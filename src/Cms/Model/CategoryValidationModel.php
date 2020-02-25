<?php

namespace Cms\Model;

use Cms\App\CmsSkinsetConfig;
use Cms\Orm\CmsCategoryRecord;

class CategoryValidationModel
{

    /**
     * Klucze widgetów koniecznych jeszcze do dodania
     * @var array
     */
    private $_minOccurrenceKeys = [];

    /**
     * Klucze widgetów których już nie można dodać
     * @var array
     */
    private $_maxOccurrenceKeys = [];

    /**
     * Konstruktor
     * @param CmsCategoryRecord $categoryRecord
     */
    public function __construct(CmsCategoryRecord $categoryRecord, CmsSkinsetConfig $skinsetConfig)
    {
        $widgetModel = $categoryRecord->getWidgetModel();
        //iteracja po sekcjach szablonu
        foreach ((new SkinsetModel($skinsetConfig))->getSectionsByKey($categoryRecord->template) as $section) {
            //iteracja po dostępnych widgetach
            foreach ($section->getAvailableWidgets() as $key => $widget) {
                $widgetCount = $widgetModel->countWidgetRelationsByWidgetKey($key);
                //osiągnięto max dla widgeta
                if ($widgetCount >= $widget->getMaxOccurrence()) {
                    $this->_maxOccurrenceKeys[] = $key;
                }
                //nie osiągnięto min dla widgeta
                if ($widgetCount < $widget->getMinOccurrence()) {
                    $this->_minOccurrenceKeys[] = $key;
                }
            }
        }
    }

    /**
     *
     * @return integer
     */
    public function getMaxOccurenceWidgets()
    {
        return $this->_maxOccurrenceKeys;
    }

    /**
     *
     * @return integer
     */
    public function getMinOccurenceWidgets()
    {
        return $this->_minOccurrenceKeys;    
    }

}