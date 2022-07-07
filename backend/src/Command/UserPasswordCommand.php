<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserPasswordCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('afs:user:password')
            ->setDescription('Creates user')
            ->addOption('login', null, InputOption::VALUE_OPTIONAL, 'User\'s login or email')
            ->addOption('password', null, InputOption::VALUE_OPTIONAL, 'User\'s surname')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $login = trim($input->getOption('login'));
        if(empty($login)){
            $login = $io->ask('User\'s login or email', '', function ($login) {
                if (empty($login)) {
                    throw new \RuntimeException('Login or email cannot be empty.');
                }

                return $login;
            });
        }

        $password = trim($input->getOption('password'));
        if(empty($password)) {
            $password = $io->askHidden('New user\'s password:', function ($password) {
                if (empty($password)) {
                    throw new \RuntimeException('Password cannot be empty.');
                }
                if (strlen($password) < 6) {
                    throw new \RuntimeException('Password has to be longer than 5 characters.');
                }

                return $password;
            });
        }

        $manager = $this->getContainer()->get('doctrine')->getManager();
        $repo = $manager->getRepository('MainCoreBundle:DcsPilots');
        $user = $repo->findOneByNickname($login);
        if(!empty($user)){
            $encoder = $this->getContainer()->get('security.encoder_factory')->getEncoder($user);
            $password = $encoder->encodePassword($password, $user->getSalt());
            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();

            $output->writeln('User has been saved.');
        }else{
            $output->writeln('User doesn\'t exist.');
        }

    }
}
