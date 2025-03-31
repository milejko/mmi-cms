<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Unit\Cms\Model;

use CmsAdmin\Model\DateAgeModel;
use PHPUnit\Framework\TestCase;

class DateAgeModelTest extends TestCase
{
    public function testIfNowGivesAgeZero(): void
    {
        $dam = new DateAgeModel('now');
        self::assertEquals(0, $dam->getAgeInMinutes());
        self::assertEquals(0, $dam->getAgeInHours());
        self::assertEquals(0, $dam->getAgeInDays());
        self::assertEquals(0, $dam->getAgeInWeeks());
        self::assertEquals(0, $dam->getAgeInMonths());
        self::assertEquals(0, $dam->getAgeInYears());
        self::assertEquals('i', $dam->getAgeRange());
    }

    public function testIfFewMinutesAgoGivesProperAge(): void
    {
        $dam = new DateAgeModel('-5 minutes');
        self::assertEquals('i', $dam->getAgeRange());
        self::assertEquals(5, $dam->getAgeInMinutes());

        self::assertEquals(0, $dam->getAgeInHours());
        self::assertEquals(0, $dam->getAgeInDays());
        self::assertEquals(0, $dam->getAgeInWeeks());
        self::assertEquals(0, $dam->getAgeInMonths());
        self::assertEquals(0, $dam->getAgeInYears());
    }

    public function testIfFewHoursAgoGivesProperAge(): void
    {
        $dam = new DateAgeModel('-5 hours');
        self::assertEquals('h', $dam->getAgeRange());
        self::assertEquals(5, $dam->getAgeInHours());
        self::assertEquals(300, $dam->getAgeInMinutes());

        self::assertEquals(0, $dam->getAgeInDays());
        self::assertEquals(0, $dam->getAgeInWeeks());
        self::assertEquals(0, $dam->getAgeInMonths());
        self::assertEquals(0, $dam->getAgeInYears());
    }

    public function testIfFewDaysAgoGivesProperAge(): void
    {
        $dam = new DateAgeModel('-5 days');
        self::assertEquals('d', $dam->getAgeRange());
        self::assertLessThanOrEqual(5, $dam->getAgeInDays());
        #time change from summer to winter can give range 119-121 (normally 120)
        self::assertGreaterThanOrEqual(119, $dam->getAgeInHours());
        self::assertLessThanOrEqual(121, $dam->getAgeInHours());
        #time change from summer to winter can give 7140-7260 (normally 7200)
        self::assertGreaterThanOrEqual(7140, $dam->getAgeInMinutes());
        self::assertLessThanOrEqual(7260, $dam->getAgeInMinutes());

        self::assertEquals(0, $dam->getAgeInWeeks());
        self::assertEquals(0, $dam->getAgeInMonths());
        self::assertEquals(0, $dam->getAgeInYears());
    }

    public function testIfFewWeeksAgoGivesProperAge(): void
    {
        $dam = new DateAgeModel('-3 weeks');
        self::assertEquals('w', $dam->getAgeRange());
        self::assertLessThanOrEqual(3, $dam->getAgeInWeeks());
        self::assertLessThanOrEqual(21, $dam->getAgeInDays());
        #time change from summer to winter can give 503-505 (normally 504)
        self::assertGreaterThanOrEqual(503, $dam->getAgeInHours());
        self::assertLessThanOrEqual(505, $dam->getAgeInHours());
        #time change from summer to winter can give 30240-30390 (normally 30330)
        self::assertGreaterThanOrEqual(30180, $dam->getAgeInMinutes());
        self::assertLessThanOrEqual(30390, $dam->getAgeInMinutes());

        self::assertEquals(0, $dam->getAgeInMonths());
        self::assertEquals(0, $dam->getAgeInYears());
    }

    public function testIfFewMonthsAgoGivesProperAge(): void
    {
        $dam = new DateAgeModel('-9 months');
        self::assertEquals('m', $dam->getAgeRange());
        self::assertEquals(9, $dam->getAgeInMonths());
        self::assertGreaterThan(36, $dam->getAgeInWeeks());
        self::assertGreaterThan(250, $dam->getAgeInDays());
        self::assertGreaterThan(6000, $dam->getAgeInHours());
        self::assertGreaterThan(144000, $dam->getAgeInMinutes());

        self::assertEquals(0, $dam->getAgeInYears());
    }

    public function testIfFewYearsAgoGivesProperAge(): void
    {
        $dam = new DateAgeModel('-3 years');
        self::assertEquals('y', $dam->getAgeRange());
        self::assertEquals(3, $dam->getAgeInYears());
        self::assertGreaterThan(30, $dam->getAgeInMonths());
        self::assertGreaterThan(150, $dam->getAgeInWeeks());
        self::assertGreaterThan(900, $dam->getAgeInDays());
        self::assertGreaterThan(21600, $dam->getAgeInHours());
        self::assertGreaterThan(51840, $dam->getAgeInMinutes());
    }
}
