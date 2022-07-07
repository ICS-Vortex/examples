<?php

namespace App\Command;

use App\Entity\Server;
use App\Service\ParserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ParseReportsCommand extends Command
{
    protected static $defaultName = 'app:parse-reports';
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $container;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var ParserService
     */
    private ParserService $parser;

    public function __construct(ParameterBagInterface $container, EntityManagerInterface $em, ParserService $parser)
    {
        parent::__construct();
        $this->container = $container;
        $this->em = $em;
        $this->parser = $parser;
    }

    protected function configure()
    {
        $this
            ->setDescription('Parses files from Reports folder')
            ->addOption(
                'identifier',
                null,
                InputOption::VALUE_REQUIRED,
                'Server identifier, which is used to find server instance',
                'empty'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $identifier = (string) $input->getOption('identifier');
        if ($identifier === 'empty') {
            $io->error('Please, provide server identifier');
            return 1;
        }
        $server = $this->em->getRepository(Server::class)->findOneBy(['identifier' => $identifier]);
        if (empty($server)) {
            $io->error("Server not found! Given identifier '{$identifier}' is invalid.");
            return 1;
        }
        $finder = new Finder();
        $filesystem = new Filesystem();
        $directory = $this->container->get('kernel.project_dir') . '/reports';
        $io->writeln('Reading directory ' . $directory);
        $finder->files()->in($directory)->sortByName(true);
        if (!$finder->hasResults()) {
            $io->warning("No reports found...Exiting...");
            return 0;
        }
        $io->success("Found {$finder->count()} files");
        foreach ($finder as $file) {
            $absoluteFilePath = $file->getRealPath();
            $content = file_get_contents($absoluteFilePath);
            $result = [];
            try {
                $data = json_decode($content, true);
                $data['server']['identifier'] = $identifier;
                $result = $this->parser->parse(json_encode($data));
            } catch (OptimisticLockException $e) {
                $io->error($e->getMessage());
            } catch (ORMException $e) {
                $io->error($e->getMessage());
            }
            if (empty($result)) {
                continue;
            }
            if ($result['status'] === 1) {
                $io->warning($result['message']);
            } else {
                $io->success($result['message']);
            }
            $filesystem->remove($absoluteFilePath);
        }
        return 0;
    }
}
