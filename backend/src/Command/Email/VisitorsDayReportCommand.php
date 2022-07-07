<?php

namespace App\Command\Email;

use App\Entity\Log;
use App\Entity\Setting;
use App\Entity\Visitor;
use App\Repository\LogRepository;
use App\Repository\SettingRepository;
use App\Service\ContainerService;
use App\Service\MailingService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;

class VisitorsDayReportCommand extends Command
{
    const COMMAND_NAME = 'app:visitors-daily-report';
    const WARNING = 'WARNING';
    const INFO = 'INFO';
    const ERROR = 'ERROR';
    const DB_ERROR = 'DB_ERROR';
    const DEBUG = 'DEBUG';
    const RECORD = 'RECORD';
    /** @var EntityManager */
    private $em;
    /**
     * @var MailingService
     */
    private MailingService $mailer;
    /**
     * @var Environment
     */
    private Environment $twig;

    public function __construct(EntityManagerInterface $em, MailingService $mailer, Environment $twig)
    {
        parent::__construct();
        $this->em = $em;
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Sends users online report')
            ->addOption(
                'date',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_OPTIONAL,
                'Date, which is used to generate report for.',
                null
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $settings = $this->em->getRepository(Setting::class)->getSettings();
        $date = $input->getOption('date');
        if (empty($settings)) {
            $this->log('Settings not found...Failed to send Visitors report.', LogRepository::TYPE_ERROR);
            $output->writeln("<error>Settings are unavailable.</error>");
            return;
        }

        /** @var Setting $recipientsSetting */
        $recipientsSetting = $settings[SettingRepository::SETTING_EMAILS_RECIPIENTS];

        /** @var Setting $reportPrefixSetting */
        $reportPrefixSetting = $settings[SettingRepository::SETTING_REPORTS_EMAILS_PREFIX];
        $addresses = array_filter(explode('|', $recipientsSetting->getValue()));
        $reportPrefix = $reportPrefixSetting->getValue();

        /** @var $mailer Swift_Mailer */
        $mailer = $this->mailer;
        $em = $this->em;
        $visitors = $em->getRepository(Visitor::class)->getTodayVisitors($date);
        $message = new Swift_Message();
        $message
            ->setSubject($reportPrefix . ' Daily visitors report from Burning Skies (DCS World)')
            ->setFrom('burningskieswwii@gmail.com')
            ->setTo($addresses)
            ->setBody($this->twig->render(
                'emails/visitors_daily.html.twig',
                array('visitors' => $visitors)
            ),
                'text/html'
            );
        try {
            $date = date('d.m.Y H:i:s');
            $mailer->send($message);
            $this->log('Visitors daily report sent.');
            $output->writeln("<info>{$date} - Visitors daily report sent.</info>");
        } catch (Exception $e) {
            $output->writeln("<error>{$date} - Failed to send daily report. Error: {$e->getMessage()}</error>");
            try {
                $this->log($e->getMessage(), LogRepository::TYPE_ERROR);
            } catch (OptimisticLockException $e) {
                $output->writeln("<error>{$date} - {$e->getMessage()}</error>");
            }
        }
    }

    /**
     * @param $message
     * @param string $type
     * @throws OptimisticLockException
     * @throws ORMException
     */
    private function log($message, $type = LogRepository::TYPE_OK)
    {
        $em = $this->em;

        $log = new Log();
        $log->setType($type);
        $log->setMessage($message);
        $log->setEvent(LogRepository::EVENT_SENDING_REPORT);
        $log->setInitiator(LogRepository::INITIATOR_COMMAND);
        $em->persist($log);
        $em->flush();
    }
}
