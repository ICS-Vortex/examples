<?php

namespace App\Service;

use App\Constant\Parameter;
use App\Entity\AircraftClass;
use App\Entity\Airfield;
use App\Entity\Ban;
use App\Entity\ChatMessage;
use App\Entity\CurrentKill;
use App\Entity\Dogfight;
use App\Entity\Elo;
use App\Entity\Event;
use App\Entity\Flight;
use App\Entity\JsonMessage;
use App\Entity\Kill;
use App\Entity\Log;
use App\Entity\MapUnit;
use App\Entity\Mission;
use App\Entity\MissionRegistry;
use App\Entity\Model\Initiator;
use App\Entity\Model\Target;
use App\Entity\Online;
use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\RaceRun;
use App\Entity\RegistrationTicket;
use App\Entity\Server;
use App\Entity\Setting;
use App\Entity\Sortie;
use App\Entity\Streak;
use App\Entity\Theatre;
use App\Entity\Tour;
use App\Entity\Tournament;
use App\Entity\TournamentCoupon;
use App\Entity\TournamentCouponRequest;
use App\Entity\TournamentStage;
use App\Entity\Unit;
use App\Entity\UnitType;
use App\Entity\Visitor;
use App\Helper\Helper;
use App\Message\EmailMessage;
use App\Repository\CurrentKillRepository;
use App\Repository\EloRepository;
use App\Repository\EventRepository;
use App\Repository\MissionRegistryRepository;
use App\Repository\SettingRepository;
use App\Repository\SortieRepository;
use App\Repository\UnitTypeRepository;
use App\Service\Google\GoogleSheetsService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ParserService implements IParserService
{
    public string $initiator;
    public string $event;
    private array $options;
    private string $json;
    private array $logStack;
    private ?Server $server;
    private ?MissionRegistry $missionRegistry;
    private ?Tour $tour;
    private EntityManagerInterface $em;
    private ParameterBagInterface $container;
    private SerializerInterface $serializer;
    private array $data;
    private ?Tournament $tournament;
    private GoogleSheetsService $sheetsService;
    private MessageBusInterface $messageBus;
    private ContainerInterface $serviceContainer;

    public function __construct(
        EntityManagerInterface $entityManager,
        ParameterBagInterface  $container,
        SerializerInterface    $serializer,
        GoogleSheetsService    $sheetsService,
        MessageBusInterface    $messageBus,
        ContainerInterface     $serviceContainer
    )
    {
        $this->em = $entityManager;
        $this->data = [];
        $this->options = $entityManager->getRepository(Setting::class)->findAll();
        $this->container = $container;
        $this->serializer = $serializer;
        $this->sheetsService = $sheetsService;
        $this->messageBus = $messageBus;
        $this->serviceContainer = $serviceContainer;
    }

    private function getManager(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * @param string $json
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function parse(string $json): array
    {
        $this->json = $json;
        $this->loadOptions();
        $em = $this->getManager();
        if (!$this->isValidJson($json)) {
            $this->reset();
            return array(
                'status' => 1,
                'message' => 'Invalid json got in ParserService: ' . $json,
            );
        }

        $data = json_decode($json, true);
        $event = $this->event = $data['event'];
        $this->tour = $em->getRepository(Tour::class)->getCurrentTour();

        if (empty($this->tour)) {
            $this->log('Current tour not found.');
            $this->reset();
            return [
                'status' => 1,
                'message' => 'Current tour not found',
            ];
        }
        $this->data = $data;
        $this->initiator = $data['init']['nick'] ?? 'Server';
        $this->log("Parsing event {$event}");

        $this->server = $this->getServer(base64_decode($data['server']));
        if (empty($this->server)) {
            $this->log('Server is not registered in the system');
            $this->reset();
            return [
                'status' => 1,
                'message' => 'Server not registered in the system',
            ];
        }
        $this->tournament = $this->getCurrentTournament();

        $this->log('Event time is ' . $data['time']);

        if (!$this->eventExists($event)) {
            $this->log('This event is not allowed to parse');
            $this->reset();
            return [
                'status' => 999,
                'message' => 'Incorrect event',
                'type' => Logger::ERROR,
            ];
        }
        $this->log('Event is valid. Server object received. Getting mission registry object.');
        $this->missionRegistry = $this->getLastMissionRegistry();

        if (!in_array($event, EventRepository::$allowedAlwaysEvents) && empty($this->missionRegistry)) {
            $this->log('JSON event ' . $event . ' sent after SERVER-STOP event');
            $this->reset();
            return [
                'status' => 1,
                'message' => 'JSON ' . $event . ' sent after SERVER-STOP event'
            ];
        }
        try {
            $result = $this->parseData($event, $data);
        } catch (Exception $e) {
            $result = [
                'status' => 1,
                'message' => $e->getMessage() . ' in file ' . $e->getFile() . ' at line ' . $e->getLine()
            ];
        }
//        $this->sendEventToNotifications($data);

        if ($this->event !== EventRepository::EVENT_MISSION_STOP && $this->event !== EventRepository::EVENT_WON) {
            $update = $this->updateLastActivity($this->server);
            if (!$update) {
                $this->log("Failed to update last activity time for server {$this->server->getName()}");
            }
        }

        if ($this->event !== EventRepository::EVENT_OBJECTS) {
            $this->saveLog($result);
        }
        $this->reset();
        return $result;
    }

    public function updateLastActivity(Server $server): bool
    {
        $this->log("Updating SERVER #{$server->getId()} last activity..");
        $exec = $this->getManager()->getRepository(Server::class)->updateLastActivity($server);
        if ($exec) {
            $this->log('Last activity successfully updated.');
        } else {
            $this->log('Last activity not updated...Exiting...');
        }
        return $exec;
    }

    /**
     * @param string $event
     * @param array $data
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function parseData(string $event, array $data): array
    {
        return match ($event) {
            EventRepository::EVENT_MISSION_START => $this->serverOnline($data),
            EventRepository::EVENT_MISSION_STOP => $this->serverOffline($data),
            EventRepository::EVENT_ENTERED => $this->pilotEnter($data),
            EventRepository::EVENT_JOINED => $this->pilotJoined($data),
            EventRepository::EVENT_TAKEOFF => $this->pilotTakeoff($data),
            EventRepository::EVENT_LANDED => $this->pilotLanded($data),
            EventRepository::EVENT_KILLED, EventRepository::EVENT_FRIENDLY_FIRE => $this->pilotKill($data),
            EventRepository::EVENT_DIED => $this->pilotDied($data),
            EventRepository::EVENT_EJECTED => $this->pilotEjected($data),
            EventRepository::EVENT_CRASHED => $this->pilotCrashed($data),
            EventRepository::EVENT_WON => $this->won($data),
            EventRepository::EVENT_LEFT => $this->pilotLeft($data),
            EventRepository::EVENT_CHAT => $this->chat($data),
            EventRepository::EVENT_SRS => $this->parseSrs($data),
            EventRepository::EVENT_VERSION => $this->serverVersion($data),
            EventRepository::EVENT_OBJECTS => $this->insertObjects($data),
            EventRepository::EVENT_BANLIST => $this->updateBanList($data),
            EventRepository::EVENT_REGISTRATION => $this->registration($data),
            EventRepository::EVENT_FLIGHT_GROUP => $this->markFlightAsGroup($data),
            EventRepository::EVENT_WEATHER => ['status' => 0, 'message' => 'Event weather not parsed. Implement it'],
            EventRepository::EVENT_SHOT => ['status' => 0, 'message' => 'Event shot parsing not parsed. Implement it'],
            EventRepository::EVENT_RACE => $this->saveRace($data),
            EventRepository::EVENT_COUPON => $this->giveCoupon($data),
            EventRepository::EVENT_EMAIL => $this->processEmail($data),
            EventRepository::EVENT_CLOSE_SESSIONS => $this->closeSessions($data),
            default => ['status' => 1, 'message' => 'Invalid event'],
        };
    }

    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function closeSessions($data): array
    {
        $this->log('Triggered closeSessions event');
        $em = $this->getManager();
        $flush = false;
        $this->log('Searching not closed mission registries for server '. $this->server->getName());
        $sessions = $this->getManager()->getRepository(MissionRegistry::class)
            ->findBy(['server' => $this->server, 'finished' => false]);
        $this->log(sprintf('Found %d registries', count($sessions)));
        try {
            foreach ($sessions as $session) {
                $this->log(sprintf('Clearing session #%d', $session->getId()));

                $session->setFinished(true);
                $session->setEnd(new DateTime($data['time']));
                $session->setWinner(MissionRegistryRepository::DRAW);
                $this->log(sprintf('Saving session #%d', $session->getId()));
                $em->persist($this->missionRegistry);
                $this->log('Saving done');
                $flush = true;
            }
            if ($flush) {
                $this->log('Flushing changes...');
                $em->flush();
                $this->log('Changes were flushed');
            }
            $this->log('All sessions were closed');
            return [
                'status' => 0,
                'message' => 'All mission sessions were closed successfully',
            ];
        }catch (ORMException | Exception $e) {
            $this->log('Failed to clear all sessions with error: ' . $e->getTraceAsString());
            return [
                'status' => 1,
                'message' => 'Internal error detected. Failed to clear all sessions',
            ];
        }
    }

    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function saveRace($data): array
    {
        $this->log('Saving race run ...');
        $pilot = $this->getPilot($data['init']);
        $plane = $this->getPlane($data['init']['type']);
        if ($pilot === null || $plane === null) {
            $this->log('Pilot or his plane not found: ' . $data['init']['nick']);
            return [
                'status' => 1,
                'message' => 'Pilot his plane not found',
            ];
        }

        $tournament = $this->tournament;
        $stage = null;
        if (!empty($tournament)) {
            $stage = $this->em->getRepository(TournamentStage::class)->findOneBy([
                'tournament' => $tournament,
                'code' => $data['race']['type'],
            ]);
        }

//        if ($tournament->getGoogleSheetExport() && !empty($tournament->getGoogleSheetTab()) && !empty($tournament->getGoogleSheetId())) {
//            $this->sheetsService->setSheetId($tournament->getGoogleSheetId())
//                ->setTab($tournament->getGoogleSheetTab())
//                ->insert([[],[]]) // TODO Provide array of data here
//            ;
//        }
        $em = $this->getManager();

        $class = $this->getManager()->getRepository(AircraftClass::class)->findOneBy(['code' => $data['race']['class'] ?? '']);
        $race = new RaceRun();
        $race->setTournament($tournament);
        $race->setAircraftClass($class);
        $race->setTour($this->tour);
        $race->setStage($stage);
        $race->setAircraftClass($tournament->getAircraftsClass());
        $race->setServer($this->server);
        $race->setMissionRegistry($this->missionRegistry);
        $race->setPilot($pilot);
        $race->setPlane($plane);
        $race->setTime(floatval($data['init']['score']));

        try {
            $this->log('Checking stage and giving a coupon for race');
            if (!empty($stage) && $stage->getCode() === 'qualification' && $tournament->getProvideCoupons() === true) {
                $this->log('Stage found');
                $coupons = $em->getRepository(TournamentCouponRequest::class)->findBy([
                    'pilot' => $pilot,
                    'tournament' => $tournament,
                ]);
                $this->log('Searching already existing coupons for pilot ' . $pilot->getCallsign());
                if (empty($coupons)) {
                    $this->log('Race found. Giving coupon for pilot ' . $pilot->getCallsign());
                    $couponRequest = new TournamentCouponRequest();
                    $couponRequest->setPilot($pilot);
                    $couponRequest->setServer($this->server);
                    $couponRequest->setTournament($tournament);
                    $couponRequest->setActive(true);
                    $em->persist($couponRequest);
                }
            } else {
                if (empty($stage)) {
                    $this->log('Stage not found');
                }
                $this->log('Race saved, but coupon is not provided. Stage is not qualification or tournament does not proved coupons');
            }
            $em->persist($race);
            $em->flush();
            $this->log('Done');
            return ['status' => 0, 'message' => 'Race run successfully saved'];
        } catch (Exception $e) {
            $this->log("Failed to save the race run. Message: {$e->getMessage()}, File: {$e->getFile()}, Line: {$e->getLine()}");
            return ['status' => 0, 'message' => 'Error!!! Failed to save the race run'];
        }
    }

    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function markFlightAsGroup($data): array
    {
        $this->log('Marking flight as GROUP. Searching pilot ...');
        $pilot = $this->getPilot($data['init']);

        if ($pilot === null) {
            $this->log('Pilot not found: ' . $data['init']['nick']);
            return [
                'status' => 1,
                'message' => 'Pilot not found',
            ];
        }
        $this->log('Getting current flight for pilot ' . $data['init']['nick']);
        $flight = $this->getCurrentFlight($pilot);
        if (empty($flight)) {
            $this->log('Flight not found');
            return [
                'status' => 1,
                'message' => 'Flight not found',
            ];
        }
        $this->log('Flight found. #' . $flight->getId());
        if ($flight->isGroupFlight()) {
            $this->log('Flight is already marked as group');
            return ['status' => 0, 'message' => 'Flight already marked as Group flight'];
        }
        $this->log('Marking flight as group');
        $em = $this->getManager();
        $flight->setGroupFlight(true);
        try {
            $em->persist($flight);
            $em->flush();
            $this->log('Done');
            return ['status' => 0, 'message' => 'Flight marked as Group flight'];
        } catch (ORMException $e) {
            $this->log("Failed to mark flight as group. Message: {$e->getMessage()}, File: {$e->getFile()}, Line: {$e->getLine()}");
            return ['status' => 0, 'message' => 'Failed to mark flight as Group flight'];
        }
    }

    #[ArrayShape(['status' => "int", 'message' => "string"])]
    protected function registration($data): array
    {
        $this->log('Triggered REGISTRATION event');
        $em = $this->getManager();
        $this->log('Searching pilot ' . $data['init']['nick']);
        $pilot = $this->getPilot($data['init']);

        if ($pilot === null) {
            $this->log('Pilot not found');
            return [
                'status' => 1,
                'message' => 'Pilot not found',
            ];
        }
        $email = $data['init']['email'];
        $this->log('Validating email ' . $email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->log('Email ' . $email . ' is not valid');
            return [
                'status' => 1,
                'message' => 'Email address is invalid',
            ];
        }
        $this->log('Searching already registered pilot with given email');
        $findEmail = $em->getRepository(Pilot::class)->findBy([
            'email' => $email,
        ]);

        if (!empty($findEmail)) {
            $this->log('Email is already in use');
            return [
                'status' => 1,
                'message' => "Email is already in use"
            ];
        }
        $this->log('Checking if given pilot is registered');

        if ($pilot->isRegistered()) {
            $this->log("User with email {$email} already registereds");
            return [
                'status' => 1,
                'message' => "User with email {$email} already registered",
            ];
        }
        $this->log("Checking if registration ticket exists for given user");
        $ticket = $em->getRepository(RegistrationTicket::class)->findOneBy([
            'pilot' => $pilot,
        ]);

        if (!empty($ticket)) {
            $this->log("Registration request already exists");
            return [
                'status' => 1,
                'message' => 'Registration request already exists'
            ];
        }
        $this->log("Creating registration request for pilot " . $pilot->getUsername());

        $ticket = new RegistrationTicket();
        $ticket->setDeadline(new DateTime(date('Y-m-d H:i:s', time() + (60 * 30)))); //now + 30 mins
        $ticket->setPilot($pilot);
        $ticket->setEmail($email);
        $ticket->setToken(Uuid::v4());
        $this->log('Generated random token: ' . $ticket->getToken() . ' . Saving ticket');
        $em->persist($ticket);
        $em->flush();
        $this->log('Ticket saved. #' . $ticket->getId() . '. Registration request created. Exiting. ');

        return [
            'status' => 0,
            'message' => 'Registration request created'
        ];
    }

    /**
     * @param $data
     * @return array
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function updateBanList($data): array
    {
        $this->log('Triggered BANLIST event');
        $em = $this->getManager();
//        $this->log('Cleaning banlist for server ' . $this->server->getName());
//        $em->getRepository(Ban::class)->clearBanList($this->server);
//        $this->log('Done');
        $list = $data['banlist'] ?? [];

        try {
            $this->log('Starting processing banlist...');
            foreach ($list as $record) {
                $this->log('Searching pilot by ucid ' . $record['ucid']);
                /** @var Pilot $pilot */
                $pilot = $em->getRepository(Pilot::class)->findOneBy(['ucid' => $record['ucid']]);
                if (empty($pilot)) {
                    $this->log('Pilot not found...');
                    continue;
                }
                $this->log('Pilot found. Creating object Ban...');
                $pilot->setBanned(true);
                $ban = $em->getRepository(Ban::class)->findOneBy(['pilot' => $pilot, 'server' => $this->server]);
                if (empty($ban)) {
                    $ban = new Ban();
                    $ban->setPilot($pilot);
                    $ban->setServer($this->server);
                }
                $ban->setIpAddress($record['ipaddr']);
                $ban->setBannedFrom(new DateTime(date('Y-m-d H:i:s', $record['banned_from'])));
                $ban->setBannedUntil(new DateTime(date('Y-m-d H:i:s', $record['banned_until'])));
                $ban->setReason($record['reason']);
                $this->log('Persisting object Ban...');
                $em->persist($pilot);
                $em->persist($ban);
            }
            $this->log('Saving results');
            $em->flush();
            $this->log('Done. Ban list updated');
            return [
                'status' => 0,
                'message' => 'Ban list updated',
            ];
        } catch (OptimisticLockException | Exception $e) {
            $this->log(sprintf('Failed to update banlist with message: %s', $e->getMessage()));
            return [
                'status' => 1,
                'message' => 'Failed to update banlist',
            ];
        }
    }

    /**
     * @param string $event
     * @return bool
     */
    public function eventExists(string $event): bool
    {
        $this->log("Checking if event {$event} exists...");
        $events = EventRepository::$events;
        if (!in_array($event, $events, true)) {
            $this->log("Event {$event} does not exists");
            return false;
        }
        $this->log("Event {$event} exists");
        return true;
    }

    /**
     * @param $data
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function serverOnline($data): array
    {
        $this->log('Triggered START event. Checking if previous mission registry exists');
        if ($this->missionRegistry !== null) {
            $this->log('Detected previous mission registry. Closing current mission registry');
            $this->closeCurrentMissionRegistry($data);
            $this->log('Current active mission registry closed with success');
        }
        $this->log('Searching mission object');
        $mission = $this->getMission($data);
        $this->log('Clearing server data');
        $this->clearServerData($this->server);
        $em = $this->getManager();
        $this->log('Searching current TOUR');
        $currentTour = $this->getCurrentTour();

        $this->log('Creating object MissionRegistry');
        $this->missionRegistry = new MissionRegistry();
        $this->missionRegistry->setStart(new DateTime($data['time']));
        $this->missionRegistry->setTour($currentTour);
        $this->missionRegistry->setMission($mission);
        $this->missionRegistry->setServer($this->server);
        $this->server->setMission($mission);
        $this->missionRegistry->setTheatre($mission->getTheatre());
        $this->server->setIsOnline(true);
        $this->server->setStartTime(new DateTime($data['time']));
        $this->missionRegistry = $this->setWeather($this->missionRegistry, $data['weather'] ?? null);
        $this->log('Saving object MissionRegistry');
        $em->persist($this->server);
        $em->persist($this->missionRegistry);
        $em->flush();
        $this->log('Done.');
        $message = 'Server is ONLINE. MissionRegistry ' . $this->missionRegistry->getId() . ' added successfully';
        //$this->sendNotification('Server online!', 'Now you can visit ' . $this->server->getName());
        $this->log('Sending some notifications');
        $this->sendDiscordMessage(
            "Server online. Mission `{$mission->getName()}` has started / Сервер в сети. Начинается миссия `{$mission->getName()}`",
            $this->server->isSendDiscordServerNotifications()
        );
        $this->log('Done. ' . $message);
        return [
            'status' => 0,
            'message' => $message,
        ];
    }

    /**
     * @param $data
     * @return Mission
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getMission($data): Mission
    {
        $this->log('Searching mission...');
        $em = $this->getManager();
        $theatre = $this->getTheatre($data['theatre'] ?? null);
        $this->log('Theatre: ' . $data['theatre'] ?? null . ' . Searching mission');

        $mission = $em->getRepository(Mission::class)->findOneBy(array(
            'name' => $data['mission'],
            'server' => $this->server,
        ));

        if (empty($mission)) {
            $this->log('Mission not found.Creating object Mission');
            $mission = new Mission();
            $mission->setName($data['mission']);
            $mission->setTheatre($theatre);
            $mission->setDescription($data['description']);
            $mission->setServer($this->server);
            $this->log('Saving object Mission');
            $em->persist($mission);
            $em->flush();
            $this->log('Done.');
            return $mission;
        }
        $this->log('Found already created mission');
        $mission->setTheatre($theatre);
        $mission->setDescription($data['description']);
        $this->log('Updating theatre and description');
        $em->persist($mission);
        $em->flush();
        $this->log('Done');
        return $mission;
    }

    /**
     * @param $name
     * @return Theatre|null
     */
    public function getTheatre($name): ?Theatre
    {
        $this->log('Searchign theatre by name: ' . $name);
        $theatre = $this->getManager()->getRepository(Theatre::class)->getTheatre($name);
        if (empty($theatre)) {
            $this->log('Theatre not found');
        } else {
            $this->log('Theatre found');
        }

        return $theatre;
    }

    public function clearServerData(Server $server): bool
    {
        $this->log('Clearing server data');
        $exec = $this->em->getRepository(Server::class)->clearTemporaryData($server);
        if ($exec) {
            $this->log('Successfully cleared server data');
        } else {
            $this->log('Failed to clear server data');
        }
        return $exec;
    }

    /**
     * @return Tour
     */
    public function getCurrentTour(): Tour
    {
        return $this->tour;
    }

    /**
     * @param $data
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function serverOffline($data): array
    {
        $this->log('Triggered STOP event');
        $em = $this->getManager();
        $time = $data['time'];
        $this->log('Running getCurrentTour');
        $tour = $this->getCurrentTour();

        $this->log('Searching current flights');
        $flights = $em->getRepository(Flight::class)->findBy([
            'server' => $this->server,
        ]);
        if (!empty($flights)) {
            $this->log('Found ' . count($flights) . ' flights');
            foreach ($flights as $flight) {
                $this->log('Closing flight #' . $flight->getId());
                /** @var Flight $flight */
                $pilot = $flight->getPilot();
                $plane = $flight->getPlane();
                $airfield = $flight->getAirfield();
                $event = Event::LANDING;
                $this->log('Checking if flight #' . $flight->getId() . ' is started');
                if ($flight->isStarted()) {
                    $this->log('Closing flight #' . $flight->getId());
                    $em->getRepository(Flight::class)->endFlight($flight, $time, $airfield);
                }
                if (!empty($this->missionRegistry)) {
                    $this->log('Adding landing event for flight #' . $flight->getId());
                    $em->getRepository(Event::class)
                        ->addEvent($event, $time, $this->server, $pilot, $flight->getSide(), $plane, $tour, $this->missionRegistry);
                }
            }
        }
        $this->log('Setting server offline and clearing version');
        $this->server->setIsOnline(false);
        //$this->server->setVersion(null); // TODO We keep version always and update it on start
        $this->log('Clearing server temp info');
        $this->clearServerData($this->server);
        $this->log('Persisting changes');
        $em->persist($this->server);
        $em->flush();
        $this->sendNotification('Server offline!', 'Bye-bye.');
        $this->sendDiscordMessage('Server offline', $this->server->isSendDiscordServerNotifications());
        $this->log('Done.Server offline.');

        return [
            'status' => 0,
            'message' => 'Server offline',
        ];
    }

    /**
     * Returns object of mission registry information
     * @return MissionRegistry|null
     */
    public function getLastMissionRegistry(): ?MissionRegistry
    {
        $em = $this->getManager();
        $this->log('Searching active mission registry in database for server #' . $this->server->getId() . ' ' . $this->server->getName());
        $search = $em->getRepository(MissionRegistry::class)
            ->findBy(['finished' => false, 'server' => $this->server], ['id' => 'DESC']);
        $registry = null;

        if (!empty($search)) {
            $this->log('Mission registry found');
            $registry = reset($search);
            try {
                unset($search[0]);
                /** @var MissionRegistry $session */
                foreach ($search as $session) {
                    $this->log(sprintf('Closing mission session %d ', $session->getId()));
                    $session->setWinner('DRAW');
                    $session->setFinished(true);
                    $session->setEnd(new DateTime($this->data['time'] ?? date('Y-m-d H:i:s')));
                    $em->persist($session);
                }
                $em->flush();
                $this->log('All not finished missions were closed with success');
            } catch (ORMException | Exception $e) {
                $this->log('Failed to close all mission sessions: ' . $e->getTraceAsString());
            }
        } else {
            $this->log('Mission registry record not found ');
        }

        return $registry;
    }

    /**
     * @param array $data
     * @param bool $target
     * @return Pilot|null
     */
    public function getPilot(array $data, bool $target = false): ?Pilot
    {
        $this->log('Searching pilot object: ' . $data['nick'] . ' by class ');
        $class = Initiator::class;
        if ($target) {
            $class = Target::class;
        }
        $this->log('Searching pilot object: ' . $data['nick'] . ' by class ' . $class);

        $pilot = $this->serializer->deserialize(json_encode($data), $class, 'json', ['groups' => 'app_event']);

        $search = $this->em->getRepository(Pilot::class)->getPilot($pilot);
        if (!empty($search)) {
            $this->log('Pilot object found');
        } else {
            $this->log('Pilot object not found');
        }
        return $search;
    }

    /**
     * @param $identifier
     * @return Server|null
     */
    public function getServer($identifier): ?Server
    {
        $this->log('Searching server by identifier :' . $identifier);
        /** @var Server $server */
        $server = $this->em->getRepository(Server::class)->findOneBy([
            'identifier' => $identifier,
        ]);
        if (!empty($server)) {
            $this->log('Server object found');
        } else {
            $this->log('Server object not found');
        }
        return $server;
    }


    /**
     * @param $data
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function pilotEnter(array $data): array
    {
        $this->log('Triggered ENTER event');
        $em = $this->em;
        $this->log('Searching pilot');
        $pilot = $this->getPilot($data['init']);
        if ($pilot === null) {
            $this->log('Pilot not found in ENTER event');
            return [
                'status' => 1,
                'message' => 'Pilot not found after insertion',
            ];
        }
        $this->log('Searching current tour');
        $tour = $em->getRepository(Tour::class)->getCurrentTour();
        $this->log('Running clearOnline');
        $this->clearOnline($pilot);
        $this->log('Running clearFlights');
        $this->clearFlights($pilot);

        $this->log('Creating object Online as spectator');
        $online = new Online();
        $online->setSide(Online::SPECTATOR);
        $online->setEnterTime(new DateTime($data['time']));
        $online->setPilot($pilot);
        $online->setServer($this->server);

        $this->log('Creating object Visitor');
        $visitor = new Visitor();
        $visitor->setPilot($pilot);
        $visitor->setTour($tour);
        $visitor->setServer($this->server);
        $visitor->setEnterTime(new DateTime($data['time']));
        $visitor->setMissionRegistry($this->missionRegistry);

        $this->log('Setting pilot ONLINE flag');
        $pilot->setOnline(true);
        $this->log('Saving results');
        $em->persist($visitor);
        $em->persist($pilot);
        $em->flush();
        $this->log('Done. Saving ONLINE object in REPO');
        $em->getRepository(Online::class)->save($online);
        $this->log('Done. Sending some notifications');
        $this->sendNotification('New visitor!', 'Pilot ' . $pilot->getNickname() . ' entered server ');
        $message = "Pilot `{$pilot->getNickname()}` entered server ";
        $this->sendDiscordMessage($message, $this->server->isSendDiscordFlightNotifications());
        $this->sendOnlineTrigger($pilot, 'add');
        $this->log('Done. Successfully parsed event ENTER');
        return [
            'status' => 0,
            'message' => 'Pilot ' . $pilot->getNickname() . ' entered server ',
        ];
    }

    /**
     * @param $data
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function pilotJoined(array $data): array
    {
        $this->log('Running pilotJoined function');

        return match ($data['init']['side']) {
            'RED' => $this->pilotJoinedRed($data),
            'BLUE' => $this->pilotJoinedBlue($data),
            'SPECTATORS' => $this->pilotJoinedSpectators($data),
            default => ['status' => 1, 'message' => 'Error. Impossible to detect pilot TEAM'],
        };
    }

    /**
     * @param array $data
     * @return array
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function pilotJoinedRed(array $data): array
    {
        $this->log('Triggered JOIN RED event');
        $em = $this->em;
        $this->log('Searching pilot');
        $pilot = $this->getPilot($data['init']);
        if ($pilot === null) {
            $this->log("Pilot {$data['init']['nick']} not found");
            return [
                'status' => 0,
                'message' => "Pilot {$data['init']['nick']} not found ",
            ];
        }
        $this->log('Searching plane ' . $data['init']['type']);
        $plane = $this->getPlane($data['init']['type']);
        $side = $data['init']['side'];
        $this->log('Searching current flight ');
        $flight = $this->getCurrentFlight($pilot);
        if (!empty($flight) && $flight->isStarted()) {
            $this->log('Found active flight. Ending it and adding DEATH event ');
            $em->getRepository(Flight::class)->endFlight($flight, $data['time'], null, SortieRepository::STATUS_DEATH);
            $this->log('Done.');
        } else {
            $this->log('Active flight not found. Status: OK ');
        }
        $this->log('Clearing online data ');
        $em->getRepository(Online::class)->clearOnline($pilot);
        $this->log('Creating online data ');
        $this->createOnline($pilot, $data['time'], $plane, $side);
        $this->log('Creating FLIGHT data ');
        $this->createFlight($pilot, $plane, $side);
        $this->log('Sending some notifications');
        $this->sendDiscordMessage("Pilot `{$pilot->getNickname()}` joined RED team / Пилот `{$pilot->getNickname()}` присоединился к красным", $this->server->isSendDiscordFlightNotifications());
        $this->sendOnlineTrigger($pilot, 'replace');
        $this->log('Done. JOIN RED event successfully parsed');
        return [
            'status' => 0,
            'message' => 'Pilot ' . $pilot->getNickname() . ' joined RED ',
        ];
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function pilotJoinedBlue(array $data): array
    {
        $this->log('Triggered JOIN BLUE event');
        $em = $this->getManager();
        $this->log('Searching pilot');
        $pilot = $this->getPilot($data['init']);
        if ($pilot === null) {
            $this->log("Pilot {$data['init']['nick']} not found");
            return [
                'status' => 0,
                'message' => "Pilot {$data['init']['nick']} not found ",
            ];
        }
        $this->log('Searching plane ' . $data['init']['type']);
        $plane = $this->getPlane($data['init']['type']);
        $this->log('Searching current flight');
        $flight = $this->getCurrentFlight($pilot);
        $side = $data['init']['side'];
        if (!empty($flight) && $flight->isStarted()) {
            $this->log('Detected active current flight. Finishing and addind event DEATH');
            $em->getRepository(Flight::class)->endFlight($flight, $data['time'], null, SortieRepository::STATUS_DEATH);
            $this->log('Done.');
        } else {
            $this->log('Active current flight not found. Status: OK');
        }
        $this->log('Clearing online data');
        $this->clearOnline($pilot);
        $this->log('Creating online data');
        $this->createOnline($pilot, $data['time'], $plane, $side);
        $this->log('Creating FLIGHT data');
        $this->createFlight($pilot, $plane, $side);
        $this->log('Sending some notifications');
        $this->sendDiscordMessage("Pilot `{$pilot->getNickname()}` joined BLUE team / Пилот `{$pilot->getNickname()}` присоединился к синим", $this->server->isSendDiscordFlightNotifications());
        $this->sendOnlineTrigger($pilot, 'replace');
        $this->log('Event JOIN BLUE successfully parsed');
        return ['status' => 0, 'message' => 'Pilot ' . $pilot->getNickname() . ' joined BLUE'];
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function pilotJoinedSpectators(array $data): array
    {
        $this->log('Triggered JOIN Spectators event');
        $em = $this->getManager();
        $this->log('Searching pilot');
        $pilot = $this->getPilot($data['init']);
        if ($pilot === null) {
            $this->log("Pilot {$data['init']['nick']} not found");
            return ['status' => 1, 'message' => 'Pilot ' . $data['init']['nick'] . ' not found in DB '];
        }
        $this->log('Searching current flight');
        $flight = $this->getCurrentFlight($pilot);
        if (!empty($flight) && $flight->isStarted()) {
            $this->log('Detected active current flight. Finishing and addind event DEATH');
            $em->getRepository(Flight::class)->endFlight($flight, $data['time'], null, SortieRepository::STATUS_DEATH);
            $this->log('Done.');
        } else {
            $this->log('Active current flight not found. Status: OK');
        }
        $this->log('Clearing online data');
        $this->clearOnline($pilot);
        $this->log('Creating online data');
        $this->createOnline($pilot, $data['time']);
        $this->log('Sending some notifications');
        $this->sendDiscordMessage("Pilot `{$pilot->getNickname()}` joined Spectators team", $this->server->isSendDiscordFlightNotifications());
        $this->sendOnlineTrigger($pilot, 'replace');
        $this->log('Event JOIN Spectators successfully parsed');
        return ['status' => 0, 'message' => 'Pilot ' . $pilot->getNickname() . ' joined SPECTATORS '];
    }

    /**
     * @param Pilot $pilot
     * @param $time
     * @param Plane|null $plane
     * @param string $side
     * @return bool
     * @throws Exception
     */
    public function createOnline(Pilot $pilot, $time, Plane $plane = null, $side = Online::SPECTATOR): bool
    {
        $em = $this->getManager();
        $online = new Online();
        $online->setPilot($pilot);
        $online->setPlane($plane);
        $online->setSide($side);
        $online->setEnterTime(new DateTime($time));
        $online->setServer($this->server);
        try {
            $em->persist($online);
            $em->flush();
            return true;
        } catch (ORMException $e) {
            $this->log("Message: {$e->getMessage()}, File: {$e->getFile()}, line: {$e->getLine()}");
            return false;
        }
    }

    /**
     * @param $pilot Pilot
     * @return Flight|null
     */
    public function getCurrentFlight(Pilot $pilot): ?Flight
    {
        return $this->em->getRepository(Flight::class)->getCurrentFlight($pilot);
    }

    /**
     * @param $plane
     * @return null|Plane
     */
    public function getPlane(string $plane): ?Plane
    {
        return $this->em->getRepository(Plane::class)->getPlane($plane);
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function pilotTakeoff(array $data): array
    {
        $em = $this->getManager();
        $pilot = $this->getPilot($data['init']);
        if ($pilot === null) {
            return [
                'status' => 1,
                'message' => "Pilot {$data['init']['nick']} not found ",
            ];
        }
        $plane = $this->getPlane($data['init']['type']);
        $airfield = $this->getAirfield($data['field']['name']);
        $tour = $this->getCurrentTour();
        $side = $data['init']['side'];
        if ($side === 'SPECTATORS') {
            $this->log('Takeoff function received SPECTATORS side, instead RED or BLUE');
            $side = 'RED';
        }
        $flight = $this->createFlight($pilot, $plane, $side);
        if (empty($flight)) {
            $this->log('Error!!! Failed to find FLIGHT after createFlight function.');
            return [
                'status' => 1,
                'message' => 'Failed to start FLIGHT'
            ];
        }
        $flight->setTheatre($this->missionRegistry->getMission()->getTheatre());
        $flight->setNightFlight($em->getRepository(Theatre::class)->isNightTime($data['simulationTime'], $flight));
        $flight->setTour($tour);
        $flight->setAirfield($airfield);
        $flight->setBadWeatherFlight($em->getRepository(Flight::class)->isBadWeatherFlight($flight));
        $flight->setStartFlightTime(new DateTime($data['time']));
        $flight->setStarted(true);
        try {
            $em->persist($flight);
            $em->flush();
            $em->getRepository(Event::class)->addEvent(Event::TAKEOFF, $data['time'], $this->server, $pilot, $side, $plane, $tour, $this->missionRegistry);
            $airfieldTitle = $airfield === null ? 'field' : $airfield->getTitle();
            $this->sendDiscordMessage("Pilot `{$pilot->getNickname()}` took-off from {$airfieldTitle}", $this->server->isSendDiscordFlightNotifications());
            return ['status' => 0, 'message' => 'Pilot ' . $pilot->getNickname() . ' took-off from ' . $airfieldTitle];
        } catch (ORMException $e) {
            $this->log($e->getMessage() . "File: {$e->getFile()}, line: {$e->getLine()}");
            return ['status' => 1, 'message' => 'Failed to parse TAKEOFF event'];
        }
    }

    /**
     * @param string $title
     * @return Airfield|null
     */
    public function getAirfield(string $title): ?Airfield
    {
        $this->log('Getting airfield by title ' . $title);
        $airfield = $this->getManager()->getRepository(Airfield::class)->getAirfieldByTitle($title);
        if (empty($airfield)) {
            $this->log('Failed to find airfield ' . $title);
        } else {
            $this->log('Airfield found: #' . $airfield->getId());
        }
        return $airfield;
    }

    /**
     * @param array $data
     * @return array
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function pilotLanded(array $data): array
    {
        $this->log('Triggered LANDED event');
        $em = $this->getManager();
        $this->log('Searching pilot');
        $pilot = $this->getPilot($data['init']);
        if ($pilot === null) {
            $this->log("Pilot {$data['init']['nick']} not found");
            return [
                'status' => 1,
                'message' => "Pilot {$data['init']['nick']} not found ",
            ];
        }
        $this->log('Searching plane');
        $plane = $this->getPlane($data['init']['type']);
        $this->log('Searching MR');
        $missionRegistry = $this->getLastMissionRegistry();
        $this->log('Searching Tour');
        $tour = $this->getCurrentTour();
        $this->log('Searching Current flight');
        $flight = $this->getCurrentFlight($pilot);
        if (!empty($flight) && $flight->isStarted()) {
            $this->log('Found active flight. Searching airfield ' . $data['field']['name']);
            $airfieldAt = $this->getAirfield($data['field']['name']);
            $this->log('Done. Finishing flight');
            $flightFinished = $em->getRepository(Flight::class)->endFlight($flight, $data['time'], $airfieldAt);
            if ($flightFinished) {
                if (!empty($airfieldAt)) {
                    $this->log('Adding landing event');
                    $em->getRepository(Event::class)->addEvent(Event::LANDING, $data['time'], $this->server, $pilot, $data['init']['side'], $plane, $tour, $missionRegistry);
                } else {
                    $this->log('Adding CRASH landing event');
                    $em->getRepository(Event::class)->addEvent(Event::CRASHLANDING, $data['time'], $this->server, $pilot, $data['init']['side'], $plane, $tour, $missionRegistry);
                }
                $this->log('Sending some notifications');
                $this->sendDiscordMessage("Pilot `{$pilot->getNickname()}` landed / Пилот `{$pilot->getNickname()}` приземлился", $this->server->isSendDiscordFlightNotifications());
                $this->log('Event LANDED successfully parsed ');
                return [
                    'status' => 0,
                    'message' => 'Pilot ' . $pilot->getNickname() . ' landed',
                ];
            }
            $this->log("Flight with #{$flight->getId()} not finished");
            return [
                'status' => 1,
                'message' => "Flight with #{$flight->getId()} not finished"
            ];
        }
        $this->log('Flight not found for pilot ' . $pilot->getNickname());
        return [
            'status' => 1,
            'message' => 'Flight not found for pilot ' . $pilot->getNickname(),
        ];
    }

    /**
     * @param array $data
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function pilotKill(array $data): array
    {
        $this->log('Checking pilot kill TYPE');
        $isGroundTarget = (bool)$data['targ']['gr'];
        $this->log('Ground target: ' . strval($isGroundTarget));
        $isHuman = (bool)$data['targ']['hum'];
        $this->log('Human target: ' . strval($isHuman));

        if (!$isGroundTarget && !$isHuman) {
            $this->log('Triggering pilotKilledAi function');
            return $this->pilotKilledAi($data);
        }

        if ($isHuman && !$isGroundTarget) {
            $this->log('Triggering pilotKilledHuman function');
            return $this->pilotKilledHuman($data);
        }
        if ($isGroundTarget) {
            $this->log('Triggering pilotDestroyedGroundTarget function');
            return $this->pilotDestroyedGroundTarget($data);
        }
        $this->log('Undefined KILL type (Not AI/Human/GKill)');
        return [
            'status' => 1,
            'message' => 'Undefined KILL type (Not AI/Human/GKill)'
        ];
    }

    /**
     * @param $data
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function pilotDestroyedGroundTarget(array $data): array
    {
        $this->log('Triggered KILL Ground target EVENT.');
        $em = $this->getManager();
        $this->log('Searching pilot.');
        $pilot = $this->getPilot($data['init']);
        $this->log('Searching plane.');
        $plane = $this->getPlane($data['init']['type']);

        if ($pilot === null || $plane === null) {
            $this->log("Pilot {$data['init']['nick']} or his plane {$data['init']['type']} not found ");
            return [
                'status' => 1,
                'message' => "Pilot {$data['init']['nick']} or his plane {$data['init']['type']} not found ",
            ];
        }
        $this->log('Searching MR.');
        $missionRegistry = $this->getLastMissionRegistry();
        $this->log('Searching Tour.');
        $tour = $this->getCurrentTour();
        $this->log('Setting all data.');
        $side = $data['init']['side'];
        $targetSide = $data['targ']['side'];
        if (empty($targetSide) || empty($side)) {
            $this->log("Empty side VALUE. Initiator side: {$side}, Target side: {$targetSide}. Exiting...");
            return [
                'status' => 1,
                'message' => "Invalid (empty) side VALUE in KILL event. Initiator side: {$side}, Target side: {$targetSide}",
            ];
        }
        $time = $data['time'] ?? date('Y-m-d H:i:s');
        $friendly = $side === $targetSide;
        $points = (int)$data['init']['score'] ?? 0;
        /** @var Target $target */
        $target = $this->serializer->deserialize(json_encode($data['targ']), Target::class, 'json', ['groups' => 'app_event']);
        $this->log('Searching unit.');
        $unit = $this->getUnit($target);

        $this->log('Creating object Kill.');
        $kill = new Kill();
        $kill->setUnit($unit);
        $kill->setKillTime(new DateTime($time));
        $kill->setPoints($points);
        $kill->setFriendly($friendly);
        $kill->setPlane($plane);
        $kill->setPilot($pilot);
        $kill->setServer($this->server);
        $kill->setTour($tour);
        $kill->setRegisteredMission($missionRegistry);
        $kill->setSide($side);
        $kill->setTargetSide($targetSide);
        $kill->setGroundKill($unit->getType()->getTitle() !== UnitTypeRepository::TYPE_SHIPS);
        $kill->setSeaKill($unit->getType()->getTitle() === UnitTypeRepository::TYPE_SHIPS);
        $em->persist($kill);
        $em->flush();

        $this->log('Creating object CurrentKill.');
        $cK = new CurrentKill();
        $cK->setPilot($pilot);
        $cK->setPlane($plane);
        $cK->setServer($this->server);
        $cK->setTour($this->tour);
        $cK->setIsHuman(false);
        $cK->setSide($side);
        $cK->setVictimSide($data['targ']['side'] ?? 'RED');
        $cK->setAction(CurrentKillRepository::ACTION_DESTROYED);
        $cK->setIsAi(false);
        $cK->setUnit($unit);
        $cK->setRegisteredMission($this->missionRegistry);
        $cK->setPoints($points);
        $cK->setKillTime(new DateTime($time));

        $this->log('Saving objects.');
        $em->persist($kill);
        $em->persist($cK);
        $em->getRepository(Streak::class)->updateStreaks($pilot, $this->server, true, $points);
        $em->flush();
        $this->log('Saving done. Sending some notifications');

        if ($kill->getFriendly()) {
            $message = "@here Attention! Friendly fire detected! ";
            $message .= "Pilot `{$pilot->getNickname()}` killed friendly ground unit `{$unit->getName()}`. ";
            $message .= "Pilot UCID: `{$pilot->getUcid()}`";
            $this->sendDiscordMessage($message, $this->server->isSendDiscordFriendlyFireNotifications());
        }
        $this->sendDiscordMessage("Pilot {$pilot->getNickname()} destroyed unit {$unit->getName()}",
            $this->server->isSendDiscordCombatNotifications());
        $this->log('Done. Marking flight as COMBAT');
        $em->getRepository(Flight::class)->markFlightAsCombat($pilot);
        $this->log('Done. Event KILL successfully parsed');
        return [
            'status' => 0,
            'message' => 'Pilot ' . $pilot->getNickname() . ' destroyed ' . $unit->getName()
        ];
    }

    /**
     * @param $type
     * @return UnitType|null|object
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getUnitCategory($type)
    {
        $this->log('Searching unit category ' . $type);
        $em = $this->getManager();
        $category = $em->getRepository(UnitType::class)->findOneBy(array(
            'title' => $type,
        ));


        if (empty($category)) {
            $this->log('Category not found in DB. Adding into database.');
            $category = new UnitType();
            $category->setTitle($type);

            $em->persist($category);
            $em->flush();
        }
        $this->log('Unit category detected.');
        return $category;
    }

    /**
     * @param Target $target
     * @return Unit
     * @throws ORMException
     */
    public function getUnit(Target $target): Unit
    {
        $this->log('Processing unit object');
        $em = $this->getManager();
        $this->log('Getting unit category.');
        $category = $this->getUnitCategory($target->getCat());
        $unit = $em->getRepository(Unit::class)->findOneBy(array(
            'type' => $category,
            'name' => $target->getType(),
        ));

        if (empty($unit)) {
            $this->log("Unit {$target->getType()} not found in database. Adding new unit...");
            $unit = new Unit();
            $unit->setName($target->getType());
            $unit->setType($category);

            switch ($category->getTitle()) {
                case 'Planes':
                case 'Helicopters':
                    $unit->setAirUnit(true);
                    break;
                case 'Ships':
                    $unit->setSeaUnit(true);
                    break;
                default:
                    $unit->setGroundUnit(true);
            }

            $em->persist($unit);
            $em->flush();
        }
        return $unit;
    }

    /**
     * @param $data
     * @return array
     * @throws ORMException
     * @throws Exception
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function pilotKilledHuman(array $data): array
    {
        $this->log('Triggered KILL Human event');
        $em = $this->getManager();
        $this->log('Searching pilot');
        $pilot = $this->getPilot($data['init']);
        $this->log('Searching victim');
        $victim = $this->getPilot($data['targ']);
        $this->log('Searching pilot plane');
        $plane = $this->getPlane($data['init']['type']);
        $this->log('Searching victim plane');
        $victimPlane = $this->getPlane($data['targ']['type']);
        $this->log('Search done.');

        if ($pilot === null || $victim === null || $plane === null || $victimPlane === null) {
            $this->log('Pilot or victim or planes not found. Exiting...');
            return [
                'status' => 1,
                'message' => 'Pilot or victim or planes not found',
            ];
        }
        $this->log('Searching MR');
        $missionRegistry = $this->getLastMissionRegistry();
        $this->log('Searching tour');
        $tour = $this->getCurrentTour();
        $side = $data['init']['side'];
        $time = $data['time'];
        $points = $data['init']['score'] ?? 0;

        $victimSide = $data['targ']['side'];

        $this->log('Creating object Dogfight');
        $dogfight = new Dogfight();
        $dogfight->setFriendly($data['init']['side'] === $data['targ']['side']);
        $dogfight->setSide($side);
        $dogfight->setVictimSide($victimSide);
        $dogfight->setVictim($victim);
        $dogfight->setPilot($pilot);
        $dogfight->setPlane($plane);
        $dogfight->setPvp(true);
        $dogfight->setServer($this->server);
        $dogfight->setVictimPlane($victimPlane);
        $dogfight->setKillTime(new DateTime($time));
        $dogfight->setPoints($points);
        $dogfight->setRegisteredMission($missionRegistry);
        $dogfight->setTour($tour);
        $dogfight->setInAir($this->killedInAir($dogfight));
        $dogfight->setEloCalculated(false);

        $this->log('Creating object CurrentKill');
        $cK = new CurrentKill();
        $cK->setPilot($pilot);
        $cK->setPlane($plane);
        $cK->setServer($this->server);
        $cK->setTour($this->tour);
        $cK->setIsHuman(true);
        $cK->setSide($side);
        $cK->setVictimPlane($victimPlane);
        $cK->setVictim($victim);
        $cK->setVictimSide($data['targ']['side']);
        $cK->setAction(CurrentKillRepository::ACTION_DESTROYED);
        $cK->setIsAi(false);
        $cK->setRegisteredMission($this->missionRegistry);
        $cK->setPoints($points);
        $cK->setKillTime(new DateTime($time));

        $this->log('Saving objects...');

        $em->persist($dogfight);
        $em->persist($cK);
        $em->flush();
        if ($dogfight->isInAir()) {
            $em->getRepository(Streak::class)->updateStreaks($pilot, $this->server, true, 1);
        }

        $this->log('Saving done.');
        if ($dogfight->isFriendly()) {
            $message = "@here Attention! Friendly fire detected! ";
            $message .= "Pilot `{$pilot->getNickname()}` killed pilot `{$victim->getNickname()}`. ";
            $message .= "Pilot UCID: `{$pilot->getUcid()}`";
            $this->sendDiscordMessage($message, $this->server->isSendDiscordFriendlyFireNotifications());
        }
        $this->log('Saving done. Calling ELO Calculation');
        $this->calculateElo($dogfight);
        $this->log('Done. Sending some notifications');
        $this->sendDiscordMessage("Pilot `{$pilot->getNickname()}` killed player",
            $this->server->isSendDiscordCombatNotifications());
        $this->log('Pilot ' . $pilot->getNickname() . ' killed ' . $victim->getNickname());
        $sendNotification = $this->sendNotification(
            'Dogfight!',
            "Pilot {$pilot->getNickname()} killed {$victim->getNickname()}"
        );
        $this->log('Done. Marking flight as COMBAT');
        $em->getRepository(Flight::class)->markFlightAsCombat($pilot);
        $this->log('Done. KILL HUMAN event successfully parsed.');
        return [
            'status' => 0,
            'message' => 'Pilot ' . $pilot->getNickname() . ' killed ' . $victim->getNickname(),
        ];
    }

    /**
     * @param Dogfight $dogfight
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function calculateElo(Dogfight $dogfight): bool
    {
        $this->log('ELO calculation triggered. Checkig if dogfight is VALID for calculation');
        if (!$dogfight->isValidEloDogfight()) {
            $this->log('Dogfight IS NOT VALID for calculation. Exiting');
            return false;
        }
        $this->log('Dogfight IS VALID for calculation');
        $em = $this->getManager();
        $this->log('Calculating ' . EloRepository::ELO_TYPE_PLANE_GENERAL . ' ELO');
        $em->getRepository(Elo::class)->calculateElo($dogfight, EloRepository::ELO_TYPE_PLANE_GENERAL);
        $this->log('Done. Calculating ' . EloRepository::ELO_TYPE_PLANE_TOUR . ' ELO');
        $em->getRepository(Elo::class)->calculateElo($dogfight, EloRepository::ELO_TYPE_PLANE_TOUR);
        $this->log('Done. Calculating ' . EloRepository::ELO_TYPE_SIDE_GENERAL . ' ELO');
        $em->getRepository(Elo::class)->calculateElo($dogfight);
        $this->log('Done. Calculating ' . EloRepository::ELO_TYPE_SIDE_TOUR . ' ELO');
        $em->getRepository(Elo::class)->calculateElo($dogfight, EloRepository::ELO_TYPE_SIDE_TOUR);
        $this->log('Done. Updating dogfight with setEloCalculated');
        $dogfight->setEloCalculated(true);
        $this->log('Done. Saving dogfight');
        $em->persist($dogfight);
        $em->flush();
        $this->log('Done. Exiting from ELO calculation');
        return true;
    }

    /**
     * @param Dogfight $dogfight
     * @return bool
     */
    public function killedInAir(Dogfight $dogfight): bool
    {
        $this->log('Checking if dogfight was in AIR. Searching victim and his current flight');
        $victim = $dogfight->getVictim();
        $victimFlight = $this->getCurrentFlight($victim);
        $this->log('Search done...');
        if (!empty($victimFlight) && $victimFlight->isStarted()) {
            $this->log('Victim flight found. Dogfight was in AIR');
            return true;
        }
        $this->log('Victim flight not found. Searching last flight');

        /** @var Sortie $lastVictimFlight */
        $lastVictimFlight = $this->getManager()->getRepository(Sortie::class)->findOneBy([
            'pilot' => $victim,
            'server' => $this->server,
            'registeredMission' => $this->missionRegistry,
        ], ['id' => 'DESC']);

        if (empty($lastVictimFlight)) {
            $this->log('Victim last flight not found. Exiting...');
            return false;
        }
        $this->log('Victim last flight found. Checking dogfight kill time');
        if (empty($dogfight->getKillTime())) {
            $this->log('Kill time is empty. Exiting');
            return false;
        }
        $this->log('Checking last victim flight END time');

        if (!empty($lastVictimFlight->getEndFlight())) {
            $this->log('Calculating difference ...');
            $end = strtotime($lastVictimFlight->getEndFlight()->format('Y-m-d H:i:s'));
            $now = strtotime($dogfight->getKillTime()->format('Y-m-d H:i:s'));
            $this->log("Difference between {$now} and {$end}");

            if (($now - $end) < 5) {
                $this->log('Difference is less than 5 seconds. Dogfight was in AIR');
                return true;
            } else {
                $this->log('Difference is MORE than 5 seconds. Dogfight was not in AIR');
            }
        } else {
            $this->log('Last flight END time is empty ... Exiting...');
        }
        return false;
    }

    /**
     * @param $data
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function pilotKilledAi(array $data): array
    {
        $this->log('Event KILL (AI) triggered');
        $em = $this->getManager();
        $this->log('Searching pilot');
        $pilot = $this->getPilot($data['init']);
        if (empty($pilot)) {
            $this->log('Pilot not found by UCID in kill AI event. Exiting ...');
            return [
                'status' => 1,
                'message' => "Pilot {$data['init']['nick']} not found ",
            ];
        }
        $this->log('Searching plane ...');
        $plane = $this->getPlane($data['init']['type']);
        $this->log('Searching Mission Registry ...');
        $missionRegistry = $this->getLastMissionRegistry();
        $this->log('Searching Tour ...');

        $tour = $this->getCurrentTour();
        $side = $data['init']['side'];
        $time = $data['time'];
        $points = $data['init']['score'] ?? 0;
        /** @var Target $target */
        $target = $this->serializer->deserialize(json_encode($data['targ']), Target::class, 'json', ['groups' => 'app_event']);
        $this->log('Searching UNIT ...');

        $unit = $this->getUnit($target);

        $initiatorSide = $data['init']['side'] ?? 'RED';
        $targetSide = $data['targ']['side'] ?? 'BLUE';

        $this->log('Creating DOGFIGHT object ...');
        $dogfight = new Dogfight();
        $dogfight->setFriendly($initiatorSide === $targetSide);
        $dogfight->setSide($side);
        $dogfight->setVictimSide($targetSide);
        $dogfight->setAi($unit);
        $dogfight->setPilot($pilot);
        $dogfight->setPlane($plane);
        $dogfight->setKillTime(new DateTime($time));
        $dogfight->setPoints($points);
        $dogfight->setRegisteredMission($missionRegistry);
        $dogfight->setTour($tour);
        $dogfight->setEloCalculated(true);
        $dogfight->setServer($this->server);
        $dogfight->setInAir(true);

        $this->log('Creating CurrentKill object ...');
        $cK = new CurrentKill();
        $cK->setPilot($pilot);
        $cK->setPlane($plane);
        $cK->setServer($this->server);
        $cK->setTour($this->tour);
        $cK->setIsHuman(false);
        $cK->setSide($side);
        $cK->setVictimSide($targetSide);
        $cK->setAction(CurrentKillRepository::ACTION_KILLED);
        $cK->setIsAi(true);
        $cK->setUnit($unit);
        $cK->setRegisteredMission($this->missionRegistry);
        $cK->setPoints($points);
        $cK->setKillTime(new DateTime($time));

        $this->log('Saving all objects ...');

        $em->persist($dogfight);
        $em->persist($cK);
        $em->flush();
        $this->log('Done. Sending some notifications ...');

        if ($dogfight->getFriendly()) {
            $message = "@here Attention! Friendly fire detected! ";
            $message .= "Pilot `{$pilot->getNickname()}` killed friendly AI `{$unit->getName()}`.";
            $message .= "Pilot UCID: `{$pilot->getUcid()}`";
            $this->sendDiscordMessage($message, $this->server->isSendDiscordFriendlyFireNotifications());
        }
        $this->sendNotification('Dogfight!', "Pilot {$pilot->getNickname()} killed AI {$unit->getName()}");
        $this->sendDiscordMessage("Pilot {$pilot->getNickname()} killed AI {$unit->getName()}",
            $this->server->isSendDiscordCombatNotifications());
        $this->log('Done. Marking pilot flight as combat flight');

        $em->getRepository(Flight::class)->markFlightAsCombat($pilot);
        $this->log('Done. KILL AI event successfully parsed.');

        return [
            'status' => 0,
            'message' => 'Pilot ' . $pilot->getNickname() . ' killed AI ' . $unit->getName()
        ];
    }

    /**
     * @param $data
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function chat(array $data): array
    {
        $this->log('Triggered CHAT event');
        if (!isset($data['message']) && empty($data['message'])) {
            $this->log('CHAT event without message. Exiting...');
            return ['status' => 1, 'message' => 'Chat message is missing'];
        }
        $this->log('Searching pilot...');

        /** @var Pilot $pilot */
        $pilot = $this->getPilot($data['init']);

        if (empty($pilot)) {
            $this->log('Pilot not found ... Exiting...');
            return [
                'status' => 0,
                'message' => 'Pilot not found...'
            ];
        }
        $this->log('Creating ChatMessage Object...');
        $message = new ChatMessage();
        $message->setSender($pilot);
        $message->setMessage($data['message']);
        $message->setCreatedAt(new DateTime($data['time']));
        $message->setServer($this->server);
        $this->log('Done.Saving ChatMessage Object...');
        $this->getManager()->persist($message);
        $this->getManager()->flush();
        $this->log('Done.Sending messages to WS server...');
        $this->sendMessageToChat([
            'username' => $pilot->getNickname(),
            'message' => $message->getMessage(),
            'time' => $message->getCreatedAt()->format('d.m.Y H:i:s')
        ]);
        $this->log('Event CHAT successfully parsed');
        return ['status' => 0, 'message' => 'Chat message saved'];
    }

    /**
     * @param Pilot $pilot
     * @return bool
     */
    public function clearOnline(Pilot $pilot): bool
    {
        $this->log('Clearing online table');
        $this->em->getRepository(Online::class)->clearOnline($pilot);
        $this->log('Clearing done');
        return true;
    }

    /**
     * @param Pilot $pilot
     * @return bool
     */
    public function clearFlights(Pilot $pilot): bool
    {
        $this->log('Clearing flights table for pilot #' . $pilot->getId());
        $this->em->getRepository(Flight::class)->clearFlights($pilot);
        $this->log('Clearing done');
        return true;
    }

    /**
     * @param Pilot $pilot
     * @param Plane $plane
     * @param $side
     * @return Flight|null
     */
    public function createFlight(Pilot $pilot, Plane $plane, $side): ?Flight
    {
        $this->log("Creating flight for pilot #{$pilot->getId()} on plane #{$plane->getId()} for {$side}");
        $em = $this->getManager();
        $this->log('Searching already created flight');
        $flight = $em->getRepository(Flight::class)->findOneBy([
            'pilot' => $pilot,
        ]);
        if (empty($flight)) {
            $this->log('Flight not found. Creating...');
            $flight = new Flight();
        } else {
            $this->log('Flight was already created...');
        }
        $this->log('Filling all data...');
        $flight->setSide($side);
        $flight->setPlane($plane);
        $flight->setPilot($pilot);
        $flight->setTour($this->getCurrentTour());
        $flight->setServer($this->server);
        $flight->setSide($side);
        $flight->setTheatre($this->missionRegistry->getTheatre());
        $flight->setRegisteredMission($this->missionRegistry);
        try {
            $this->log('Persisting all data...');
            $this->em->persist($flight);
            $this->em->flush();
            $this->log('Done...');
            return $flight;
        } catch (ORMException $e) {
            $this->log("Failed to save flight with message: {$e->getMessage()}, File: {$e->getFile()}, Line: {$e->getLine()}");
            return null;
        }
    }


    /**
     * @param $data
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function pilotLeft(array $data): array
    {
        $this->log('Triggered LEFT event');

        /** @var Initiator $initiator */
        $initiator = $this->serializer->deserialize(json_encode($data['init']), Initiator::class, 'json', ['groups' => 'app_event']);

        $em = $this->getManager();
        $this->log('Checking if UCID is present in request');
        if (!empty($initiator->getId())) {
            $this->log('UCID is present in request. Searching pilot by UCID');
            /** @var Pilot $pilot */
            $pilot = $em->getRepository(Pilot::class)->findOneBy([
                'ucid' => $initiator->getId(),
            ]);

            if (empty($pilot)) {
                $this->log('Pilot NOT FOUND by UCID. Searching pilot by nickname from REPO');

                $pilot = $em->getRepository(Pilot::class)->getPilotByNickname($initiator);

                if (empty($pilot)) {
                    $this->log('Pilot NOT FOUND by nickname. Searching pilot in ONLINE list by nickname');
                    $sPilot = $em->getRepository(Pilot::class)->findOneBy(['username' => $initiator->getNick()]);
                    $pilot = $em->getRepository(Online::class)->findOneBy([
                        'server' => $this->server,
                        'pilot' => $sPilot,
                    ]);
                    if ($pilot === null) {
                        $this->log('Pilot NOT FOUND in ONLINE list. Exiting...');
                        return ['status' => 0, 'message' => 'Pilot ' . $data['init']['nick'] . ' not found in DB '];
                    }
                }
            }
        } else {
            $this->log('UCID is not present in request. Searching pilot by nickname from REPO');
            $pilot = $em->getRepository(Pilot::class)->getPilotByNickname($initiator);

            if ($pilot === null) {
                $this->log('Pilot not found by nickname. Exiting...');
                return ['status' => 0, 'message' => 'Pilot ' . $data['init']['nick'] . ' not found in DB '];
            }
            $this->log('Searching pilot in ONLINE list');
            $pilot = $em->getRepository(Online::class)->findOneBy([
                'server' => $this->server,
                'pilot' => $em->getRepository(Pilot::class)->findOneBy(['username' => $initiator->getNick()]),
            ]);

            if ($pilot === null) {
                $this->log('Pilot not found in ONLINE list. Exiting');
                return ['status' => 0, 'message' => 'Pilot ' . $data['init']['nick'] . ' not found in DB '];
            } else {
                $this->log('Pilot found in ONLINE list');
            }
        }

        $missionRegistry = $this->getLastMissionRegistry();
        $tour = $this->getCurrentTour();
        $this->log('Checking current flight');
        $flight = $this->getCurrentFlight($pilot);
        if (!empty($flight)) {
            $this->log('Current flight detected');
            if ($flight->isStarted()) {
                $this->log('Current flight is in progress...Stopping flight...');
                $em->getRepository(Event::class)->addEvent(Event::DISCONNECT, $data['time'], $this->server, $pilot, $flight->getSide(), $flight->getPlane(), $tour, $missionRegistry);
                $em->getRepository(Flight::class)->endFlight($flight, $data['time'], null, SortieRepository::STATUS_DISCONNECT);
                $this->log('Done.');
            } else {
                $this->log('Removing current flight');
                $em->remove($flight);
            }
        }
        $this->log('Setting pilot offline');

        $pilot->setOnline(false);
        $em->persist($pilot);
        $em->flush();
        $this->log('Done. Clearing online');

        $leftExec = $em->getRepository(Online::class)->clearOnline($pilot);
        if ($leftExec) {
            $this->log('Done with success. Sending some notifications...');

            $this->sendNotification('User left server!', 'Pilot ' . $pilot->getCallsign() . ' left server');
            $this->sendDiscordMessage("Pilot `{$pilot->getCallsign()}` left server",
                $this->server->isSendDiscordFlightNotifications());
            $this->sendOnlineTrigger($pilot, 'remove');
            return ['status' => 0, 'message' => 'Pilot ' . $data['init']['nick'] . ' left server '];
        }
        $this->log("Done with error. Online not cleared for pilot {$data['init']['nick']}. Exiting...");
        return ['status' => 1, 'message' => "Pilot {$pilot->getCallsign()} is not cleared from database."];
    }

    public function log($message): bool
    {
        $this->logStack[] = $message;
        return true;
    }

    /**
     * @param $event
     * @param $message
     * @return bool
     */
    public function sendNotification($event, $message): bool
    {
        return true;
//        if ($this->getOption(SettingRepository::MOBILE_NOTIFICATIONS)->isEnabled()) {
//            $this->log('Starting sending notifications.');
//            /** @var NotificationService $notificationService */
//            $notificationService = $this->container->get(NotificationService::class);
//            $notificationService->notifyMobileClients($event, $message);
//            $this->log('Notifications are sent to all registered mobile clients.');
//        }
    }

    /**
     * @param $data
     * @return array
     * @throws ORMException
     */
    public function pilotDied(array $data): array
    {
        $this->log('DIED event triggered');
        $em = $this->getManager();
        $this->log('Searching pilot');
        $pilot = $this->getPilot($data['init']);
        $this->log('Searching plane');
        $plane = $this->getPlane($data['init']['type']);
        if ($pilot === null || $plane === null) {
            $this->log('Pilot ' . $data['init']['nick'] . ' or his plane not found in DB ');
            return [
                'status' => 0,
                'message' => 'Pilot ' . $data['init']['nick'] . ' not found in DB '
            ];
        }
        $this->log('Searching flight');
        /** @var Flight $flight */
        $flight = $this->getCurrentFlight($pilot);
        $side = $data['init']['side'];
        $event = new Event();
        $this->log('Checking current flight');

        if (!empty($flight) && $flight->isStarted()) {
            $em->getRepository(Streak::class)->resetStreaks($pilot, $this->server, true);
            $em->getRepository(Streak::class)->resetStreaks($pilot, $this->server, false);
            $this->log('Active flight found. Setting it as EMERGENCY');
            $flight->setEmergencyFlight(true);
            $event->setPlane($flight->getPlane());
            $this->log('Finishing flight');
            $em->getRepository(Flight::class)->endFlight($flight, $data['time'], null, SortieRepository::STATUS_DEATH);
            $this->log('Flight finished');
            //TODO Add punishment
        } else {
            $this->log('Current flight not found or not started');
        }
        $tour = $this->getCurrentTour();
        $this->log('Adding event DEATH to list');
        $em->getRepository(Event::class)
            ->addEvent(Event::DEATH, $data['time'], $this->server, $pilot, $side, $plane, $tour, $this->missionRegistry);
        $this->log('Done. Sending some notifications...');

        $message = "Pilot `{$pilot->getNickname()}` died";
        $this->sendDiscordMessage($message, $this->server->isSendDiscordFlightNotifications());
        $this->log('DEATH event parsed with success');

        return [
            'status' => 0,
            'message' => $message,
        ];
    }

    /**
     * @param array $data
     * @return array
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function pilotCrashed(array $data): array
    {
        $this->log('CRASH event triggered. Searching data...');
        $em = $this->getManager();
        $tour = $this->getCurrentTour();
        $pilot = $this->getPilot($data['init']);
        $flight = $this->getCurrentFlight($pilot);
        $plane = $this->getPlane($data['init']['type']);
        $this->log('Searching done');

        if ($pilot === null || $plane === null) {
            $this->log("Pilot {$data['init']['nick']} or his plane not found in DB");
            return [
                'status' => 0,
                'message' => "Pilot {$data['init']['nick']} or his plane not found in DB"
            ];
        }
        $this->log('Checking flight');

        if (!empty($flight) && $flight->isStarted()) {
            $this->log('Active flight detected. Setting EMERGENCY flag.');

            $flight->setEmergencyFlight(true);
            $this->log('Finishing flight with CRASH status ...');

            $em->getRepository(Flight::class)
                ->endFlight($flight, $data['time'], null, SortieRepository::STATUS_CRASH);
            $this->log('Done. Flight finished.');

            $em->getRepository(Event::class)
                ->addEvent(Event::CRASH, $data['time'], $this->server, $pilot, $data['init']['side'], $plane, $tour, $this->missionRegistry);
            $this->log('Done');
        } else {
            $this->log('Current flight not found or not started');
        }
        $this->log('Adding event CRASH');

        $em->getRepository(Event::class)
            ->addEvent(Event::CRASH, $data['time'], $this->server, $pilot, $data['init']['side'], $plane, $tour, $this->missionRegistry);
        $this->log('Done');
        $this->log('Sending some notifications');

        $message = "Pilot `{$pilot->getNickname()}` crashed plane {$plane->getName()}";
        $this->sendDiscordMessage($message, $this->server->isSendDiscordFlightNotifications());
        $this->log('CRASH event parsed with success');

        return [
            'status' => 0,
            'message' => $message,
        ];
    }

    /**
     * @param array $data
     * @return array
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function pilotEjected(array $data): array
    {
        $this->log('EJECT event triggered. Searching data...');

        $em = $this->getManager();
        $tour = $this->getCurrentTour();
        $pilot = $this->getPilot($data['init']);
        $flight = $this->getCurrentFlight($pilot);
        $side = $data['init']['side'];
        $plane = $this->getPlane($data['init']['type']);
        $this->log('Search is done');

        if ($pilot === null || $plane === null) {
            $this->log("Pilot {$data['init']['nick']} or his plane not found in DB");
            return [
                'status' => 0,
                'message' => "Pilot {$data['init']['nick']} or his plane not found in DB"
            ];
        }
        $this->log('Checking current flight');

        if (!empty($flights) && $flight->isStarted()) {
            $this->log('Current flight found. Setting it as EMERGENCY');
            $flight->setEmergencyFlight(true);
            $this->log('Finishing current flight');
            $em->getRepository(Flight::class)->endFlight($flight, $data['time'], null, SortieRepository::STATUS_EJECT);
            $this->log('Done. Adding event EJECT');

            $em->getRepository(Event::class)
                ->addEvent(Event::EJECT, $data['time'], $this->server, $pilot, $side, $plane, $tour, $this->missionRegistry);
            $this->log('Done');

        } else {
            $this->log('Current flight not found or not started');
        }
        $this->log('Sending some notifications');

        $message = "Pilot `{$pilot->getNickname()}` ejected from {$plane->getName()}";
        $this->sendDiscordMessage($message, $this->server->isSendDiscordFlightNotifications());
        $this->log('EJECT event parsed with success');

        return [
            'status' => 0,
            'message' => $message,
        ];
    }

    public function won(array $data): array
    {
        $this->log('WON event triggered');

        $mr = $this->missionRegistry;
        $this->log('Checking Mission registry presence...');
        if (empty($mr)) {
            $this->log('Mission registry not found...');
            return [
                'status' => 1,
                'message' => 'Mission registry not found',
            ];
        }
        $this->log('Mission registry found. Checking winner');

        $winner = (!isset($data['won']) || empty($data['won']) || $data['won'] === 'TODO')
            ? MissionRegistryRepository::DRAW : strtoupper($data['won']);
        $this->log('Winner: ' . $winner . ' . Saving data...');
        $mr->setWinner($winner);
        $mr->setFinished(true);
        $mr->setEnd(new DateTime($data['time']));

        try {
            $this->em->persist($mr);
            $this->em->flush();
            $this->log('Saving done. Sending some notifications...');

            $message = "`{$mr->getMission()->getName()}` mission has ended. Winner: `{$winner}`";
            $this->sendDiscordMessage($message, $this->server->isSendDiscordServerNotifications());
            $this->log('Done. WON event parsed with success');

            return [
                'status' => 0,
                'message' => $message
            ];
        } catch (ORMException $e) {
            $this->log("Failed to parse event WON with message: {$e->getMessage()}. File: {$e->getFile()} at line {$e->getLine()}");
            return [
                'status' => 1,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws ORMException
     * @todo Add logging...event is not used yet
     */
    public function parseSrs(array $data): array
    {
        $em = $this->getManager();
        $server = $this->server;
        $radioData = $data['Clients'];
        $online = $em->getRepository(Online::class)->getPilotsForServer($server);
        if (empty($online)) {
            return [
                'status' => 0,
                'message' => 'Online is empty for current server'
            ];
        }

        foreach ($radioData as $srsClient) {
            $callsign = $srsClient['Name'];
            $coordinates = $srsClient['LatLngPosition'];
            $radios = $srsClient['RadioInfo']['radios'] ?? [];
            $currentOnlinePilot = $this->getOnlineForUpdate($online, $callsign);
            if ($currentOnlinePilot === null || $currentOnlinePilot === false) {
                continue;
            }
            $clientRadios = [];
            foreach ($radios as $radio) {
                if (!in_array($radio['modulation'], Parameter::$radioModulationConstants)) {
                    continue;
                }
                $clientRadios[] = [
                    'modulation' => $radio['modulation'],
                    'frequency' => $radio['freq'],
                    'secondFrequency' => $radio['secFreq'],
                ];
            }
            $currentOnlinePilot->setCoordinates(json_encode($coordinates));
            $currentOnlinePilot->setFrequencies(json_encode($clientRadios));
            $em->persist($currentOnlinePilot);
        }

        try {
            $em->flush();
            return [
                'status' => 0,
                'message' => 'SRS data parsed'
            ];
        } catch (ORMException $e) {
            $this->log($e->getMessage());
            return [
                'status' => 0,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * @param array $data
     * @return array
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function serverVersion(array $data): array
    {
        $this->log('Event VERSION triggered.');
        $this->server->setVersion($data['version']);
        $this->log('Saving server version...');

        $this->getManager()->persist($this->server);
        $this->getManager()->flush();
        $this->log('Done. VERSION event parsed with success...');

        return [
            'status' => 0,
            'message' => 'Server version updated',
        ];
    }

    /**
     * @param array $online
     * @param string $callsign
     * @return Online|null
     * @todo add logging - related to SRS event
     */
    public function getOnlineForUpdate(array $online, string $callsign): ?Online
    {
        $results = array_filter($online, static function ($onlineObject) use ($callsign) {
            /** @var $onlineObject Online */
            if (!empty($onlineObject->getPilot()) && $onlineObject->getPilot()->getUsername() === $callsign) {
                return $onlineObject;
            }
        });
        if (!empty($results)) {
            $return = reset($results);
            return $return;
        }
        return null;
    }

    /**
     * @param string $message
     * @param bool $send
     * @return bool
     */
    public function sendDiscordMessage(string $message, bool $send = false): bool
    {
        $discordBotUrl = $this->server->getDiscordWebHook();
        if ($discordBotUrl === null) {
            return true;
        }
        if ($this->server->getSendDiscordNotifications() === false) {
            return true;
        }

        if ($send === false) {
            return true;
        }

        $data = ['content' => $message, 'username' => $this->server->getName()];
        $json = json_encode($data);

        $client = new CurlHttpClient();
        try {
            $client->request('POST', $discordBotUrl, [
                'body' => $json,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Length' => strlen($json),
                ],
            ]);
//            $content = $response->getContent();
            return true;
        } catch (Exception $e) {
            $this->log($e->getMessage());
            return true;
        } catch (TransportExceptionInterface $e) {
            $this->log($e->getMessage());
            return true;
        }
    }

    /**
     * @return bool
     */
    public function loadOptions(): bool
    {
        $em = $this->em;
        $search = $em->getRepository(Setting::class)->findAll();
        /** @var Setting $option */
        foreach ($search as $option) {
            $this->options[$option->getKeyword()] = $option;
        }
        return true;
    }

    /**
     * @param string $keyword
     * @return Setting|null
     */
    public function getOption(string $keyword): ?Setting
    {
        return $this->options[$keyword] ?? null;
    }

    /**
     * @param array $message
     * @return bool
     */
    public function sendMessageToChat(array $message): bool
    {
        try {
            $address = $this->container->get('socketServerHost') . ':'
                . $this->container->get('socketServerPort');
            $client = new Client(new Version2X($this->container->get('secure') . '://' . $address));
            $client->initialize();
            $client->emit('send', [
                'room' => "server/{$this->server->getId()}/chat",
                'message' => json_encode($message)
            ]);
            $client->close();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * @param Pilot $pilot
     * @param $event
     * @return bool
     */
    public function sendOnlineTrigger(Pilot $pilot, string $event): bool
    {
        $data = $this->getManager()
            ->getRepository(Online::class)
            ->getPilotOnlineData($this->server, $this->missionRegistry, $pilot);
        try {
            $address = $this->container->get('socketServerHost') . ':'
                . $this->container->get('socketServerPort');
            $client = new Client(new Version2X($this->container->get('secure') . '://' . $address));
            $client->initialize();
            $client->emit('send', [
                'room' => "server/{$this->server->getId()}/online",
                'message' => json_encode([
                    'event' => $event,
                    'member' => empty($data) ? [
                        'id' => $pilot->getId(),
                        'ucid' => $pilot->getUcid(),
                        'callsign' => $pilot->getCallsign()
                    ] : $data,
                ])
            ]);
            $client->close();
            return true;
        } catch (Exception $e) {
            $this->log($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function sendEventToNotifications(array $event): bool
    {
        try {
            $address = $this->container->get('socketServerHost') . ':'
                . $this->container->get('socketServerPort');
            $client = new Client(new Version2X($this->container->get('secure') . '://' . $address));
            $client->initialize();
            $client->emit('send', [
                'room' => "server/{$this->server->getId()}/notifications",
                'message' => json_encode($event)
            ]);
            $client->close();
            return true;
        } catch (Exception $e) {
            $this->log('Failed to send message to websockets server: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Doctrine\DBAL\Exception
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function insertObjects(array $data): array
    {
        $mapUnitsOption = $this->getOption(SettingRepository::SETTING_IGNORE_UNITS_EVENT);
        if (!empty($mapUnitsOption) && $mapUnitsOption->isEnabled()) {
            return [
                'status' => 0,
                'message' => 'Units are not saved. Skipping...',
            ];
        }
        $objects = $data['objects'];
        $this->log('Received units stack: ' . count($objects) . ' objects found.');
        $saved = 0;
        $units = [];
        $em = $this->getManager();
        $em->getConnection()->executeQuery('DELETE FROM ' . $em->getClassMetadata(MapUnit::class)
                ->getTableName() . ' WHERE server_id = ' . $this->server->getId());

        foreach ($objects as $object) {
            $unit = new MapUnit();
            $unit->setIdentifier($object['id']);
            $unit->setTitle($object['title']);
            $unit->setCountry($object['country']);
            $unit->setSide($object['side']);
            $unit->setLatitude($object['latitude']);
            $unit->setLongitude($object['longitude']);
            $unit->setAltitude($object['altitude']);
            $unit->setHeading(rad2deg(floatval($object['heading'])));
            $unit->setType($object['type']);
            $unit->setIsHuman($object['isHuman'] ?? false);
            $unit->setIsStatic($object['isStatic'] ?? false);
            $unit->setServer($this->server);
            $em->persist($unit);
            $units[] = $unit;
        }
        $em->flush();
        if ($this->server->isShowMap() && $this->server->isOnline()) {
//            $this->updateMapUnits($units); TODO Sockets server is not SET UP
        }
        $this->log("Saved {$saved} objects");
        return [
            'status' => 0,
            'message' => 'Units are saved'
        ];
    }

    /**
     * @param string $json
     * @return bool
     * @deprecated
     */
    private function saveJson(string $json): bool
    {
        $em = $this->getManager();
        $option = $this->getOption(SettingRepository::SETTING_PARSER_SAVE_JSONS);
        if (!empty($option) && $option->isEnabled()) {
            $jsonMsg = new JsonMessage();
            $jsonMsg->setContent($json);
            $jsonMsg->setDeprecated(true);
            $jsonMsg->setSuccess(true);
            $jsonMsg->setExecuteTime(0);
            $jsonMsg->setExecuted(true);
            $jsonMsg->setServer($this->server);
            try {
                $em->persist($jsonMsg);
                $em->flush();
                return true;
            } catch (OptimisticLockException | ORMException $e) {
                $this->log($e->getMessage());
                return false;
            }
        }
        return false;
    }

    public function isValidJson($json): bool
    {
        if (!is_string($json)) {
            $this->log('JSON Request body is, probably, not a string');
            return false;
        }

        $data = json_decode($json, true);

        if (empty($data)) {
            $this->log('Invalid JSON received: ' . $json);
            return false;
        }

        if (!isset($data['event'])) {
            $this->log('Event field is missing in JSON: ' . $json);
            return false;
        }
        if (!isset($data['server'])) {
            $this->log('Server data is missing in JSON: ' . $json);
            return false;
        }

        if (!isset($data['time'])) {
            $this->log('Event time is missing. Exiting');
            return false;
        }

        return true;
    }

    public function updateMapUnits($units)
    {
        $event = [
            'event' => 'mapUnits',
            'units' => $this->serializer->normalize($units, 'json', ['groups' => 'api_open_servers']),
        ];
        return $this->sendEventToNotifications($event);
    }

    /**
     * @param MissionRegistry $missionRecord
     * @param null $weather
     * @return MissionRegistry
     */
    private function setWeather(MissionRegistry $missionRecord, $weather = null): MissionRegistry
    {
        if (empty($weather)) {
            return $missionRecord;
        }

        $missionRecord->setAtmosphereType($weather['atmosphere_type']);
        $missionRecord->setGroundTurbulence($weather['groundTurbulence']);
        $missionRecord->setFog($weather['enable_fog']);

        $missionRecord->setWindSpeedAt8000($weather['wind']['at8000']['speed']);
        $missionRecord->setWindDirectionAt8000($weather['wind']['at8000']['dir']);

        $missionRecord->setWindSpeedAtGround($weather['wind']['atGround']['speed']);
        $missionRecord->setWindDirectionAtGround($weather['wind']['atGround']['dir']);

        $missionRecord->setWindSpeedAt2000($weather['wind']['at2000']['speed']);
        $missionRecord->setWindDirectionAt2000($weather['wind']['at2000']['dir']);

        $missionRecord->setTemperature($weather['season']['temperature'] ?? null);
        $missionRecord->setWeatherType($weather['type_weather']);
        $missionRecord->setQnh($weather['qnh']);
        $missionRecord->setWeatherName($weather['name']);
        $missionRecord->setFogThickness($weather['fog']['thickness']);
        $missionRecord->setFogVisibility($weather['fog']['visibility']);
        $missionRecord->setVisibilityDistance($weather['visibility']['distance']);

        $missionRecord->setDustDensity($weather['dust_density']);
        $missionRecord->setDust($weather['enable_dust']);

        $missionRecord->setCloudsThickness($weather['clouds']['thickness']);
        $missionRecord->setCloudsDensity($weather['clouds']['density']);
        $missionRecord->setCloudsBase($weather['clouds']['base']);
        $missionRecord->setCloudsIpRecptns($weather['clouds']['iprecptns']);

        return $missionRecord;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    private function saveLog($result)
    {
        $data = json_decode($this->json, true);
        $log = new Log();
        $status = $result['status'] ?? 1;
        $log->setSuccess($status === 0);

        $log->setMessagesStack($this->logStack);
        $log->setTour($this->tour);
        $log->setServer($this->server);

        $log->setEventTime(new DateTime($data['time']));
        $log->setEvent($data['event']);
        $log->setChatMessage($data['message'] ?? null);
        $log->setTheatre($data['theatre'] ?? null);
        $log->setField($data['field']['name'] ?? null);
        $log->setWeather($data['weather'] ?? []);
        $log->setDescription($data['description'] ?? null);
        $log->setBanlist($data['banlist'] ?? []);
        $log->setWon($data['won'] ?? null);

        $log->setInitiatorNickname($data['init']['nick'] ?? null);
        $log->setInitiatorIpAddress($data['init']['ip'] ?? null);
        $log->setInitiatorUcid($data['init']['id'] ?? null);
        $log->setInitiatorEmail($data['init']['email'] ?? null);
        $log->setInitiatorSide($data['init']['side'] ?? null);
        $log->setInitiatorType($data['init']['type'] ?? null);
        $log->setInitiatorCategory($data['init']['cat'] ?? null);
        $log->setScore($data['init']['score'] ?? null);

        $log->setTargetNickname($data['targ']['nick'] ?? null);
        $log->setTargetIpAddress($data['targ']['ip'] ?? null);
        $log->setTargetUcid($data['targ']['id'] ?? null);
        $log->setTargetSide($data['targ']['side'] ?? null);
        $log->setTargetType($data['targ']['type'] ?? null);
        $log->setTargetCategory($data['targ']['cat'] ?? null);
        $log->setTargetIsHuman($data['targ']['hum'] ?? false);
        $log->setTargetIsGround($data['targ']['gr'] ?? false);

        $manager = $this->getManager();
        $manager->persist($log);
        $manager->flush();
    }

    /**
     * @param array $data
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function closeCurrentMissionRegistry(array $data): void
    {
        $this->log('Closing current MR in closeCurrentMissionRegistry function');
        $em = $this->getManager();
        $this->missionRegistry->setFinished(true);
        $this->missionRegistry->setEnd(new DateTime($data['time']));
        $this->missionRegistry->setWinner(MissionRegistryRepository::DRAW);
        $this->log('Saving info');
        $em->persist($this->missionRegistry);
        $em->flush();
        $this->log('Done.');
    }

    protected function reset()
    {
        $this->server = null;
        $this->tour = null;
        $this->missionRegistry = null;
        $this->json = '';
        $this->logStack = [];
    }

    /**
     * @return Tournament|null
     */
    private function getCurrentTournament(): ?Tournament
    {
        $tournaments = $this->getManager()->getRepository(Tournament::class)->findBy(['finished' => false]);
        foreach ($tournaments as $tournament) {
            if ($tournament->getServers()->contains($this->server)) {
                return $tournament;
            }
        }
        return null;
    }

    /**
     * @param array $data
     * @return array
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    private function giveCoupon(array $data): array
    {
        $pilot = $this->getPilot($data['init']);
        if (empty($pilot)) {
            $this->log('Pilot not found');
            return [
                'status' => 1,
                'message' => sprintf('Pilot %s not found', $data['init']['nick'] ?? ''),
            ];
        }
        $email = $data['init']['email'] ?? '';
        if (!Helper::isValidEmail($email)) {
            $this->log('Invalid email address given');
            return [
                'status' => 1,
                'message' => sprintf('Invalid email given: %s', $email)
            ];
        }
        $tournament = $this->server->getCurrentTournament();
        if (empty($tournament)) {
            $this->log('Tournament not found');
            return [
                'status' => 1,
                'message' => 'Tournament not found'
            ];
        }

        if (!$tournament->getProvideCoupons()) {
            $this->log('Tournament is not providing coupons');

            return [
                'status' => 1,
                'message' => 'Tournament is not providing coupons'
            ];
        }

        $oldCoupon = $this->getManager()->getRepository(TournamentCoupon::class)->findOneBy([
            'tournament' => $tournament,
            'pilot' => $pilot,
        ]);

        if (!empty($oldCoupon)) {
            $this->log(sprintf('Pilot %s already received coupon', $pilot->getCallsign()));

            return [
                'status' => 1,
                'message' => sprintf('Pilot %s already received coupon', $pilot->getCallsign())
            ];
        }

        try {
            if (!$pilot->isRegistered()) {
                $this->log('Pilot is not registered in the system. Saving his potential email');
                $pilot->setEmail($email);
                $this->getManager()->persist($pilot);
            }
            $coupon = $this->getManager()->getRepository(TournamentCoupon::class)->findOneBy([
                'pilot' => null,
                'email' => null,
                'tournament' => $tournament,
            ]);
            if (empty($coupon)) {
                // TODO Notify user
                return [
                    'status' => 0,
                    'message' => 'There is no any unused coupons'
                ];
            }
            $coupon->setEmail($email);
            $coupon->setPilot($pilot);
            $coupon->setActive(false);
            $coupon->setTransferTime(new DateTime());
            $this->getManager()->persist($coupon);
            $this->getManager()->flush();
            $this->log('Coupon request created');
            // TODO Send email to USER
            return [
                'status' => 0,
                'message' => 'Coupon request created'
            ];
        } catch (Exception $e) {
            $this->log($e->getTraceAsString());
            return [
                'status' => 1,
                'message' => 'Failed to create coupon'
            ];
        }
    }

    /**
     * @param array $data
     * @return array
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])]
    private function processEmail(array $data): array
    {
        $pilot = $this->getPilot($data['init']);
        if (empty($pilot)) {
            $this->log('Pilot not found');
            return [
                'status' => 1,
                'message' => sprintf('Pilot %s not found', $data['init']['nick'] ?? ''),
            ];
        }
        if (!empty($pilot->getEmail())) {
            $this->log('Pilot already has email');
            return [
                'status' => 1,
                'message' => sprintf('Pilot %s already has email', $data['init']['nick'] ?? ''),
            ];
        }
        $email = $data['init']['email'] ?? '';
        if (!Helper::isValidEmail($email)) {
            $this->log('Invalid email address given');
            return [
                'status' => 1,
                'message' => sprintf('Invalid email given: %s', $email)
            ];
        }
        $pilot->setEmail($email);
        $pilot->setEnabled(true);
        try {
            $this->getManager()->persist($pilot);
            $this->getManager()->flush();
            $this->log('Pilot email saved');
            // Send invite email to pilot
            $this->sendEmailToPilot($pilot);
            return [
                'status' => 0,
                'message' => 'Pilot email saved'
            ];
        } catch (Exception $e) {
            $this->log($e->getMessage());
            $this->log($e->getTraceAsString());
            return [
                'status' => 1,
                'message' => 'Failed to create coupon'
            ];
        }
    }

    /**
     * @param Pilot $pilot
     * @return bool
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function sendEmailToPilot(Pilot $pilot): bool
    {
        $tournament = $this->getCurrentTournament();
        if (empty($tournament)) {
            $this->log('Tounrnament not found during sending invite email in processEmail function');
            return false;
        }
        /** @var Environment $templating */
        $email = new EmailMessage();
        $templating = $this->serviceContainer->get('twig');
        $template = 'emails/vpc/invite-en.html.twig';
        if ($pilot->getIpCountry() === 'ru' || $pilot->getIpCountry() === 'ua') {
            $template = 'emails/vpc/invite-ru.html.twig';
        }
        $body = $templating->render($template, [
            'username' => $pilot->getUsername(),
            'ucid' => $pilot->getUcid(),
            'tournamentTitle' => $tournament->getTitleEn(),
            'tournamentId' => $tournament->getId(),
        ]);
        $email->setIsHtml(true);
        $email->setRecipients([$pilot->getEmail()]);
        $email->setBody($body);
        $email->setSubject(Parameter::EMAIL_PREFIX) . ' ' . $tournament->getTitleEn();
        $this->messageBus->dispatch($email);
        $this->log('Invitation email sent to ' . $pilot->getEmail());
        return true;
    }
}

