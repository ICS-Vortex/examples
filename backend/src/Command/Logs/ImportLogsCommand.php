<?php

namespace App\Command\Logs;

use App\Constant\Parameter;
use App\Entity\Log;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class ImportLogsCommand extends Command
{
    protected static $defaultName = 'app:import-logs';
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $manager)
    {
        parent::__construct();
        $this->parameterBag = $parameterBag;
        $this->manager = $manager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import daily logs from ' . Parameter::LOG_FILE . ' into database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filesystem = new Filesystem();
        $logFile = $this->parameterBag->get('kernel.project_dir') . '/' . Parameter::LOG_FILE;
        if (!$filesystem->exists($logFile)) {
            $io->caution('File not found: ' . $logFile);
            return 0;
        }
        $fo = fopen($logFile, 'r');
        if (!$fo) {
            $io->error('Failed to open file '.$logFile);
            return 1;
        }
        $lines = 0;
        $io->section("Reading log file...");
        while ($line = fgets($fo)) {
            $log = new Log();
            if (preg_match('/Log file created/i', $line)) {
                $io->warning('Skipping line #'. ($lines + 1). ": {$line}");
                continue;
            }
            $log->setType($this->getTypeFromTypeString($this->getTypeFromLine($line)));
            $log->setInitiator($this->getInitiatorFromTypeString($this->getTypeFromLine($line)));
            $log->setMessage($this->getContentFromLine($line));
            $log->setCreatedAt(new DateTime($this->getDateFromLine($line)));
            $this->manager->persist($log);
            $lines++;
        }

        fclose($fo);
        if ($lines > 0) {
            $this->manager->flush();
            $io->writeln("Imported {$lines} lines");
            try{
                $filesystem->remove($logFile);
                $filesystem->touch($logFile);
                $io->success('Import done.');
                return Command::SUCCESS;
            }catch (IOException $e) {
                $io->error($e->getMessage());
                return Command::FAILURE;
            }
        }
        $io->caution('Nothing to import. Exiting...');
        return Command::SUCCESS;
    }

    protected function getDateFromLine(string $line): string
    {
        return date('Y-m-d H:i:s', strtotime(substr($line, 1, 32)));
    }

    protected function getTypeFromLine(string $line): string
    {
        $array = explode(' ', $line);
        return substr($array[1], 0, -1);
    }

    protected function getInitiatorFromTypeString(string $type) : string {
        $array = explode('.', $type);
        return $array[1];
    }

    protected function getTypeFromTypeString(string $type) : string {
        $array = explode('.', $type);
        return $array[0];
    }

    private function getContentFromLine(string $line) : string
    {
        $array = explode(' ', $line);
        unset($array[0]);
        unset($array[1]);
        return implode(' ', $array);
    }


}
