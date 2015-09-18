<?php

namespace EFUPW\FR;

final class HarptosDate_LeapYearTest extends \PHPUnit_Framework_TestCase
{
    const SOME_LEAP_YEAR = 1376;

    /**
     * @dataProvider firstDaysOfMonthProvider
     */
    public function testFirstDaysOfMonth($day, $month_name) {
        $result = self::rollDays($day);

        $this->assertStringStartsWith("First of {$month_name}", $result);
    }

    public function testDay31IsMidwinter() {
        $day = 31;

        $result = self::rollDays($day);
        $this->assertContains('Midwinter', $result);
    }

    public function testDay122IsGreengrass() {
        $day = 122;

        $result = self::rollDays($day);
        $this->assertContains('Greengrass', $result);
    }

    public function testDay213IsMidsummer() {
        $day = 213;

        $result = self::rollDays($day);
        $this->assertContains('Midsummer', $result);
    }

    public function testDay214IsShieldmeet() {
        $day = 214;

        $result = self::rollDays($day);
        $this->assertStringStartsWith('Shieldmeet', $result);
    }

    public function testDay215IsFirstOfEleasis() {
        $day = 215;

        $result = self::rollDays($day);
        $this->assertStringStartsWith('First of Eleasis', $result);
    }

    public function testDay275IsHighHarvestide() {
        $day = 275;

        $result = self::rollDays($day);
        $this->assertContains('High Harvestide', $result);
    }

    public function testDay336IsFeastOfTheMoon() {
        $day = 336;

        $result = self::rollDays($day);
        $this->assertContains('Feast of the Moon', $result);
    }

    public function testDay366Is30thOfNightalSameYear() {
        $day = 366;
        $some_leap_year = 1376;

        $result = HarptosDate::rollDaysForYear(
            $day, $some_leap_year);

        $this->assertStringStartsWith(
            "Nightal 30th, {$some_leap_year}",
            $result);
    }

    public function testDay367IsFirstOfHammerNextYear() {
        $day = 367;
        $some_starting_leap_year = 1384;
        $following_year = $some_starting_leap_year + 1;

        $result = HarptosDate::rollDaysForYear(
            $day, $some_starting_leap_year);

        $this->assertStringStartsWith(
            "First of Hammer, {$following_year}",
            $result);
    }

    public function testDay1461Is30thOfNightalThreeYearsLater() {
        $day = 1461;
        $some_starting_leap_year = 1388;
        $three_years_later = $some_starting_leap_year + 3;

        $result = HarptosDate::rollDaysForYear(
            $day, $some_starting_leap_year);

        $this->assertStringStartsWith(
            "Nightal 30th, ${three_years_later}",
            $result);
    }

    public function testDay1462IsFirstOfHammerFourYearsLater() {
        $day = 1462;
        $some_starting_leap_year = 1388;
        $four_years_later = $some_starting_leap_year + 4;

        $result = HarptosDate::rollDaysForYear(
            $day, $some_starting_leap_year);

        $this->assertStringStartsWith(
            "First of Hammer, ${four_years_later}",
            $result);
    }

    public function firstDaysOfMonthProvider() {
        return [
            [  1, 'Hammer'],
            [ 32, 'Alturiak'],
            [ 62, 'Ches'],
            [ 92, 'Tarsakh'],
            [123, 'Mirtul'],
            [153, 'Kythorn'],
            [183, 'Flamerule'],
            [215, 'Eleasis'],
            [245, 'Eleint'],
            [276, 'Marpenoth'],
            [306, 'Uktar'],
            [337, 'Nightal'],
        ];
    }

    private static function rollDays($day) {
        return HarptosDate::rollDaysForYear(
            $day, self::SOME_LEAP_YEAR);
    }
}
