<?php

namespace App\Helpers;

use Morilog\Jalali\CalendarUtils;

class PardisanHelper
{
    /**
     * @return string
     */
    public static function getCurrentEducationalYear (): string
    {
        return self::getEducationalYearByTime(time());
    }

    /**
     * @param string $date
     * @return string
     */
    public static function getEducationalYearByGregorianDate (string $date): string
    {
        return self::getEducationalYearByTime(strtotime($date));
    }

    /**
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function getEducationalYearByJalaliDate (string $date, string $format = 'Y-m-d H:i:s'): string
    {
        return self::getEducationalYearByTime(
          CalendarUtils::createCarbonFromFormat($format, $date)->timestamp
        );
    }

    /**
     * @param int $time
     * @return string
     */
    public static function getEducationalYearByTime (int $time): string
    {
        return CalendarUtils::strftime('n', $time) < 7 ? CalendarUtils::strftime('Y', $time) - 1 : CalendarUtils::strftime('Y', $time);
    }
}
