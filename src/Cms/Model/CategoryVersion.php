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
        $this->_copy->cmsCategoryOriginalId = $this->_category->id;
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
        //usuwanie plików tinymce i z atrybutów
        (new \Cms\Orm\CmsFileQuery)
            //tinymce
            ->orQuery((new \Cms\Orm\CmsFileQuery)->whereObject()->equals(self::FILE_CATEGORY_OBJECT)
                ->andFieldObjectId()->equals($this->_category->id))
            //atrybuty like 'category%' ale bez 'categoryWidgetRelation%'
            ->orQuery((new \Cms\Orm\CmsFileQuery)->whereObject()->like(self::OBJECT_TYPE . '%')
                ->andFieldObject()->notLike(self::CATEGORY_WIDGET_RELATION . '%')
                ->andFieldObjectId()->equals($this->_category->id))
            ->find()
            ->delete();
        //usuwanie widgetów
        (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryId()->equals($this->_category->id)
            ->find()
            ->delete();
        $attributeValueRelationModel = new AttributeValueRelationModel(self::OBJECT_TYPE, $this->_category->id);
        $attributeValueRelationModel->deleteAttributeValueRelations();
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
        foreach ((new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryId()->equals($draft->id)
            ->find() as $widgetCategory) {
            //przepinanie id
            $widgetCategory->cmsCategoryId = $this->_category->id;
            $widgetCategory->save();
        }
        //przepinanie atrybutów
        foreach ((new \Cms\Orm\CmsAttributeValueRelationQuery)
            ->whereObject()->equals(self::OBJECT_TYPE)
            ->whereObjectId()->equals($draft->id)
            ->find() as $attributeRelation) {
            //przepinanie id
            $attributeRelation->objectId = $this->_category->id;
            $attributeRelation->save();
        }
        //przepinanie plików
        foreach ((new \Cms\Orm\CmsFileQuery)
            //tinymce
            ->orQuery((new \Cms\Orm\CmsFileQuery)->whereObject()->equals(self::FILE_CATEGORY_OBJECT)
                ->andFieldObjectId()->equals($draft->id))
            //atrybuty like 'category%' ale bez 'categoryWidgetRelation%'
            ->orQuery((new \Cms\Orm\CmsFileQuery)->whereObject()->like(self::OBJECT_TYPE . '%')
                ->andFieldObject()->notLike(self::CATEGORY_WIDGET_RELATION . '%')
                ->andFieldObjectId()->equals($draft->id))
            ->find() as $file) {
            //przepinanie id
            $file->objectId = $this->_category->id;
            $file->save();
        }
        //usuwanie draftu
        $draft->delete();
        return $this->_category->save();
    }

}
