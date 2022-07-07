<?php

namespace App\Helper;

class Helper
{
    public static function formatFrequency($frequency)
    {
        return number_format(($frequency / 1000000), 3, '.', ' ') . ' MHz';
    }

    public static function generateRandomString($length = 10) {
        return substr(
            str_shuffle(
                str_repeat(
                    $x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                    ceil($length/strlen($x))
                )
            ),1,$length);
    }

    public static function calculateFlightTime($time, $returnString = true)
    {
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
        if ($returnString) {
            return $time . ":" . $min . ":" . $sec;
        }
        return array(
            'hour' => (int)$time,
            'minute' => (int)$min,
            'second' => (int)$sec,
        );
    }

    /**
     * @param string|null $email
     * @return bool
     */
    public static function isValidEmail(?string $email): bool
    {
        if ($email === null) {
            return false;
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    public static function isJson($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * @param $string
     * @return string
     */
    public static function base64ToJson($string): string
    {
        return base64_decode($string);
    }

    public static function jsonToArray($string)
    {
        return json_decode($string, true);
    }

    public static function isValidData(string $json): bool
    {
        if (empty($json)) {
            return false;
        }
        if (!self::isJson($json)) {
            return false;
        }
        $data = self::jsonToArray($json);
        if (!isset($data['event']) || !isset($data['time']) || !isset($data['server'])) {
            return false;
        }

        if (empty($data['event']) || empty($data['time']) || empty($data['server'])) {
            return false;
        }

        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        if (!preg_match($UUIDv4, base64_decode($data['server']))) {
            return false;
        }

        return true;
    }
}