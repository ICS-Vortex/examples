<?php

namespace App\Command\Check;

use App\Entity\RegistrationTicket;
use App\Message\EmailMessage;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CheckTicketsCommand extends Command
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    /** @var ParameterBagInterface $container */
    private ParameterBagInterface $container;

    /** @var Environment */
    private Environment $twig;
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $bus;

    private SymfonyStyle $io;

    public function __construct(ParameterBagInterface $container, EntityManagerInterface $entityManager, MessageBusInterface $bus, Environment $twig)
    {
        parent::__construct();
        $this->container = $container;
        $this->em = $entityManager;
        $this->twig = $twig;
        $this->bus = $bus;
    }

    protected function configure()
    {
        $this
            ->setName('app:check-tickets')
            ->setDescription('Checks approved registration tickets')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $em = $this->em;
        $io = $this->io = new SymfonyStyle($input, $output);
        $sent = 0;
        $tickets = $em->getRepository(RegistrationTicket::class)->findBy([
            'issued' => false
        ]);
        $io->text("Found ".count($tickets) . ' tickets');
        /** @var RegistrationTicket $ticket */
        foreach ($tickets as $ticket) {
            // TODO Add alerts for admins
            try {
                if ($ticket->isOutdated()) {
                    $ticket->setIssued(true);
                    $em->persist($ticket);
                    $io->error("Ticket #{$ticket->getId()} is outdated");
                    continue;
                }
                $sending = (int) $this->checkRegistration($ticket);
                $sent += $sending;
                if ($sending) {
                    $io->success("Ticket #{$ticket->getId()} issued");
                    $ticket->setIssued(true);
                    $em->persist($ticket);
                } else {
                    $io->error("Failed to send registration email for ticket #{$ticket->getId()}");
                }
            } catch (Exception $e) {
                $io->error($e->getMessage());
                continue;
            }
        }

        $em->flush();
        $io->success("{$sent} emails sent");
        return Command::SUCCESS;
    }

    /**
     * @param RegistrationTicket $ticket
     * @return bool
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function checkRegistration(RegistrationTicket $ticket) :bool
    {
        $em = $this->em;

        $pilot = $ticket->getPilot();
        $token = $ticket->getToken();
        if(!$pilot->isRegistered()){
            $body = $this->twig->render('emails/registration/confirm.html.twig', ['token' => $token]);

            try{
                $email = new EmailMessage();
                $email->setBody($body);
                $email->setSubject("Registration at {$this->container->get('frontendHost')}");
                $email->setIsHtml(true);
                $email->setRecipients([$ticket->getEmail()]);
                $this->bus->dispatch($email);

                $em->persist($pilot);
                $em->flush();
                return true;
            }catch (Exception $e) {
                //TODO Add logging
                $this->io->error($e->getMessage());
                $this->io->comment($e->getTrace());
                return false;
            }
        }

        return false;
    }
}
