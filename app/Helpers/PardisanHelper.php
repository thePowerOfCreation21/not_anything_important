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
        return CalendarUtils::date('n') < 7 ? CalendarUtils::date('Y') - 1 : CalendarUtils::date('Y');
    }
}
