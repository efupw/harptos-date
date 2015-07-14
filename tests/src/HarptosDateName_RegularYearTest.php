<?php

namespace EFUPW\FR;

final class HarptosDateName_RegularYearTest extends \PHPUnit_Framework_TestCase
{
    const SOME_NON_LEAP_YEAR = 1377;

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

    public function testDay214IsFirstOfEleasis() {
        $day = 214;

        $result = self::rollDays($day);
        $this->assertStringStartsWith('First of Eleasis', $result);
    }

    public function testDay274IsHighHarvestide() {
        $day = 274;

        $result = self::rollDays($day);
        $this->assertContains('High Harvestide', $result);
    }

    public function testDay335IsFeastOfTheMoon() {
        $day = 335;

        $result = self::rollDays($day);
        $this->assertContains('Feast of the Moon', $result);
    }

    public function testDay365Is30thOfNightalSameYear() {
        $day = 365;
        $some_regular_year = 1377;

        $result = HarptosDateName::rollDaysForYear(
            $day, $some_regular_year);

        $this->assertStringStartsWith(
            "Nightal 30th, {$some_regular_year}",
            $result);
    }

    public function testDay366IsFirstOfHammerNextYear() {
        $day = 366;
        $some_starting_regular_year = 1386;
        $following_year = $some_starting_regular_year + 1;

        $result = HarptosDateName::rollDaysForYear(
            $day, $some_starting_regular_year);

        $this->assertStringStartsWith(
            "First of Hammer, {$following_year}",
            $result);
    }

    public function testDay1461IsFirstOfHammerFourYearsLater() {
        $day = 1461;
        $some_starting_regular_year = 1389;
        $four_years_later = $some_starting_regular_year + 4;

        $result = HarptosDateName::rollDaysForYear(
            $day, $some_starting_regular_year);

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
            [214, 'Eleasis'],
            [244, 'Eleint'],
            [275, 'Marpenoth'],
            [305, 'Uktar'],
            [336, 'Nightal'],
        ];
    }

    private static function rollDays($day) {
        return HarptosDateName::rollDaysForYear(
            $day, self::SOME_NON_LEAP_YEAR);
    }
}
