<?php

namespace App\Command\Send\Report;

use App\Constant\Parameter;
use App\Entity\Server;
use App\Entity\Setting;
use App\Entity\Visitor;
use App\Message\EmailMessage;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SendVisitorsReportCommand extends Command
{
    protected static $defaultName = 'app:send-visitors-report';
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $bus;

    public function __construct(EntityManagerInterface $manager, ParameterBagInterface $parameterBag, MessageBusInterface $bus)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->parameterBag = $parameterBag;
        $this->bus = $bus;
    }

    protected function configure()
    {
        $this
            ->setDescription('Sends daily visitors report');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $servers = $this->manager->getRepository(Server::class)->findBy(['active' => true]);
        $spreadsheet = new Spreadsheet();

        foreach ($servers as $key => $server) {
            $data = $this->manager->getRepository(Visitor::class)->getTodayVisitors($server);
            $sheet = $spreadsheet->createSheet($key);
            $sheet->setCellValue("A1", 'ID');
            $sheet->setCellValue("B1", 'Username');
            $sheet->setCellValue("C1", 'UCID');
            $sheet->setCellValue("D1", 'Address');
            $sheet->setCellValue("E1", 'Country');
            $sheet->setCellValue("F1", 'Time');
            $sheet->setTitle(substr($server->getName(), 0, 31));
            foreach ($data as $index => $row) {
                $cell = $index += 2;
                $sheet->setCellValueExplicit("A{$cell}", $row['id'], DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("B{$cell}", str_replace('%', '', $row['username']), DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("C{$cell}", $row['ucid'], DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("D{$cell}", $row['ip_address'], DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("E{$cell}", strtoupper($row['country']), DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("F{$cell}", $row['visit_time'], DataType::TYPE_STRING);
            }
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'visitors_report_' . date('Y_m_d') . '.xlsx';
        $path = $this->parameterBag->get('kernel.project_dir') . '/public/uploads/reports';
        $fullPath = "{$path}/{$filename}";
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
        $recipientsOption = $this->manager->getRepository(Setting::class)->findOneBy([
            'keyword' => SettingRepository::SETTING_REPORTS_RECEIVERS
        ]);

        $recipients = Parameter::REPORT_EMAILS;
        if (!empty($recipientsOption)) {
            $recipients = explode('|', $recipientsOption->getValue());
        }

        $writer->save($fullPath);
        $email = new EmailMessage();
        $email->setRecipients($recipients);
        $email->addAttachment([
            'name' => $filename,
            'path' => $fullPath,
            'mime' => Parameter::MIME_TYPE_XLSX,
        ]);
        $subject = Parameter::EMAIL_PREFIX . " Daily visits report from {$this->parameterBag->get('frontendHost')}";
        $email->setSubject($subject);
        $email->setBody('Check attachments');
        $this->bus->dispatch($email);

        $io->success('Report successfully added to queue.');
        return Command::SUCCESS;
    }
}
