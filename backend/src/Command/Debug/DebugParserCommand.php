<?php

namespace App\Command\Debug;

use App\Entity\Server;
use App\Service\ParserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class DebugParserCommand extends Command
{
    protected static $defaultName = 'app:debug-parser';
    /**
     * @var ParserService
     */
    private ParserService $parserService;
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(ParserService $parserService, ParameterBagInterface $parameterBag, EntityManagerInterface $manager)
    {
        parent::__construct();
        $this->parserService = $parserService;
        $this->parameterBag = $parameterBag;
        $this->manager = $manager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Listens folder for new files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $directory = 'C:\Users\Vortex\Saved Games\DCS.openbeta\Scripts\Hooks\Reports';
        $io = new SymfonyStyle($input, $output);
        $identifier = '37a3596b-0f88-4806-8e68-c81a1c99b9d1';
        $finder = new Finder();
        while (true) {
            $filesystem = new Filesystem();
            $finder->files()->in($directory)->sortByName(true);
            if (!$finder->hasResults()) {
                unset($filesystem);
                sleep(10);
                continue;
            }
            $io->success("Found {$finder->count()} files");
            foreach ($finder as $file) {
                $extension = $file->getExtension();
                if ($extension !== 'json') {
                    continue;
                }
                $absoluteFilePath = $file->getRealPath();
                if(empty($absoluteFilePath)) {
                    continue;
                }
                $content = file_get_contents($absoluteFilePath);
                $result = [];
                try {
                    $data = json_decode($content, true);
                    $data['server']['identifier'] = $identifier;
                    $result = $this->parserService->parse(json_encode($data));
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
            unset($filesystem);
            $io->text('Waiting new files...');
        }
        return Command::SUCCESS;
    }
}
