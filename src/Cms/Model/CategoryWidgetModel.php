<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use \Cms\Orm\CmsCategoryWidgetCategoryQuery;

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
     */
    public function __construct($categoryId)
    {
        //przypisanie kategorii opakowującej
        $this->_categoryId = $categoryId;
        //wyszukiwanie relacji
        if (null === $this->_widgetCollection = (new CmsCategoryWidgetCategoryQuery)
            ->join('cms_category')->on('cms_category_id')
            ->join('cms_category_widget')->on('cms_category_widget_id')
            ->whereCmsCategoryId()->equals($this->_categoryId)
            ->orderAscOrder()
            ->find()) {
            //nie znaleziono relacji
            throw new \Cms\Exception\CategoryWidgetException('Category not found');
        }
    }

    /**
     * Pobiera rekord konfiguracji widgeta
     * @param integer $id
     * @return \Cms\Orm\CmsCategoryWidgetCategoryRecord
     */
    public function findWidgetRelationById($id)
    {
        //iteracja po relacjach
        foreach ($this->_widgetCollection as $widgetRelationRecord) {
            //relacja odnaleziona
            if ($widgetRelationRecord->id == $id) {
                return $widgetRelationRecord;
            }
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
     * Sortuje po tabeli order => $id
     * @param array $serial
     */
    public function sortBySerial(array $serial = [])
    {
        //iteracja po order => id
        foreach ($serial as $order => $id) {
            //wyszukanie rekordu (z obcięciem do kategorii)
            if (null === $record = (new CmsCategoryWidgetCategoryQuery)
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
