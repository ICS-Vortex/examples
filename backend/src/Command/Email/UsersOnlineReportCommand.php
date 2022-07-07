<?php

namespace App\Command\Email;

use App\Entity\Log;
use App\Entity\Mission;
use App\Entity\MissionRegistry;
use App\Entity\Online;
use App\Entity\Setting;
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
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;

class UsersOnlineReportCommand extends Command
{
    const COMMAND_NAME = 'app:users-online-report';
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
            ->setDescription('Sends users online report');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->em;
        $this->mailer->initClient();
        $settings = $this->em->getRepository(Setting::class)->getSettings();
        if (empty($settings)) {
            $this->log('Settings not found...Failed to send Visitors report.', LogRepository::TYPE_ERROR);
            $output->writeln("<error>Settings are unavailable.</error>");
            return;
        }

        /** @var Setting $recipientsSetting */
        $recipientsSetting = $settings[SettingRepository::SETTING_EMAILS_RECIPIENTS];

        /** @var Setting $reportPrefixSetting */
        $reportPrefixSetting = $settings[SettingRepository::SETTING_REPORTS_EMAILS_PREFIX];

        /** @var $mission Mission */
        $mission = null;

        /** @var $missionRegistry MissionRegistry */
        $missionRegistry = $em->getRepository(MissionRegistry::class)->findOneBy(array(
            'finished' => false,
        ), array(
            'id' => 'DESC'
        ));
        if (!empty($missionRegistry)) {
            $mission = $missionRegistry->getMission();
        }
        $addresses = array_filter(explode('|', $recipientsSetting->getValue()));
        $online = $em->getRepository(Online::class)->getOnline();
        $message = (new Swift_Message)
            ->setSubject($reportPrefixSetting->getValue() . ' Users online report from Burning Skies (DCS World)')
            ->setFrom('burningskieswwii@gmail.com')
            ->setTo($addresses)
            ->setBody($this->twig->render(
                'emails/users_online.html.twig',
                array('online' => $online, 'mission' => $mission)
            ),
                'text/html');
        $date = date('d.m.Y H:i:s');
        try {
            $this->mailer->send($message);
            $this->log('Online report sent.');
            $output->writeln("<info>{$date} - Online report sent.</info>");
        } catch (Exception $e) {
            $output->writeln("<error>{$date} - Failed to send Online report.</error>");
            $output->writeln("<error>{$e->getMessage()}</error>");
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
        $log->setInitiator(LogRepository::INITIATOR_COMMAND);
        $log->setEvent(LogRepository::EVENT_SENDING_REPORT);
        $log->setMessage($message);

        $em->persist($log);
        $em->flush();
    }
}
