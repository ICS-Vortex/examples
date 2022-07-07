<?php

namespace App\Command\Statistics;

use App\Entity\JsonMessage;
use App\Entity\Log;
use App\Repository\LogRepository;
use App\Service\ParserService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParseCommand extends Command
{
    public function __construct(EntityManagerInterface $em, ParserService $parserService, ParameterBagInterface $container)
    {
        parent::__construct();
        $this->container = $container;
        $this->em = $em;
        $this->parserService = $parserService;
    }

    public const COMMAND_NAME = 'app:statistics-parse';
    public const WARNING = 'WARNING';
    public const INFO = 'INFO';
    public const ERROR = 'ERROR';
    public const DB_ERROR = 'DB_ERROR';
    public const DEBUG = 'DEBUG';
    public const RECORD = 'RECORD';

    /** @var EntityManager */
    private $em;

    /** @var ParameterBagInterface */
    private ParameterBagInterface $container;

    /** @var ParserService */
    private ParserService $parserService;

    protected function configure() : void
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Parses statistics')
            ->addOption(
                'debug', null,
                InputOption::VALUE_OPTIONAL,
                'Sets debug mode and deletes lock file',
                false
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $debug = (bool) $input->getOption('debug');
        if ($this->processStillRunning()) {
            $io->error('Parser still running');
            if ($debug === true) {
                $this->stopProcess();
            } else {
                return;
            }
        }

        $this->startProcess();
        $options = ['executed' => false];
        if ($debug) {
            $options = [];
        }
        $messages = $this->em->getRepository(JsonMessage::class)->findBy($options, array('id' => 'ASC'), 200);

        $io->title('Found ' . count($messages) . ' messages');
        if (empty($messages)) {
            $this->stopProcess();
            $io->text('No messages found to run parsing');
            return;
        }
        /** @var JsonMessage $message */
        foreach ($messages as $message) {
            $output->writeln("<info>Executing message {$message->getId()}</info>");
            $start = microtime(true);

            $json = $message->getContent();
            try{
                $process = $this->parserService->parse($json);
            }catch (ORMException $e) {
                $io->error($e->getMessage());
                $io->warning('File: '.$e->getFile());
                $io->warning('Line: '.$e->getLine());
                $this->stopProcess();
                return;
            }
            $executeTime = microtime(true) - $start;
            $message->setExecuteTime($executeTime);
            $message->setExecuted(true);
            if ($process['status'] === 1) {
                $message->setSuccess(false);
                $io->error($process['message']);
            } else {
                $message->setSuccess(true);
                $io->success($process['message']);
            }
            $this->log('Json parsed. Message: ' . $process['message']);
            $this->em->persist($message);
            $this->em->flush();
            $io->warning("Execution time: {$executeTime}");
        }

        $this->stopProcess();
    }

    /**
     * @param $message
     * @param string $type
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function log($message, $type = LogRepository::TYPE_OK) : bool
    {
        $em = $this->em;

        $log = new Log();
        $log->setType($type);
        $log->setMessage($message);
        $log->setEvent(LogRepository::EVENT_PARSING);
        $log->setInitiator(LogRepository::INITIATOR_COMMAND);
        $em->persist($log);
        $em->flush();
        return true;
    }

    private function processStillRunning() : bool
    {
        $cnt = $this->container;
        $runFile = $cnt->get('kernel.project_dir') . '/parser.lock';
        if (file_exists($runFile)) {
            return true;
        }

        return false;
    }

    private function startProcess() : bool
    {
        $cnt = $this->container;
        $runFile = $cnt->get('kernel.project_dir') . '/parser.lock';
        if (!file_exists($runFile)) {
            $file = fopen($runFile, 'wb');
            fclose($file);
        }
        return true;
    }

    private function stopProcess() : bool
    {
        $cnt = $this->container;
        $runFile = $cnt->get('kernel.project_dir') . '/parser.lock';
        if (file_exists($runFile)) {
            unlink($runFile);
        }
        return true;
    }
}
