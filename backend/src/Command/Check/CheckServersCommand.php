<?php

namespace App\Command\Check;

use App\Constant\Parameter;
use App\Entity\MissionRegistry;
use App\Entity\Server;
use App\Message\EmailMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CheckServersCommand extends Command
{
    protected static $defaultName = 'app:check-servers';

    private EntityManagerInterface $em;
    private MessageBusInterface $bus;

    protected function configure() : void
    {
        $this
            ->setDescription('Checking servers status and last activity. If mroe than 1 hour - shuts down server');
    }

    public function __construct(EntityManagerInterface $em, MessageBusInterface $bus)
    {
        parent::__construct();
        $this->em = $em;
        $this->bus = $bus;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $servers = $this->em->getRepository(Server::class)->findBy([
            'active' => true,
        ]);

        $io->text(count($servers).' servers detected...');
        /** @var Server $server */
        foreach ($servers as $server) {
            $io->title('Checking server '.$server);
            $lastActivity = $server->getLastActivity();
            if ($lastActivity === null) {
                continue;
            }
            $difference = (int) $server->getLastActivityInHours();
            $io->text("Last action: {$difference} hours ago");
            if ($difference > 1 || !$server->isOnline()) {
                $io->text('Shutting down server ...');
                $server->setIsOnline(false);
                $this->em->getRepository(Server::class)->clearTemporaryData($server);
                $this->em->persist($server);

                /** Send email to server admin */
                if (!empty($server->getEmail())) {
                    $io->text('Sending email ...');
                    $email = new EmailMessage();
                    $email->setRecipients([$server->getEmail()]);
                    $email->setSubject(Parameter::EMAIL_PREFIX . " [ALERT] Server {$server->getName()} offline!!!");
                    $email->setBody(sprintf('Server %s does not send data more than %d hours. Please, check it immediately', $server->getName(), $difference));
                    $this->bus->dispatch($email);
                    $io->text('Email sent ...');
                }
            }
        }
        $this->em->flush();
        $io->text('Exiting.');

        return 0;
    }
}
