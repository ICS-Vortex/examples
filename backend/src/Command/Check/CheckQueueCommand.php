<?php

namespace App\Command\Check;

use App\Entity\CommandQueue;
use App\Entity\Log;
use App\Repository\LogRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckQueueCommand extends Command
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    const COMMAND_NAME = 'app:check-queue';

    const WARNING = 'WARNING';
    const INFO = 'INFO';
    const ERROR = 'ERROR';
    const DB_ERROR = 'DB_ERROR';
    const DEBUG = 'DEBUG';
    const RECORD = 'RECORD';

    /** @var EntityManager */
    private $em;

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Checks commands queue')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $commands = $this->em->getRepository(CommandQueue::class)->findBy(array(
            'executed' => false,
        ), array('id' => 'ASC'));

        $io->title('Found '.count($commands).' commands in queue');
        if(empty($commands)){
            return Command::SUCCESS;
        }
        /** @var CommandQueue $command */
        foreach ($commands as $command){
            $arguments = [];
            $io->info("Executing command {$command->getCommandName()}");
            $command->setExecuteStartTime(new DateTime());
            $arguments['--id']= $command->getIdentifierValue();
            $cmd = $this->getApplication()->find($command->getCommandName());
            $commandInput = new ArrayInput($arguments);

            try {
                $exitCode = $cmd->run($commandInput, $output);
                switch ($exitCode) {
                    case 0:
                        $io->success('Command executed with success');
                        $command->setFailed(false);
                        break;
                    default:
                        $io->error('Execution failed!');
                        $command->setFailed(true);
                }
                $command->setExecuted(true);
                $command->setExecuteEndTime(new DateTime());
                $this->em->persist($command);
                $this->em->flush();
                $io->info("Execution done");
            } catch (ExceptionInterface | Exception $e) {
                $io->error($e->getMessage());
                $io->warning($e->getTrace());
                return Command::FAILURE;
            }
        }
        return Command::SUCCESS;
    }
}
