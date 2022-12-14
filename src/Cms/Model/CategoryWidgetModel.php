<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsCategoryWidgetCategoryQuery;

/**
 * Model widgetów kategorii
 */
class CategoryWidgetModel
{
    /**
     * Rekordy relacji widget - kategoria
     * @var \Cms\Orm\CmsCategoryWidgetCategoryRecord[]
     */
    private $_widgetCollection = [];

    /**
     * Identyfikator kategorii opakowującej widgety
     * @var integer
     */
    private $_categoryId;

    /**
     * Identyfikator relacji
     * @param integer $categoryId
     * @throws \Cms\Exception\CategoryWidgetException
     */
    public function __construct($categoryId)
    {
        //przypisanie kategorii opakowującej
        $this->_categoryId = $categoryId;
        //wyszukiwanie relacji
        if (null === $this->_widgetCollection = (new CmsCategoryWidgetCategoryQuery())
            ->join('cms_category')->on('cms_category_id')
            ->whereCmsCategoryId()->equals($this->_categoryId)
            ->andFieldWidget()->notEquals(null)
            ->orderAscOrder()
            ->find()) {
            //nie znaleziono relacji
            throw new \Cms\Exception\CategoryWidgetException('Category not found');
        }
    }

    /**
     * Pobiera rekordy relacji widget - kategoria
     * @return \Cms\Orm\CmsCategoryWidgetCategoryRecord[]
     */
    public function getWidgetRelations()
    {
        //zwrot kolekcji widgetów
        return $this->_widgetCollection;
    }

    /**
     * Pobiera rekordy relacji widget - kategoria po kluczu sekcji
     * @param string $sectionKey
     * @return \Cms\Orm\CmsCategoryWidgetCategoryRecord[]
     */
    public function getWidgetRelationsBySectionKey($sectionKey)
    {
        $filteredRelations = [];
        //iteracja po relacjach
        foreach ($this->_widgetCollection as $widgetRelation) {
            //porównanie klucza sekcji
            if ($sectionKey != substr($widgetRelation->widget, 0, strlen($sectionKey))) {
                continue;
            }
            $filteredRelations[] = $widgetRelation;
        }
        return $filteredRelations;
    }

    /**
     * Zlicza widgety po kluczu
     * @param string $widgetKey
     * @return integer
     */
    public function countWidgetRelationsByWidgetKey($widgetKey)
    {
        $count = 0;
        //iteracja po widgetach w sekcji
        foreach ($this->getWidgetRelationsBySectionKey($widgetKey) as $widgetRelationRecord) {
            if ($widgetKey == $widgetRelationRecord->widget) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Sortuje po tabeli order => $id
     * @param array $serial
     */
    public function sortBySerial(array $serial = [])
    {
        //iteracja po order => id
        foreach ($serial as $order => $id) {
            //wyszukanie rekordu (z obcięciem do kategorii)
            if (null === $record = (new CmsCategoryWidgetCategoryQuery())
                ->whereCmsCategoryId()->equals($this->_categoryId)
                ->findPk($id)) {
                continue;
            }
            //zapis ordera w rekordzie
            $record->order = $order;
            $record->save();
        }
    }
}
