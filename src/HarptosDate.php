<?php

namespace EFUPW\FR;

/**
 * This class provides a static method for determining the name of a date
 * in the Forgotten Realms Harptos calendar.
 */
final class HarptosDate
{
    /**
     * @var array $months A 1-indexed map of month numbers to month names.
     */
    private static $months = [
         1 => 'Hammer',
         2 => 'Alturiak',
         3 => 'Ches',
         4 => 'Tarsakh',
         5 => 'Mirtul',
         6 => 'Kythorn',
         7 => 'Flamerule',
         8 => 'Eleasis',
         9 => 'Eleint',
        10 => 'Marpenoth',
        11 => 'Uktar',
        12 => 'Nightal',
    ];

    /**
     * Calculates the name of the date identified by
     * the number of days after the specified year.
     *
     * There are 365 days in every year,
     * unless the year is evenly divisible by 4;
     * then there are 366 days,
     * because of Shieldmeet.
     * If `$days` is greater than the number of days in `$year`
     * the difference will roll into the next year,
     * and so on until a date is found.
     *
     * Special occasions do not canonically belong to any month.
     * Here, except Shieldmeet,
     * they belong to the previous month for ease of use.
     *
     * The return value is a string naming the resulting date in full.
     * It includes the resulting day, month name, and year in DR.
     * The day is either the day number of the month or a special occasion.
     * Shieldmeet does not include the month name.
     *
     * Examples:
     *
     * ```php
     * // "First of Hammer, 1789 DR"
     * HarptosDate::rollDaysForYear(1, 1789);
     *
     * // "Shieldmeet, 1792 DR"
     * HarptosDate::rollDaysForYear(366, 1792);
     *
     * // "First of Hammer, 1793 DR"
     * HarptosDate::rollDaysForYear(366, 1792);
     * ```
     *
     * The result is undefined
     * unless `$days` and `$year` are positive integers.
     *
     * @param int $days the number of days to offset the year by
     * @param int $year the starting year in DR
     * @return string the full name of the date identified by
     * the starting year offset by the given number of days
     */
    public static function rollDaysForYear($days, $year) {
        $year_length = self::yearLength($year);
        $quadri_year_length = 4 * $year_length;

        while ($days > $quadri_year_length) {
            $days -= $quadri_year_length;
            $year += 4;
        }

        while ($days > $year_length) {
            $days -= $year_length;
            ++$year;
            $year_length = self::yearLength($year);
        }

        $month_num = 1;
        $leap_year = $year_length === 366;
        $month_length = self::monthLength($month_num, $leap_year);

        while ($days > $month_length) {
            $days -= $month_length;
            ++$month_num;
            $month_length = self::monthLength($month_num, $leap_year);
        }

        return self::dateName($days, $month_num) . ", {$year} DR";
    }

    /**
     * Gets the name of the day in the month identified by the number.
     *
     * @param int $day_num the day number
     * @param int $month_num the month number
     * @return string the date name
     */
    private static function dateName($day_num, $month_num) {
        if ($day_num === 32 && $month_num === 7) { return 'Shieldmeet'; }

        $month = self::$months[$month_num];

        if ($day_num === 31) {
            if ($month_num ===  1) { return "Midwinter, {$month}"; }
            if ($month_num ===  4) { return "Greengrass, {$month}"; }
            if ($month_num ===  7) { return "Midsummer, {$month}"; }
            if ($month_num ===  9) { return "High Harvestide, {$month}"; }
            if ($month_num === 11) { return "Feast of the Moon, {$month}"; }
        }

        if ($day_num ===  1) { return "First of {$month}"; }
        if ($day_num ===  2) { return "Second of {$month}"; }
        if ($day_num ===  3) { return "Third of {$month}"; }
        if ($day_num === 21) { return "{$month} {$day_num}st"; }
        if ($day_num === 22) { return "{$month} {$day_num}nd"; }
        if ($day_num === 23) { return "{$month} {$day_num}rd"; }

        return "{$month} {$day_num}th";
    }

    /**
     * Gets the number of days in the month for the month number,
     * depending on whether it is a leap year.
     *
     * The return value is either 30 or 31,
     * except for month number 7 during a leap year,
     * which is 32.
     *
     * @param int $month_num the number of the month to get the length of
     * @param bool $is_leap_year true to indicate this is a leap year
     * @return int the number of days in the month
     */
    private static function monthLength($month_num, $is_leap_year) {
        if ($month_num === 1)  { return 31; }
        if ($month_num === 4)  { return 31; }
        if ($month_num === 7)  { return 31 + (int) $is_leap_year; }
        if ($month_num === 9)  { return 31; }
        if ($month_num === 11) { return 31; }

        return 30;
    }

    /**
     * Gets the number of days in the specified year.
     *
     * The return value is 365,
     * unless `$year` is evenly divisible by 4;
     * then `$year` is a leap year
     * and the return value is 366.
     *
     * @param int $year the year to inspect
     * @return int the number of days in the year
     */
    private static function yearLength($year) {
        if ($year % 4 === 0) {
            return 366;
        }
        return 365;
    }
}
