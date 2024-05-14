<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Unit\Cms\Mvc\ViewHelper;

use CmsAdmin\Mvc\ViewHelper\DateAge;
use Mmi\App\AppTesting;
use PHPUnit\Framework\TestCase;

class DateAgeTest extends TestCase
{
    private static DateAge $dateAgeHelper;

    public static function setUpBeforeClass(): void
    {
        self::$dateAgeHelper = AppTesting::$di->get(DateAge::class);
    }

    public function testIfLabelNowIsCorrectlyShown(): void
    {
        self::assertEquals('<span style="opacity: 1;">przed chwilą</span>', self::$dateAgeHelper->dateAge('now'));
    }

    public function testIfLabel5MinutesAgoIsShown(): void
    {
        self::assertEquals('<span style="opacity: 1;">5 minut(y)</span>', self::$dateAgeHelper->dateAge('-5 minutes'));
    }

    public function testIfLabel10YearsAgoIsShown(): void
    {
        self::assertEquals('<span style="opacity: 0.06;">10 lat(a)</span>', self::$dateAgeHelper->dateAge('-10 years'));
    }
}
