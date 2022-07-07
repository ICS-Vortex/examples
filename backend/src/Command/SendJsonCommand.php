<?php

namespace App\Command;

use App\Message\Json;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

class SendJsonCommand extends Command
{
    protected static $defaultName = 'app:send-json';
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        parent::__construct();
        $this->bus = $bus;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->bus->dispatch(new Json('{0}'));
        $this->bus->dispatch(new Json('{1}'));
        $this->bus->dispatch(new Json('{2}'));
        $this->bus->dispatch(new Json('{3}'));
        $this->bus->dispatch(new Json('{4}'));

        $io->success('Success');

        return 0;
    }
}
