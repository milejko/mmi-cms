<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Mvc\ViewHelper;

use CmsAdmin\Model\DateAgeException;
use CmsAdmin\Model\DateAgeModel;
use Mmi\Mvc\ViewHelper\HelperAbstract;

class DateAge extends HelperAbstract
{
    private const SPAN_PATTERN = '<span style="opacity: %s;">%s</span>';

    public function dateAge(?string $dateTime): string
    {
        try {
            $dateAgeModel = new DateAgeModel($dateTime);
        } catch (DateAgeException $e) {
            return $this->getLabel(null);
        }
        return $this->getLabel($dateAgeModel);
    }

    private function getLabel(?DateAgeModel $dateAgeModel): string
    {
        $age = 0;
        switch ($dateAgeModel->getAgeRange()) {
            case DateAgeModel::YEAR_SCALE:
                $age = $dateAgeModel->getAgeInYears();
                break;
            case DateAgeModel::MONTH_SCALE:
                $age = $dateAgeModel->getAgeInMonths();
                break;
            case DateAgeModel::WEEK_SCALE:
                $age = $dateAgeModel->getAgeInWeeks();
                break;
            case DateAgeModel::DAY_SCALE:
                $age = $dateAgeModel->getAgeInDays();
                break;
            case DateAgeModel::HOUR_SCALE:
                $age = $dateAgeModel->getAgeInHours();
                break;
            case DateAgeModel::MINUTE_SCALE:
                $age = $dateAgeModel->getAgeInMinutes();
                break;
        }
        if (0 == $dateAgeModel->getAgeInMinutes()) {
            return sprintf(self::SPAN_PATTERN, 1, $this->view->_('view.helper.dateAge.now'));
        }
        return sprintf(self::SPAN_PATTERN, $this->calculateOpacity($dateAgeModel->getAgeInMinutes()), $this->view->_('view.helper.dateAge.' . $dateAgeModel->getAgeRange() . ($age > 1 ? 's' : ''), [$age]));
    }

    private function calculateOpacity(int $ageInMinutes): float
    {
        return $ageInMinutes > 1 ? round(0.05 + 0.95 / sqrt(1 + 0.0008 * $ageInMinutes), 2) : 1;
    }
}
