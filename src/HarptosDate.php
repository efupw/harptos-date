<?php

namespace EFUPW\FR;

/**
 * This class provides a simple, immutable representation of a date
 * in the Forgotten Realms Calendar of Harptos.
 *
 * There are 365 days in every year,
 * unless the year is evenly divisible by 4;
 * then there are 366 days,
 * because of Shieldmeet.
 *
 * New instances can only be created with
 * the static factory method `yearOffsetByDays`:
 *
 * ```php
 * // 1491-02-11
 * $harptos_date = HarptosDate::yearOffsetByDays(1491, 42);
 * ```
 *
 * Formally, special occasions are not associated with any months
 * but rather lie between months.
 * For practical purposes, and contractually,
 * this class associates all special occasions with the *previous* month,
 * so that some months have 31 days instead of only 30.
 * Further, Shieldmeet, which occurs midway through every fourth year,
 * is the 32nd (and last) day of that month.
 *
 * @see HarptosDate::yearOffsetByDays
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
     * @var int
     */
    private $year;

    /**
     * @var int
     */
    private $month;

    /**
     * @var int
     */
    private $day;

    /**
     * @var null|string
     */
    private $to_string;

    private function __construct($year, $month, $day) {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    /**
     * Factory method for an instance of this class.
     *
     * The resulting instance represents the Harptos date
     * corresponding to the number of days
     * after the start of the specified year.
     *
     * If `$days` is greater than the number of days in `$year`
     * the difference will roll into the next year,
     * and so on until a date is found.
     *
     * Examples:
     *
     * ```php
     * // 1489-01-01
     * HarptosDate::yearOffsetByDays(1489, 1);
     *
     * // 1492-07-32 (Shieldmeet)
     * HarptosDate::yearOffsetByDays(1492, 214);
     *
     * // 1493-01-01
     * HarptosDate::yearOffsetByDays(1492, 366);
     * ```
     *
     * @param int $year the starting year in Dale Reckoning
     * @param int $days the number of days to offset the year by
     * @return HarptosDate a date instance representing the supplied arguments
     * @throws \InvalidArgumentException
     * if either `$year` or `$days` is not a positive integer
     */
    public static function yearOffsetByDays($year, $days) {
        if ((int) $year < 1) {
            throw new \InvalidArgumentException('Year number must be positive');
        }

        if ((int) $days < 1) {
            throw new \InvalidArgumentException('Days number must be positive');
        }

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

        return new self($year, $month_num, $days);
    }

    /**
     * Gets the Dale Reckoning year of this instance.
     *
     * @return int the year in DR
     */
    public function getYear() {
        return $this->year;
    }

    /**
     * Gets the month number of this instance.
     *
     * The range of values is [1; 12].
     *
     * @return int the month number of the year
     * @see getMonthName
     */
    public function getMonth() {
        return $this->month;
    }

    /**
     * Gets the human friendly name of this instance's month.
     *
     * @return string the month name
     * @see getMonth
     */
    public function getMonthName() {
        return self::$months[$this->month];
    }

    /**
     * Gets the day number in the month of this instance.
     *
     * The range of values depends on the month and the year:
     *
     * * It is normally [1; 30].
     * * It is [1; 31] if a special occasion follows the month;
     * the 31st is the special occasion.
     * * It is [1; 32] if 4 evenly divides the year and `getMonth()` returns 7;
     * the 32nd is Shieldmeet.
     *
     * @return int the day number of the month
     */
    public function getDay() {
        return $this->day;
    }

    /**
     * Pretty-prints this date.
     *
     * The return value is suitable for human consumption.
     * It normally includes the day, the month, and the year;
     * special occasions never include the day
     * and Shieldmeet only includes the year.
     *
     * @return string a human friendly string representation
     */
    public function __toString() {
        if (!$this->to_string) {
            $this->to_string = self::dateName($this->day, $this->month)
                . ", {$this->year} DR";
        }

        return $this->to_string;
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

    public function getDate() {
        return $this->getEFUDate();
    }

    private static function getMonthLength($month, $year) {
        $fouryear = 0;
        if (($year % 4) == 0) $fouryear = 1;
        if ($month == 1) return 31;
        if ($month == 4) return 31;
        if ($month == 7) return $fouryear + 31;
        if ($month == 9) return 31;
        if ($month == 11) return 31;
        return 30;
    }

    private function getEFUDate() {
        $now = getdate();
        $date_offset = -53;
        $est_offset = 18000;
        $seconds = $now[0] - $est_offset + ($date_offset * 24 * 60 * 60);

        $total_days = ($seconds / 86400);
        $day_of_sanc_start_year = 12886;
        $daysSinceEra = floor($total_days - $day_of_sanc_start_year);

        $year = 1375;
        $month = 1;

        while ($daysSinceEra > self::monthLength($month, $year)) {
            $daysSinceEra -= self::monthLength($month, $year);
            ++$month;
            if ($month === 13) {
                $month = 1;
                ++$year;
            }
        }

        $adjustedYear = $year - 1222;

        return "::[ " . self::dateName($daysSinceEra, $month)
            . " : Year {$adjustedYear} : {$year} DR ]::";
    }
}
