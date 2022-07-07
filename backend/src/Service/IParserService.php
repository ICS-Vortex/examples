<?php

namespace App\Service;

use App\Entity\Airfield;
use App\Entity\Flight;
use App\Entity\Mission;
use App\Entity\MissionRegistry;
use App\Entity\Model\Target;
use App\Entity\Online;
use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\Server;
use App\Entity\Setting;
use App\Entity\Unit;
use App\Service\Google\GoogleSheetsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

interface IParserService
{
    public function __construct(
        EntityManagerInterface $entityManager,
        ParameterBagInterface  $container,
        SerializerInterface    $serializer,
        GoogleSheetsService    $sheetsService,
        MessageBusInterface    $messageBus,
        ContainerInterface     $serviceContainer
    );

    public function parse(string $json): array;

    public function updateLastActivity(Server $server): bool;

    public function parseData(string $event, array $data): array;

    public function eventExists(string $event): bool;

    public function serverOnline(array $data): array;

    public function saveRace(array $data): array;

    public function getMission(array $data): Mission;

    public function clearServerData(Server $server): bool;

    public function getServer(string $identifier): ?Server;

    public function getLastMissionRegistry(): ?MissionRegistry;

    public function getPilot(array $data, bool $target = false): ?Pilot;

    public function pilotEnter(array $data): array;

    public function pilotJoined(array $data): array;

    public function pilotJoinedRed(array $data): array;

    public function pilotJoinedBlue(array $data): array;

    public function getCurrentFlight(Pilot $pilot): ?Flight;

    public function getPlane(string $plane): ?Plane;

    public function pilotTakeoff(array $data): array;

    public function getAirfield(string $airfield): ?Airfield;

    public function pilotLanded(array $data): array;

    public function pilotKill(array $data): array;

    public function pilotDestroyedGroundTarget(array $data) : array;

    public function getUnit(Target $target) : Unit;

    public function pilotKilledHuman(array $data): array;

    public function pilotKilledAi(array $data): array;

    public function pilotJoinedSpectators(array $data): array;

    public function chat(array $data): array;

    public function clearOnline(Pilot $pilot): bool;

    public function pilotLeft(array $data): array;

    public function log($message): bool;

    public function sendNotification($event, $message): bool;

    public function pilotDied(array $data): array;

    public function pilotCrashed(array $data): array;

    public function pilotEjected(array $data): array;

    public function won(array $data): array;

    public function parseSrs(array $data) : array;

    public function serverVersion(array $data): array;

    public function getOnlineForUpdate(array $online, string $callsign) : ?Online;

    public function sendDiscordMessage(string $message, bool $send = false): bool;

    public function loadOptions(): bool;

    public function getOption(string $keyword) : ?Setting;

    public function sendMessageToChat(array $message): bool;

    public function sendOnlineTrigger(Pilot $pilot, string $event) : bool;

    public function insertObjects(array $data) : array;

    public function sendEventToNotifications(array $event): bool;

}
