<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class CommandQueueRepository extends EntityRepository
{
    const CALCULATE_ELO = 'app:calculate-elo';
    const RECALCULATE_ELO = 'app:recalculate-elo';
    const RESET_ELO = 'app:reset-elo';

    public static $commands = [
        self::CALCULATE_ELO,
    ];
}
