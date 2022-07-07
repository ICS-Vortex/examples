<?php

namespace App\Service;

class FlightsService
{
    /**
     * @param integer $time
     * @return string
     */
    public function formatTime($time){
        if ($time < 0) {
            return '00:00:00';
        }
        $sec = $time % 60;
        $time = floor($time / 60);
        $min = $time % 60;
        $time = floor($time / 60);
        if ($sec < 10) {
            $sec = "0" . $sec;
        }
        if ($min < 10) {
            $min = "0" . $min;
        }
        if ($time < 10) {
            $time = "0" . $time;
        }
        $total =  $time . ":" . $min . ":" . $sec;
        return $total;
    }
}