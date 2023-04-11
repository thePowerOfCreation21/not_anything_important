<?php

namespace App\Helpers;

use App\Models\StudentFinancialModel;
use DateTime;
use Morilog\Jalali\CalendarUtils;

class PardisanHelper
{
    /**
     * @return int
     */
    public static function getCurrentEducationalYear(): int
    {
        return self::getEducationalYearByTime(time());
    }

    /**
     * @param string $date
     * @return int
     */
    public static function getEducationalYearByGregorianDate(string $date): int
    {
        return self::getEducationalYearByTime(strtotime($date));
    }

    /**
     * @param string $date
     * @param string $format
     * @return int
     */
    public static function jalaliDateToTime(string $date, string $format = 'Y-m-d H:i:s'): int
    {
        return CalendarUtils::createCarbonFromFormat($format, $date)->timestamp;
    }

    /**
     * @param string $date
     * @param string $format
     * @return int
     */
    public static function getEducationalYearByJalaliDate(string $date, string $format = 'Y-m-d H:i:s'): int
    {
        return self::getEducationalYearByTime(
            self::jalaliDateToTime($date, $format)
        );
    }

    /**
     * @param int $time
     * @return int
     */
    public static function getEducationalYearByTime(int $time): int
    {
        return CalendarUtils::strftime('n', $time) < 4 ? CalendarUtils::strftime('Y', $time) - 1 : CalendarUtils::strftime('Y', $time);
    }

    /**
     * @param int|null $time
     * @return int
     */
    public static function getWeekDayByTime(int $time = null): int
    {
        return CalendarUtils::strftime('w', $time ?? time());
    }

    /**
     * @param string $date
     * @param string $format
     * @return int
     */
    public static function getWeekDayByJalaliDate(string $date, string $format = 'Y-m-d H:i:s'): int
    {
        return self::getWeekDayByTime(
            self::jalaliDateToTime($date, $format)
        );
    }

    /**
     * @param string $date
     * @return int
     */
    public static function getWeekDayByGregorianDate(string $date): int
    {
        return self::getWeekDayByTime(strtotime($date));
    }

    /**
     * @param string $userClass
     * @return string
     */
    public static function getUserTypeByUserClass(string $userClass): string
    {
        return match ($userClass) {
            'App\\Models\\AdminModel' => 'admin',
            'App\\Models\\StudentModel' => 'student',
            'App\\Models\\TeacherModel' => 'teacher',
            default => 'unknown'
        };
    }

    /**
     * @param $studentFinancial
     * @return string
     */
    public static function getStudentFinancialPaymentStatus($studentFinancial): string
    {
        if ($studentFinancial->paid) {
            return 'paid';
        }

        $due_time = strtotime($studentFinancial->date);
        $current_time = time();

        if ($due_time < $current_time) {
            return 'passed';
        }
        if ($due_time <= ($current_time + (86400 * 7))) {
            return 'near-due';
        }

        return 'not_paid';
    }

    /**
     * @param string $date
     * @return bool
     */
    public static function isDateToday(string $date): bool
    {
        return date('Y-m-d') == date('Y-m-d', strtotime($date));
    }

    // ooooooooooooooooooooooooo :o
    public static function makeItNumber ($string, $options = ['decimal'=>true, 'negative'=>true])
    {

        $options = [
            'decimal' => $options['decimal'] ?? true,
            'negative' => $options['negative'] ?? true
        ];

        //echo "input: ".$string."\n";

        // removing all non digits ecxept . , -
        $string = preg_replace('/[^-.\d]/', '', $string);

        if ($options['decimal'])
        {
            // replacing the first . with d so we can remove the other dots then replace the d with .
            $string = preg_replace('/[.]/', 'd', $string, 1);
        }

        if ($options['negative'])
        {
            // replacing the - in first character with m so we can remove the other mineses then replace the m with -
            $string = preg_replace('/^[-]/', 'm', $string, 1);
        }

        // removing dots from string
        $string = preg_replace('/[.]/', '', $string);

        // removing mineses from string
        $string = preg_replace('/[-]/', '', $string);

        // replacing the d with . (wich was the first . in the string)
        $string = preg_replace('/[d]/', '.', $string);

        // replacing the m with -
        $string = preg_replace('/[m]/', '-', $string);

        //echo "output: ".$string."\n";

        return floatval($string);
    }
}
