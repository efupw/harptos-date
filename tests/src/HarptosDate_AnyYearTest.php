<?php

namespace EFUPW\FR;

final class HarptosDate_AnyYearTest extends \PHPUnit_Framework_TestCase
{
    const SOME_YEAR = 1377;

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNegativeYearThrowsInvalidArgument() {
        $some_negative_year = -1;
        $some_days = 42;

        HarptosDate::yearOffsetByDays($some_negative_year, $some_days);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testYear0ThrowsInvalidArgument() {
        $year_0 = 0;
        $some_days = 42;

        HarptosDate::yearOffsetByDays($year_0, $some_days);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNegativeDaysThrowsInvalidArgument() {
        $some_negative_days = -1;

        HarptosDate::yearOffsetByDays(self::SOME_YEAR, $some_negative_days);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test0DaysThrowsInvalidArgument() {
        $zero_days = 0;

        HarptosDate::yearOffsetByDays(self::SOME_YEAR, $zero_days);
    }

    public function testDay1StartsWithFirstOf() {
        $day = 1;

        $result = self::offsetDays($day);

        $this->assertStringStartsWith('First of', $result->__toString());
    }

    public function testDay2StartsWithSecondOf() {
        $day = 2;

        $result = self::offsetDays($day);

        $this->assertStringStartsWith('Second of', $result->__toString());
    }

    public function testDay3StartsWithThirdOf() {
        $day = 3;

        $result = self::offsetDays($day);

        $this->assertStringStartsWith('Third of', $result->__toString());
    }

    public function testDay4Contains4th() {
        $day = 4;

        $result = self::offsetDays($day);

        $this->assertContains('4th', $result->__toString());
    }

    public function testDay21Contains21st() {
        $day = 21;

        $result = self::offsetDays($day);

        $this->assertContains('21st', $result->__toString());
    }

    public function testDay22Contains22nd() {
        $day = 22;

        $result = self::offsetDays($day);

        $this->assertContains('22nd', $result->__toString());
    }

    public function testDay23Contains23rd() {
        $day = 23;

        $result = self::offsetDays($day);

        $this->assertContains('23rd', $result->__toString());
    }

    public function testDay24Contains24th() {
        $day = 24;

        $result = self::offsetDays($day);

        $this->assertContains('24th', $result->__toString());
    }

    public function testAllDaysEndWithDr() {
        $some_leap_year = 1380;

        for ($day = 1; $day <= 366; ++$day) {
            $result = HarptosDate::yearOffsetByDays($some_leap_year, $day);

            $this->assertStringEndsWith(' DR', $result->__toString());
        }
    }

    public function testAllDaysIncludeYear() {
        $some_leap_year = 1380;

        for ($day = 1; $day <= 366; ++$day) {
            $result = HarptosDate::yearOffsetByDays($some_leap_year, $day);

            $this->assertContains("$some_leap_year", $result->__toString());
        }
    }

    /**
     * @param int $days the number of days to add to `self::SOME_NON_LEAP_YEAR`.
     * @return HarptosDate
     */
    private static function offsetDays($days) {
        return HarptosDate::yearOffsetByDays(self::SOME_YEAR, $days);
    }
}
