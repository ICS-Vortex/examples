<?php

namespace App\Command;

use App\Constant\Parameter;
use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Monolog\Logger;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class LogsReportCommand extends Command
{
    protected static $defaultName = 'app:logs-report';
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parametersBag;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /** @var SymfonyStyle $io */
    private $io;

    public function __construct(ParameterBagInterface $parametersBag, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->parametersBag = $parametersBag;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Sends errors reports log')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');
        $io = $this->io = new SymfonyStyle($input, $output);
        $logFile = $this->parametersBag->get('kernel.project_dir') . '/app.log';
        if (!$filesystem->exists($logFile)) {
            $filesystem->touch($logFile);
            $io->warning('File app.log does not exists');
            return 0;
        }
        $handle = fopen($logFile, 'rb');
        if ($handle) {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator('VFP Team')
                ->setLastModifiedBy('VFP Team')
                ->setTitle('Log errors')
                ->setSubject('Log report for ' . date('Y-m-d'))
                ->setDescription(
                    'This document contains information about all critical messages in logs'
                )
                ->setKeywords('logs errors messages vfpteam')
                ->setCategory('Error reports');

            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Log report for ' . date('Y-m-d'));
            $row = 9;
            $warning = 0;
            $error = 0;
            $alert = 0;
            $critical = 0;
            $emergency = 0;

            while (($line = fgets($handle)) !== false) {
                if (preg_match('/(DEBUG|INFO)/i', $line)) {
                    continue;
                }
                $lineArray = explode(' ', $line);
                $time = date('Y-m-d H:i:s', strtotime(preg_replace('~(\[|\])~', '', $lineArray[0])));
                if (!$this->isTodayLog($time, $start, $end)) {
                    continue;
                }
                $type = $this->getLogType($line);
                switch ($type) {
                    case 'WARNING':
                        $warning++;
                        break;
                    case 'ERROR':
                        $error++;
                        break;
                    case 'ALERT':
                        $alert++;
                        break;
                    case 'CRITICAL':
                        $critical++;
                        break;
                    case 'EMERGENCY':
                        $emergency++;
                        break;
                }
                $message = str_replace("\n", '', $this->getLogMessage($line));
                $sheet->setCellValue("A{$row}", $time);
                $sheet->getStyle("A{$row}")->applyFromArray(Parameter::$style['table']);

                $sheet->setCellValue("B{$row}", $type);
                $sheet->getStyle("B{$row}")->applyFromArray(Parameter::$style['table']);

                $sheet->setCellValue("C{$row}", $message);
                $sheet->getStyle("C{$row}")->applyFromArray(Parameter::$style['table']);

                $row++;
            }
            fclose($handle);
            $this->em->getRepository(Log::class)->log('Generating report file', Logger::INFO, 'command');

            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getStyle('A1:B1')->applyFromArray(Parameter::$style['warning']);
            $sheet->setCellValue('A1', 'WARNING messages');
            $sheet->setCellValue('B1', $warning);

            $sheet->getStyle('A2:B2')->applyFromArray(Parameter::$style['error']);
            $sheet->setCellValue('A2', 'ERROR messages');
            $sheet->setCellValue('B2', $error);

            $sheet->getStyle('A3:B3')->applyFromArray(Parameter::$style['alert']);
            $sheet->setCellValue('A3', 'ALERT messages');
            $sheet->setCellValue('B3', $alert);

            $sheet->getStyle('A4:B4')->applyFromArray(Parameter::$style['critical']);
            $sheet->setCellValue('A4', 'CRITICAL messages');
            $sheet->setCellValue('B4', $critical);

            $sheet->getStyle('A5:B5')->applyFromArray(Parameter::$style['emergency']);
            $sheet->setCellValue('A5', 'EMERGENCY messages');
            $sheet->setCellValue('B5', $emergency);


            $writer = new Xlsx($spreadsheet);
            $publicDirectory = $this->parametersBag
                    ->get('kernel.project_dir') . '/public/uploads/reports';
            $file = 'logs_report_' . date('Y_m_d_H_i_s') . '.xlsx';
            $this->em->getRepository(Log::class)->log('Report file: ' . $file, Logger::INFO, 'command');

            $excelFilepath = $publicDirectory . '/' . $file;
            try {
                $writer->save($excelFilepath);
                $io->text('File ' . $file . ' successfully generated');
                $sendEmail = $this->sendReport($excelFilepath);
                unlink($excelFilepath);
                if ($sendEmail) {
                    unlink($logFile);
                    $filesystem->touch($logFile);
                    $filesystem->chmod($logFile, 0777);
                    $io->success('Email sent');
                    return 0;
                }
                $this->em->getRepository(Log::class)->log('Failed to send report', Logger::ERROR, 'command');
                $io->error('Failed to send report');
                return 1;
            } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
                $this->em->getRepository(Log::class)->log($e->getMessage(), Logger::ALERT, 'command');
                $io->error('Failed to send report: ' . $e->getMessage());
                return 1;
            }

        }

        $io->error('Failed to read file app.log');
        return 1;
    }

    public function getStringBetween($str)
    {
        preg_match("/[a-zA-Z]\:(.*?)\[\]/", $str, $matches);
        return $matches[1];
    }

    public function getLogType($line)
    {
        preg_match('/(ERROR|WARNING|CRITICAL|ALERT|EMERGENCY)/', $line, $matches);
        return $matches[1];
    }

    public function getLogMessage($line)
    {
        $line = preg_replace('/\[(.*)(ERROR|WARNING|CRITICAL|ALERT|EMERGENCY)\:\ /', '', $line);
        $line = str_replace(' [] []', '', $line);
        return $line;
    }

    public function isTodayLog($time, $start, $end): bool
    {
        return $time >= $start && $time <= $end;
    }

    public function sendReport($reportPath): bool
    {
        $email = new PHPMailer(true);
        $email->isSMTP();
        $email->SMTPAuth = true;
        $email->isHTML(true);
        $email->Username = '';
        $email->Password = '';
        $email->Subject = Parameter::EMAIL_PREFIX . 'Log errors report for ' . date('d.m.Y');
        $email->Body = 'Log errors report for ' . date('d.m.Y');
        $email->SMTPSecure = Parameter::GMAIL_PROTOCOL;
        $email->Host = '';
        $email->Port = '';
        try {
            $email->setFrom('no-reply@' . $this->parametersBag->get('mainHost'));
            $email->addAddress('vasyl@starsam.net');
            $email->addAddress('brandon@apexitnw.com');
            $email->addAttachment($reportPath);
            return $email->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $this->io->warning($e->getMessage());
            $this->em->getRepository(Log::class)->log($e->getMessage(), Logger::ALERT, 'command');
            return false;
        } catch (Exception $e) {
            $this->io->warning($e->getMessage());
            $this->em->getRepository(Log::class)->log($e->getMessage(), Logger::ALERT, 'command');
            return false;
        }
    }
}
