<?php

namespace App\Command\Dump;

use App\Entity\JsonMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class DumpJsonCommand extends Command
{
    protected static $defaultName = 'app:dump-json';

    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    /** @var ParameterBagInterface $container */
    private ParameterBagInterface $container;

    public function __construct(ParameterBagInterface $container, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->container = $container;
        $this->em = $entityManager;
    }

    protected function configure() : void
    {
        $this
            ->setDescription('Makes a dump of JSON messages')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $em = $this->em;
        $container = $this->container;
        $filesystem = new Filesystem();

        $messages = $em->getRepository(JsonMessage::class)->findAll();
        $io->text('Found '.count($messages).' messages...');
        $filename = 'dump_'.date('Y_m_d_H_i_s').'.json' ;
        $path = $container->get('kernel.project_dir'). '/public/uploads/dumps/' . $filename;
        $io->text("Writing messages into '{$path}' file.");
        foreach ($messages as $message) {
             $filesystem->appendToFile($path, $message->getContent()."\n");
        }
        $io->success('Dump operation finished. File '.$filename. ' created in `uploads/dumps` folder');
    }
}
