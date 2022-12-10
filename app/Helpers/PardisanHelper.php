<?php

namespace App\Helpers;

use Morilog\Jalali\CalendarUtils;

class PardisanHelper
{
    /**
     * @return int
     */
    public static function getCurrentEducationalYear (): int
    {
        return self::getEducationalYearByTime(time());
    }

    /**
     * @param string $date
     * @return int
     */
    public static function getEducationalYearByGregorianDate (string $date): int
    {
        return self::getEducationalYearByTime(strtotime($date));
    }

    /**
     * @param string $date
     * @param string $format
     * @return int
     */
    public static function getEducationalYearByJalaliDate (string $date, string $format = 'Y-m-d H:i:s'): int
    {
        return self::getEducationalYearByTime(
          CalendarUtils::createCarbonFromFormat($format, $date)->timestamp
        );
    }

    /**
     * @param int $time
     * @return int
     */
    public static function getEducationalYearByTime (int $time): int
    {
        return CalendarUtils::strftime('n', $time) < 4 ? CalendarUtils::strftime('Y', $time) - 1 : CalendarUtils::strftime('Y', $time);
    }

    /**
     * @param string $userClass
     * @return string
     */
    public static function getUserTypeByUserClass (string $userClass): string
    {
        return match ($userClass){
            'App\\Models\\AdminModel' => 'admin',
            'App\\Models\\StudentModel' => 'student',
            'App\\Models\\TeacherModel' => 'teacher',
            default => 'unknown'
        };
    }
}
