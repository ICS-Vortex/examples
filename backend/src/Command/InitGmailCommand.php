<?php

namespace App\Command;

use App\Service\MailingService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InitGmailCommand extends Command
{
    protected static $defaultName = 'app:init-gmail';
    /**
     * @var MailingService
     */
    private MailingService $mailingService;

    protected function configure()
    {
        $this
            ->setDescription('Gets GMAIL token')
        ;
    }

    public function __construct(MailingService $mailingService)
    {
        parent::__construct();
        $this->mailingService = $mailingService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try{
            $this->mailingService->initClient();
            return Command::SUCCESS;
        }catch (\Google_Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
