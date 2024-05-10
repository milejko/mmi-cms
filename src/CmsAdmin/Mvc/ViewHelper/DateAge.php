<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Mvc\ViewHelper;

use Mmi\Mvc\ViewHelper\HelperAbstract;

class DateAge extends HelperAbstract
{
    private const TEMPLATE = '<span style="opacity: %s;">%s</span>';
    
    public function dateAge(?string $dateTime): string
    {
        if (false === strtotime($dateTime)) {
            return $this->getLabel(0, 'unknown');
        }
        $ageInMinutes = round((time() - strtotime($dateTime)) / 60);
        $ageInHours = round($ageInMinutes / 60);
        $ageInDays = round($ageInHours / 24);
        $ageInWeeks = round($ageInDays / 7);
        $ageInMonths = round($ageInDays / 30);
        $ageInYears = round($ageInDays / 365);

        $opacity = $ageInMinutes > 1 ? round(0.05 + 0.95 / sqrt(1 + 0.0008 * $ageInMinutes), 2) : 1;
        if ($ageInYears > 0) {
            return $this->getLabel($ageInYears, $ageInYears == 1 ? 'year' : 'years', $opacity);
        }
        if ($ageInMonths > 0) {
            return $this->getLabel($ageInMonths, $ageInMonths == 1 ? 'month' : 'months', $opacity);
        }
        if ($ageInWeeks > 1) {
            return $this->getLabel($ageInWeeks, $ageInWeeks == 1 ? 'week' : 'weeks', $opacity);
        }
        if ($ageInDays > 0) {
            return $this->getLabel($ageInDays, $ageInDays == 1 ? 'day' : 'days', $opacity);
        }
        if ($ageInHours > 0) {
            return $this->getLabel($ageInHours, $ageInHours == 1 ? 'hour' : 'hours', $opacity);
        }
        if ($ageInMinutes > 1) {
            return $this->getLabel($ageInMinutes, $ageInMinutes == 1 ? 'minute' : 'minutes', $opacity);
        }
        return $this->getLabel(0, 'now');
    }

    private function getLabel($age, $labelSuffix, $opacity = 1): string
    {
        return sprintf(self::TEMPLATE, $opacity, $this->view->_('view.helper.dateAge.' . $labelSuffix, [$age]));
    }
}
