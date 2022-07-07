<?php

namespace App\Command;

use App\Entity\Plane;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class ValidateIconsCommand extends Command
{
    protected static $defaultName = 'app:validate-icons';
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parametersBag;
    private EntityManagerInterface $em;

    public function __construct(ParameterBagInterface $parametersBag, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->parametersBag = $parametersBag;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Checks what icons namings are wrong or what icons are missing');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filesystem = new Filesystem();

        $iconsFolder = $this->parametersBag->get('kernel.project_dir') . '/public/images/planes/';
        $planes = $this->em->getRepository(Plane::class)->findAll([]);
        /** @var Plane $plane */
        foreach ($planes as $plane) {
            $icons = [
                strtolower($plane->getName()) . '.png',
                strtolower($plane->getName()) . '_r.png',
                strtolower($plane->getName()) . '_b.png'
            ];

            foreach ($icons as $icon) {
                if (!$filesystem->exists($iconsFolder . $icon)) {
                    $io->text("{$icon} icon is missing ");
                }
            }
        }
        return 0;
    }
}
