<?php

namespace EFUPW\FR;

final class HarptosDate_RegularYearTest extends \PHPUnit_Framework_TestCase
{
    const SOME_NON_LEAP_YEAR = 1377;

    /**
     * @dataProvider firstDaysOfMonthProvider
     */
    public function testFirstDaysOfMonth($day, $month_name) {
        $result = HarptosDate::yearOffsetByDays(self::SOME_NON_LEAP_YEAR, $day);

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

    public function testDay214IsFirstOfEleasis() {
        $day = 214;

        $result = self::offsetDays($day);

        $this->assertEquals(1, $result->getDay());
        $this->assertEquals('Eleasis', $result->getMonthName());
    }

    public function testDay274IsHighHarvestide() {
        $day = 274;

        $result = self::offsetDays($day);

        $this->assertContains('High Harvestide', $result->__toString());
    }

    public function testDay335IsFeastOfTheMoon() {
        $day = 335;

        $result = self::offsetDays($day);

        $this->assertContains('Feast of the Moon', $result->__toString());
    }

    public function testDay365Is30thOfNightalSameYear() {
        $day = 365;
        $some_regular_year = 1377;

        $result = HarptosDate::yearOffsetByDays($some_regular_year, $day);

        $this->assertEquals($some_regular_year, $result->getYear());
        $this->assertEquals(12, $result->getMonth());
        $this->assertEquals(30, $result->getDay());
    }

    public function testDay366IsFirstOfHammerNextYear() {
        $day = 366;
        $some_starting_regular_year = 1386;
        $following_year = $some_starting_regular_year + 1;

        $result = HarptosDate::yearOffsetByDays(
            $some_starting_regular_year, $day);

        $this->assertEquals($following_year, $result->getYear());
        $this->assertEquals(1, $result->getMonth());
        $this->assertEquals(1, $result->getDay());
    }

    public function testDay1461IsFirstOfHammerFourYearsLater() {
        $day = 1461;
        $some_starting_regular_year = 1389;
        $four_years_later = $some_starting_regular_year + 4;

        $result = HarptosDate::yearOffsetByDays(
            $some_starting_regular_year, $day);

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
            [214, 'Eleasis'],
            [244, 'Eleint'],
            [275, 'Marpenoth'],
            [305, 'Uktar'],
            [336, 'Nightal'],
        ];
    }

    /**
     * @param int $days the number of days to add to `self::SOME_NON_LEAP_YEAR`.
     * @return HarptosDate
     */
    private static function offsetDays($days) {
        return HarptosDate::yearOffsetByDays(self::SOME_NON_LEAP_YEAR, $days);
    }
}
