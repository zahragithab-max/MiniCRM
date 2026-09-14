<?php

namespace App\Support\Helpers;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class JalaliHelper
{
    public static function toJalali(
        Carbon|string|null $date
    ): ?string {
        if (!$date) {
            return null;
        }

        $carbon = $date instanceof Carbon
            ? $date
            : Carbon::parse($date);

        return Jalalian::fromDateTime($carbon)->format('Y/m/d');
    }

    public static function toGregorian(
        string|null $date
    ): ?string {
        if (!$date) {
            return null;
        }

        return Jalalian::fromFormat(
            'Y/m/d',
            $date
        )->toCarbon()->format('Y-m-d');
    }
}