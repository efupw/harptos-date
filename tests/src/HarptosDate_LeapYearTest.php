<?php

namespace EFUPW\FR;

final class HarptosDate_LeapYearTest extends \PHPUnit_Framework_TestCase
{
    const SOME_LEAP_YEAR = 1376;

    /**
     * @dataProvider firstDaysOfMonthProvider
     */
    public function testFirstDaysOfMonth($day, $month_name) {
        $result = HarptosDate::yearOffsetByDays(self::SOME_LEAP_YEAR, $day);

        $this->assertEquals(1, $result->getDay());
        $this->assertEquals($month_name, $result->getMonthName());
    }

    public function testDay31IsMidwinter() {
        $day = 31;

        $result = self::offsetDays($day);

        $this->assertContains('Midwinter', $result->__toString());
    }

    public function testDay122IsGreengrass() {
        $day = 122;

        $result = self::offsetDays($day);

        $this->assertContains('Greengrass', $result->__toString());
    }

    public function testDay213IsMidsummer() {
        $day = 213;

        $result = self::offsetDays($day);

        $this->assertContains('Midsummer', $result->__toString());
    }

    public function testDay214IsShieldmeet() {
        $day = 214;

        $result = self::offsetDays($day);

        $this->assertStringStartsWith('Shieldmeet', $result->__toString());
    }

    public function testDay215IsFirstOfEleasis() {
        $day = 215;

        $result = self::offsetDays($day);

        $this->assertEquals(1, $result->getDay());
        $this->assertEquals('Eleasis', $result->getMonthName());
    }

    public function testDay275IsHighHarvestide() {
        $day = 275;

        $result = self::offsetDays($day);

        $this->assertContains('High Harvestide', $result->__toString());
    }

    public function testDay336IsFeastOfTheMoon() {
        $day = 336;

        $result = self::offsetDays($day);

        $this->assertContains('Feast of the Moon', $result->__toString());
    }

    public function testDay366Is30thOfNightalSameYear() {
        $day = 366;
        $some_leap_year = 1376;

        $result = HarptosDate::yearOffsetByDays($some_leap_year, $day);

        $this->assertEquals($some_leap_year, $result->getYear());
        $this->assertEquals(12, $result->getMonth());
        $this->assertEquals(30, $result->getDay());
    }

    public function testDay367IsFirstOfHammerNextYear() {
        $day = 367;
        $some_starting_leap_year = 1384;
        $following_year = $some_starting_leap_year + 1;

        $result = HarptosDate::yearOffsetByDays(
            $some_starting_leap_year, $day);

        $this->assertEquals($following_year, $result->getYear());
        $this->assertEquals(1, $result->getMonth());
        $this->assertEquals(1, $result->getDay());
    }

    public function testDay1461Is30thOfNightalThreeYearsLater() {
        $day = 1461;
        $some_starting_leap_year = 1388;
        $three_years_later = $some_starting_leap_year + 3;

        $result = HarptosDate::yearOffsetByDays(
            $some_starting_leap_year, $day);

        $this->assertEquals($three_years_later, $result->getYear());
        $this->assertEquals(12, $result->getMonth());
        $this->assertEquals(30, $result->getDay());
    }

    public function testDay1462IsFirstOfHammerFourYearsLater() {
        $day = 1462;
        $some_starting_leap_year = 1388;
        $four_years_later = $some_starting_leap_year + 4;

        $result = HarptosDate::yearOffsetByDays(
            $some_starting_leap_year, $day);

        $this->assertEquals($four_years_later, $result->getYear());
        $this->assertEquals(1, $result->getMonth());
        $this->assertEquals(1, $result->getDay());
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

    /**
     * @param int $days the number of days to add to `self::SOME_NON_LEAP_YEAR`.
     * @return HarptosDate
     */
    private static function offsetDays($days) {
        return HarptosDate::yearOffsetByDays(self::SOME_LEAP_YEAR, $days);
    }
}
