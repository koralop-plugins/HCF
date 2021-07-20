<?php

namespace Koralop\HCF\utils;

/**
 * Class Time
 * @package Koralop\HCF\utils
 */
final class Time
{

    /**
     * @param int $time
     * @return false|string
     */
    public static function secondAndMinutes(int $time)
    {
        return explode(':', gmdate("i:s", $time))[0] == '00' ? explode(':', gmdate("i:s", $time))[1] . 's' : gmdate("i:s", $time);
    }

    /**
     * @param int $time
     * @return false|string
     */
    public static function HSM(int $time)
    {
        $args = explode(':', gmdate("H:i:s", $time));

        if ($args[1] == '00' && $args[0] == '00') {
            return $args[1] . 's';
        }
        return gmdate("H:i:s", $time);
    }

    /**
     * @param int $time
     * @return string
     */
    public static function asd(int $time): string
    {
        $args = explode(':', gmdate("h:i:s", $time));

        if ($args[0] == '00') {
            return $args[1] . 's';
        } else {
            return $args[0] . 'm';
        }
    }

    /**
     * @param int $reaming
     * @return string
     */
    public static function kitTime(int $reaming): string
    {
        $days = 0;
        $hours = 0;
        $minutes = 0;
        $seconds = 0;

        $args = explode(':', gmdate('D:H:i:s', $reaming));
        if ($args[0] != '00')
            $days = $args[0];

        if ($args[1] != '00')
            $hours = $args[1];

        if ($args[2] != '00')
            $minutes = $args[2];

        if ($args[3] != '00')
            $seconds = $args[3];


        return $days . 'd, ' . $hours . 'h, ' . $minutes . 'm, ' . $seconds . 's';
    }
}