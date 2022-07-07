<?php

namespace App\Repository;

use App\Entity\Pilot;
use App\Entity\RaceRun;
use App\Entity\TournamentStage;

class TournamentStageRepository extends BaseRepository
{
    public const STAGE_QUALIFICATION = 'qualification';
    public function getPilotInfo(TournamentStage $stage, Pilot $pilot)
    {
        $data = $this->getEntityManager()->getRepository(RaceRun::class)->getBestTiming($stage->getTournament(), $stage, null);
        $ranking = 1;
        $result = null;
        foreach ($data as $row) {
            if ($pilot->getId() === intval($row['id'])) {
                $row['ranking'] = $ranking;
                $row['time'] = (double)$row['time'];
                $result = $row;
                break;
            }
            $ranking++;
        }
        $bestArray = $this->getEntityManager()->getRepository(RaceRun::class)->getBestTiming(null, null, 1);
        $bestAll = $this->getEntityManager()->getRepository(RaceRun::class)->getBestTiming(null, null, null);
        if (!empty($bestArray) && !empty($result)) {
            $best = $bestArray[0];
            $result['bestEver'] = (double) $best['time'];
            foreach ($bestAll as $row) {
                if ($pilot->getId() === intval($row['id'])) {
                    $result['best'] = (double)$row['time'];
                }
            }
        }
        $qualificationStage = null;
        /** @var TournamentStage $item */
        foreach ($stage->getTournament()->getStages() as $item) {
            if ($item->getCode() === 'qualification') {
                $qualificationStage = $item;
                break;
            }
        }
        if (!empty($qualificationStage)) {
            $bestQualificationArray = $this->getEntityManager()->getRepository(RaceRun::class)
                ->getBestTiming($stage->getTournament(), $qualificationStage, null);
            if (!empty($bestQualificationArray)) {
                foreach ($bestQualificationArray as $row) {
                    if ($pilot->getId() === intval($row['id'])) {
                        $result['bestQualification'] = (double) $row['time'];
                    }
                }
            }
        }

        return $result;
    }
}
