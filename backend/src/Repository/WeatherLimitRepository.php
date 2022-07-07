<?php

namespace App\Repository;

class WeatherLimitRepository extends BaseRepository
{
    public static array $limits = [
        [
            'title' => 'Fighters, attackers, VSTOL',
            'cloudsBaseDay' => 400,
            'cloudsBaseNight' => 500,
            'visibilityDay' => 3000,
            'visibilityNight' => 4000,
        ],
        [
            'title' => 'Long range bombers, reconnaissance planes',
            'cloudsBaseDay' => 300,
            'cloudsBaseNight' => 350,
            'visibilityDay' => 3000,
            'visibilityNight' => 4000,
        ],
        [
            'title' => 'Long range jet splanes',
            'cloudsBaseDay' => 400,
            'cloudsBaseNight' => 450,
            'visibilityDay' => 4000,
            'visibilityNight' => 5000,
        ],
        [
            'title' => 'Training/Transport planes',
            'cloudsBaseDay' => 250,
            'cloudsBaseNight' => 300,
            'visibilityDay' => 2500,
            'visibilityNight' => 3000,
        ],
        [
            'title' => 'Helicopters/PistonEngine planes',
            'cloudsBaseDay' => 200,
            'cloudsBaseNight' => 250,
            'visibilityDay' => 2000,
            'visibilityNight' => 2500,
        ],
        [
            'title' => 'NAVY planes',
            'cloudsBaseDay' => 450,
            'cloudsBaseNight' => 500,
            'visibilityDay' => 4000,
            'visibilityNight' => 5000,
        ],
        [
            'title' => 'Training JET-engine planes',
            'cloudsBaseDay' => 250,
            'cloudsBaseNight' => 300,
            'visibilityDay' => 4000,
            'visibilityNight' => 4500,
        ],
    ];
}
