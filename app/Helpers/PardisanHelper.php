<?php

namespace App\Helpers;

use Morilog\Jalali\CalendarUtils;

class PardisanHelper
{
    /**
     * @return mixed
     */
    public static function getCurrentEducationalYear (): mixed
    {
        return self::getEducationalYearByTime(time());
    }

    /**
     * @param string $date
     * @return mixed
     */
    public static function getEducationalYearByGregorianDate (string $date): mixed
    {
        return self::getEducationalYearByTime(strtotime($date));
    }

    /**
     * @param string $date
     * @param string $format
     * @return mixed
     */
    public static function getEducationalYearByJalaliDate (string $date, string $format = 'Y-m-d H:i:s'): mixed
    {
        return self::getEducationalYearByTime(
          CalendarUtils::createCarbonFromFormat($format, $date)->timestamp
        );
    }

    /**
     * @param int $time
     * @return mixed
     */
    public static function getEducationalYearByTime (int $time): mixed
    {
        return CalendarUtils::strftime('n', $time) < 7 ? CalendarUtils::strftime('Y', $time) - 1 : CalendarUtils::strftime('Y', $time);
    }
}
