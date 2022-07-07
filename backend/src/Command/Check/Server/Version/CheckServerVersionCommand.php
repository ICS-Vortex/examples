<?php


namespace App\Command\Check\Server\Version;


use App\Constant\Parameter;
use App\Entity\Server;
use App\Helper\Helper;
use App\Message\EmailMessage;
use App\Service\ED\Versions\EDVersionsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CheckServerVersionCommand extends Command
{
    protected static $defaultName = 'app:check-server-version';

    private EntityManagerInterface $em;
    private EDVersionsService $EDVersionsService;
    private ParameterBagInterface $parameterBag;
    private MessageBusInterface $bus;

    public function __construct(EntityManagerInterface $em, EDVersionsService $EDVersionsService, ParameterBagInterface $parameterBag, MessageBusInterface $bus)
    {
        parent::__construct(self::$defaultName);
        $this->em = $em;
        $this->EDVersionsService = $EDVersionsService;
        $this->parameterBag = $parameterBag;
        $this->bus = $bus;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Checks servers versions from ED website.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $latestBeta = $this->EDVersionsService->getLatestBetaVersion();
        $latestStable = $this->EDVersionsService->getLatestStableVersion();

        $io = new SymfonyStyle($input, $output);
        $servers = $this->em->getRepository(Server::class)->findBy([
            'active' => true,
            'sendVersionUpdateEmails' => true,
        ]);
        $io->text('Found ' . count($servers) . ' servers');
        /** @var Server $server */
        foreach ($servers as $server) {
            $latestVersion = $server->isBeta() ? $latestBeta : $latestStable;
            if (empty($server->getVersion())) continue;
            if (version_compare($server->getVersion(), $latestVersion, '<')) {
                // Send email
                if ($server->getEmail() && Helper::isValidEmail($server->getEmail())) {
                    $email = new EmailMessage();
                    $email->setRecipients([$server->getEmail()]);
                    $email->setSubject(Parameter::EMAIL_PREFIX . " Attention!!! New DCS World update available!");
                    $email->setBody("New updated released (DCS v.{$latestVersion}). Please, update '{$server->getName()}' server!!!");
                    $this->bus->dispatch($email);

                    $io->text("Email notification about new DCS World update for server '{$server->getName()}' successfully added to queue");
                }
            }
        }
        $io->success('Operation done');
        return Command::SUCCESS;
    }

}