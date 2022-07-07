<?php

namespace App\Service;

use App\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;

class FeaturedVideoService
{
    /** @var EntityManagerInterface $em */
    private $em;
    private $videos;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->videos = $entityManager->getRepository(Setting::class)->findAll();
    }

    public function getVideo($setting)
    {
        if (empty($this->videos)) {
            return 'https://www.youtube.com/embed/live_stream?channel=UCgJRhtnqA-67pKmQ3A2GsgA';
        }

        /** @var Setting $video */
        foreach ($this->videos as $video) {
            if ($video->getKeyword() === $setting) {
                return $video->getValue();
            }
        }

        return 'https://www.youtube.com/embed/live_stream?channel=UCgJRhtnqA-67pKmQ3A2GsgA';
    }
}