<?php

namespace App\Service;

use App\Entity\Setting;
use Doctrine\ORM\EntityManager;

class SettingsService
{
    private $em;
    private $options;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->loadOptions();
    }

    private function loadOptions() {
        $this->options = $this->em->getRepository(Setting::class)->findAll();
    }

    /**
     * @param $optionKey
     * @return Setting|null
     */
    public function getOption($optionKey) {
        if (empty($optionKey)) {
            return null;
        }

        $optionSearch = array_filter($this->options, function ($option) use ($optionKey) {
            /** @var Setting $option */
            if($optionKey === $option->getKeyword()) {
                return $option;
            }
        });
        if (!empty($optionSearch)) {
            /** @var Setting $option */
            $option = reset($optionSearch);
            return $option;
        }

        return null;
    }
}