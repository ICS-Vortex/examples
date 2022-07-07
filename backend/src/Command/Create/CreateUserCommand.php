<?php

namespace App\Command\Create;

use App\Constant\Parameter;
use App\Entity\Pilot;
use App\Message\EmailMessage;
use App\Repository\BaseUserRepository;
use App\Service\MailingService;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateUserCommand extends Command
{
    private UserPasswordHasherInterface $encoder;
    private EntityManagerInterface $em;
    private SymfonyStyle $io;
    private Environment $twig;
    private MailingService $mailer;
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $bus;
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    public function __construct(
        UserPasswordHasherInterface $encoder,
        ParameterBagInterface       $parameterBag,
        EntityManagerInterface      $em,
        MessageBusInterface         $bus,
        Environment                 $twig
    )
    {
        parent::__construct();
        $this->encoder = $encoder;
        $this->em = $em;
        $this->twig = $twig;
        $this->bus = $bus;
        $this->parameterBag = $parameterBag;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:create-user')
            ->setDescription('Creates user');
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->io = new SymfonyStyle($input, $output);
        $defaultAccount = 'account_' . date('dmYHis');
        $helper = $this->getHelper('question');

        $questionRole = new Question('Please enter user Role [Pilot, User, Admin, Root]: ', 'Pilot');
        $roleAnswer = $helper->ask($input, $output, $questionRole);
        if (empty($roleAnswer)) {
            $io->error('Please, provide correct a user role!');
            return Command::FAILURE;
        }
        $role = $this->getRole($roleAnswer);
        if (!in_array($role, BaseUserRepository::$roles)) {
            $io->error('Invalid role: ' . $role);
            return Command::FAILURE;
        }

        $questionEmail = new Question('Please enter valid email: ', null);
        $email = $helper->ask($input, $output, $questionEmail);
        if (empty($email)) {
            $io->warning('Email can not be empty');
            return Command::FAILURE;
        }
        $emailIsValid = $this->em->getRepository(Pilot::class)->isValidEmail($email);
        if (!$emailIsValid) {
            $io->warning('Invalid e-mail address');
            return Command::FAILURE;
        }

        $questionUsername = new Question('Please enter username: ', $defaultAccount);
        $username = $helper->ask($input, $output, $questionUsername);
        if (empty($username)) {
            $io->warning('Username can not be empty');
            return Command::FAILURE;
        }

        if ($this->userAlreadyExists($email, $role)) {
            $io->warning('User already exists in database');
            return Command::FAILURE;
        }

        $plainPassword = sha1(random_bytes(12));
        $account = new Pilot();
        $account->setEnabled(true);
        $account->setUsername(strtolower($roleAnswer) . '_' . $username);
        $account->setEmail($email);
        $account->addRole($role);
        $account->setUcid(md5(date('Y-m-d H:i:s')));
        $account->setChecked(true);
        $account->setPlainPassword($plainPassword);
        $encoder = $this->encoder;
        $account->setPassword($encoder->hashPassword($account, $plainPassword));

        try {
            $this->em->getConnection()->beginTransaction();
            $this->em->persist($account);
            if ($this->emailUser($account)) {
                $this->em->flush();
                $this->em->getConnection()->commit();
                $io->success("Account created. Login: {$account->getUsername()}, password: {$plainPassword}");

                return Command::SUCCESS;
            }

            $this->em->getConnection()->rollback();
            $io->warning('Failed to send email');
            return Command::FAILURE;
        } catch (Exception $e) {
            try {
                $this->em->getConnection()->rollback();
                $io->warning("Failed to  create account with warning: {$e->getMessage()}");
                return Command::FAILURE;
            } catch (ConnectionException $e) {
                $io->warning("Failed to  rollback transaction with warning: {$e->getMessage()}");
                return Command::FAILURE;
            }
        }
    }

    /**
     * @param $account
     * @return bool
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function emailUser($account) : bool
    {
        $email = new EmailMessage();
        $email->setRecipients([$account->getEmail()]);

        $body = $this->twig->render('emails/new_account.html.twig', [
            'login' => $account->getUsername(),
            'password' => $account->getPlainPassword()
        ]);
        $subject = Parameter::EMAIL_PREFIX . " New account at {$this->parameterBag->get('frontendHost')}";
        $email->setSubject($subject);
        $email->setBody($body);
        $email->setIsHtml(true);
        $this->bus->dispatch($email);
        return true;
    }

    private function getRole($input)
    {
        $input = strtolower($input);
        return match ($input) {
            'admin' => BaseUserRepository::ROLE_ADMIN,
            'root' => BaseUserRepository::ROLE_ROOT,
            'user' => BaseUserRepository::ROLE_USER,
            default => BaseUserRepository::ROLE_PILOT,
        };
    }

    private function userAlreadyExists(bool $email, string $role): bool
    {
        $searchClass = null;
        $search = $this->em->getRepository(Pilot::class)->findOneBy([
            'email' => $email
        ]);
        if (!empty($search)) {
            return true;
        }

        return false;
    }
}
