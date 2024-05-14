<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Model;

class DateAgeModel
{
    private int $ageInMinutes;
    private int $ageInHours;
    private int $ageInDays;
    private int $ageInWeeks;
    private int $ageInMonths;
    private int $ageInYears;

    public const YEAR_SCALE = 'y';
    public const MONTH_SCALE = 'm';
    public const WEEK_SCALE = 'w';
    public const DAY_SCALE = 'd';
    public const HOUR_SCALE = 'h';
    public const MINUTE_SCALE = 'i';

    public function __construct(?string $dateTime)
    {
        try {
            $timestamp = strtotime($dateTime);
        } catch (\Exception $e) {
            throw new DateAgeException();
        }
        if (null === $timestamp) {
            throw new DateAgeException();
        }
        $this->ageInMinutes = round((time() - $timestamp) / 60);
        $this->ageInHours = floor($this->ageInMinutes / 60);
        $this->ageInDays = floor($this->ageInHours / 24);
        $this->ageInWeeks = floor($this->ageInDays / 7);
        $this->ageInMonths = floor($this->ageInDays / 30);
        $this->ageInYears = floor($this->ageInDays / 365);
    }

    public function getAgeRange(): string
    {
        if ($this->ageInYears > 0) {
            return self::YEAR_SCALE;
        }
        if ($this->ageInMonths > 0) {
            return self::MONTH_SCALE;
        }
        if ($this->ageInWeeks > 0) {
            return self::WEEK_SCALE;
        }
        if ($this->ageInDays > 0) {
            return self::DAY_SCALE;
        }
        if ($this->ageInHours > 0) {
            return self::HOUR_SCALE;
        }
        return self::MINUTE_SCALE;
    }

    public function getAgeInYears(): int
    {
        return $this->ageInYears;
    }

    public function getAgeInMonths(): int
    {
        return $this->ageInMonths;
    }

    public function getAgeInWeeks(): int
    {
        return $this->ageInWeeks;
    }

    public function getAgeInDays(): int
    {
        return $this->ageInDays;
    }

    public function getAgeInHours(): int
    {
        return $this->ageInHours;
    }

    public function getAgeInMinutes(): int
    {
        return $this->ageInMinutes;
    }
}
