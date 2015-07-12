<?php

namespace EFUPW\FR;

final class HarptosDateName_AnyYearTest extends \PHPUnit_Framework_TestCase
{
    const SOME_YEAR = 1377;

    public function testDay1StartsWithFirstOf() {
        $day = 1;

        $result = self::rollDays($day);
        $this->assertStringStartsWith('First of', $result);
    }

    public function testDay2StartsWithSecondOf() {
        $day = 2;

        $result = self::rollDays($day);
        $this->assertStringStartsWith('Second of', $result);
    }

    public function testDay3StartsWithThirdOf() {
        $day = 3;

        $result = self::rollDays($day);
        $this->assertStringStartsWith('Third of', $result);
    }

    public function testDay4Contains4th() {
        $day = 4;

        $result = self::rollDays($day);
        $this->assertContains('4th', $result);
    }

    public function testDay21Contains21st() {
        $day = 21;

        $result = self::rollDays($day);
        $this->assertContains('21st', $result);
    }

    public function testDay22Contains22nd() {
        $day = 22;

        $result = self::rollDays($day);
        $this->assertContains('22nd', $result);
    }

    public function testDay23Contains23rd() {
        $day = 23;

        $result = self::rollDays($day);
        $this->assertContains('23rd', $result);
    }

    public function testDay24Contains24th() {
        $day = 24;

        $result = self::rollDays($day);
        $this->assertContains('24th', $result);
    }

    public function testAllDaysEndWithDr() {
        $some_leap_year = 1380;

        for ($day = 1; $day <= 366; ++$day) {
            $result = HarptosDateName::rollDaysForYear(
                $day, $some_leap_year);
            $this->assertStringEndsWith(' DR', $result);
        }
    }

    public function testAllDaysIncludeYear() {
        $some_leap_year = 1380;

        for ($day = 1; $day <= 366; ++$day) {
            $result = HarptosDateName::rollDaysForYear(
                $day, $some_leap_year);
            $this->assertContains("$some_leap_year", $result);
        }
    }

    private static function rollDays($day) {
        return HarptosDateName::rollDaysForYear(
            $day, self::SOME_YEAR);
    }
}
