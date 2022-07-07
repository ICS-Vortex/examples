<?php

namespace App\Command;

use App\Entity\Setting;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateOptionsCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:update-options')
            ->setDescription('Updates options')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /** @var $manager EntityManager */
        $manager = $this->em;
        $io->info('Initiated options update');
        $alreadySavedOptions = $manager->getRepository('App:Setting')->findAll();
        $options = SettingRepository::$options;
        $addedOptions = 0;

        foreach ($options as $option) {
            $keyword = $option['key'];
            $io->info("Checking option '{$keyword}':");
            $search = array_filter($alreadySavedOptions, function ($opt) use ($keyword){
                /** @var $optionObject Setting */
                $optionObject = $opt;
                if ($optionObject->getKeyword() == $keyword) {
                    return $optionObject;
                }
            });

            if(empty($search)) {
                $setting = new Setting();
                $setting->setName($option['name']);
                $setting->setDescription($option['description']);
                $setting->setKeyword($option['key']);
                $setting->setValue($option['value']);
                $setting->setDefaultValue($option['default_value']);

                try{
                    $manager->persist($setting);
                    $manager->flush();
                    $io->text("Added option '{$setting->getKeyword()}'");
                    $addedOptions++;
                }catch (OptimisticLockException | ORMException $e){
                    $io->error("Error detected: {$e->getMessage()}");
                    continue;
                }
            } else {
                /** @var $foundOption Setting */
                $foundOption = reset($search);
                if ($option['deleted'] ?? false) {
                    $io->text('Deleting option : ' . $foundOption->getKeyword());
                    try {
                        $manager->remove($foundOption);
                        $manager->flush();
                        $io->text('Option deleted. Continue ... ');
                        continue;
                    } catch (OptimisticLockException | ORMException $e) {
                        $io->error("Error occurred during option deletion: {$e->getMessage()}");
                        continue;
                    }
                }
                if ($foundOption->getDefaultValue() != $option['default_value']) {
                    $io->text("Updating option '{$option['key']}...'");
                    $foundOption->setDefaultValue($option['default_value']);
                    try {
                        $manager->persist($foundOption);
                        $manager->flush();
                        $io->text("Default value for option '{$option['key']}' updated");
                    } catch (OptimisticLockException | ORMException $e) {
                        $io->error("Error detected: {$e->getMessage()}");
                        continue;
                    }
                }
            }
        }

        $io->success("Done. Added {$addedOptions} new options");

        return Command::SUCCESS;
    }
}
