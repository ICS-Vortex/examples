<?php

namespace App\Command\Delete;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeletePilotCommand extends Command
{
    protected static $defaultName = 'app:delete-pilot';

    protected function configure()
    {
        $this
            ->setDescription('Removes all data about some pilot')
            ->addOption(
                'id', null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_REQUIRED,
                'Identifier of the Dogfight record',
                null
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //TODO Finish command
        $io = new SymfonyStyle($input, $output);
        $id = $input->getOption('id');
        $helper = $this->getHelper('question');
        $question = new Question('Are you sure you want to delete user?', 'no');
        $answer = $helper->ask($input, $output, $question);
        dump($answer);
        if ($answer !== 'yes') {
            return Command::SUCCESS;
        }
        $io->success("User with #{$id} deleted!");

        return Command::SUCCESS;
    }
}
