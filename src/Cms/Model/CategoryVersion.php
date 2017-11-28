<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

/**
 * Model do zapisu wersji kategorii wraz z wszystkimi elementami zależnymi.
 */
class CategoryVersion extends \Cms\Model\CategoryDraft
{

    /**
     * Sufiks dla nazwy wersji roboczej kategorii
     * @var string
     */
    protected $_nameSuffix = '';

    /**
     * Kopiuje rekord kategorii
     * @return boolean
     */
    protected function _copyCategory()
    {
        $this->_createCopyRecord();
        $this->_copy->active = $this->_category->active;
        $this->_copy->cmsCategoryOriginalId = $this->_category->getPk();
        $this->_copy->status = \Cms\Orm\CmsCategoryRecord::STATUS_HISTORY;
        return $this->_copy->save();
    }

    /**
     * Zamienia oryginał
     * @param \Cms\Orm\CmsCategoryRecord $draft
     * @return boolean
     */
    public function exchangeOriginal(\Cms\Orm\CmsCategoryRecord $draft)
    {
        //usuwanie plików
        (new \Cms\Orm\CmsFileQuery)->whereObject()->equals(self::FILE_CATEGORY_OBJECT)
            ->andFieldObjectId()->equals($this->_category->getPk())
            ->find()
            ->delete();
        //usuwanie widgetów
        (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryId()->equals($this->_category->getPk())
            ->find()
            ->delete();
        //czyszczenie atrybutów
        (new \Cms\Orm\CmsAttributeRelationQuery)->whereObject()->equals(self::OBJECT_TYPE)
            ->andFieldObjectId()->equals($this->_category->getPk())
            ->find()
            ->delete();
        //nadpisanie danych oryginału
        $this->_category->setFromArray($draft->toArray());
        //id pozostaje niezmienione
        $this->_category->id = $this->_category->getInitialStateValue('id');
        //status active
        $this->_category->status = \Cms\Orm\CmsCategoryRecord::STATUS_ACTIVE;
        //czyszczenie id oryginału
        $this->_category->cmsCategoryOriginalId = null;
        //przenoszenie plików
        \Cms\Model\File::move(self::FILE_CATEGORY_OBJECT, $draft->id, self::FILE_CATEGORY_OBJECT, $this->_category->id);
        //przepinanie widgetów
        \Mmi\Orm\DbConnector::getAdapter()->update('cms_category_widget_category', ['cms_category_id' => $this->_category->id], 'WHERE cms_category_id = :id', [':id' => $draft->id]);
        //przepinanie atrybutów
        \Mmi\Orm\DbConnector::getAdapter()->update('cms_attribute_value_relation', ['objectId' => $this->_category->id], 'WHERE objectId = :id AND object = :object', [':id' => $draft->id, ':object' => self::OBJECT_TYPE]);
        //usuwanie draftu
        $draft->delete();
        return $this->_category->save();
    }

}
