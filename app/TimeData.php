<?php

namespace App;

use DateTime;
use DateTimeZone;

class TimeData
{
    private static $timezone = "Europe/Warsaw";

    public static function timeToStr($t)
    {
        $dt = new DateTime();
        $dt->setTimestamp($t);
        $dt->setTimezone(new DateTimeZone(self::$timezone));
        return $dt->format('H:i:s d.m.Y');
    }
}
