<?php

namespace App\Service;

use App\Entity\Pilot;
use App\Entity\Reward;
use App\Entity\Setting;
use App\Entity\Tour;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;

class RewardsService
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param Pilot $pilot
     * @param Tour $tour
     * @return array
     */
    public function getRewards(Pilot $pilot, Tour $tour)
    {
        $rewards = $this->em->getRepository(Reward::class)->findAll();
        $result = null;
        /** @var Reward $reward */
        foreach ($rewards as $reward) {
            $function = trim($reward->getFunc());
            if ($this->$function($pilot, $tour) === true) {
                if ($reward->isLifetimeReward()) {
                    $result['lifetime'][] = $reward;
                } else {
                    $result['default'][] = $reward;
                }
            }
        }

        return $result;
    }

    /**
     * Reward for Best Streak more or equal 10
     * @param Pilot $pilot
     * @param Tour $tour
     * @return bool
     */
    private function rewardForBestAirStreakTen(Pilot $pilot, Tour $tour)
    {
        return $pilot->getBestAirStreak() >= 10;
    }

    /**
     * Reward for air wins in tour if count is more or equal 10
     * @param Pilot $pilot
     * @param Tour $tour
     * @return bool
     */
    private function rewardForTourAirWins(Pilot $pilot, Tour $tour)
    {
        return $pilot->getAirWinsInTour($tour) >= 10;
    }

    private function lifetimeRewardOne(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_1_USERS);
    }

    private function checkUserRewardSetting(Pilot $pilot, $keyword)
    {
        /** @var Setting $rewardSetting */
        $rewardSetting = $this->em->getRepository(Setting::class)->findOneBy([
            'keyword' => $keyword,
        ]);
        if (empty($rewardSetting)) {
            return false;
        }

        $users = explode('|', $rewardSetting->getValue());
        if (!in_array($pilot->getId(), $users)) {
            return false;
        }

        return true;
    }

    private function lifetimeRewardTwo(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_2_USERS);
    }

    private function lifetimeRewardThree(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_3_USERS);
    }

    private function lifetimeRewardFour(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_4_USERS);
    }

    private function lifetimeRewardFive(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_5_USERS);
    }

    private function lifetimeRewardSix(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_6_USERS);
    }

    private function lifetimeRewardSeven(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_7_USERS);
    }

    private function lifetimeRewardEight(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_8_USERS);
    }

    private function lifetimeRewardNine(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_9_USERS);
    }

    private function lifetimeRewardTen(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_10_USERS);
    }

    private function lifetimeRewardEleven(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_11_USERS);
    }

    private function lifetimeRewardTwelve(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_12_USERS);
    }

    private function lifetimeRewardThirteen(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_13_USERS);
    }

    private function lifetimeRewardFourteen(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_14_USERS);
    }

    private function lifetimeRewardFifteen(Pilot $pilot, Tour $tour)
    {
        return $this->checkUserRewardSetting($pilot, SettingRepository::SETTING_LIFETIME_REWARD_15_USERS);
    }

}