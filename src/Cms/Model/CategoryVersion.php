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
        //ustawienie daty dodania na oryginalną kategorię
        $this->_copy->dateAdd = $this->_category->dateAdd;
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
        //reset rodzica i kolejności (gdyż draft może mieć nieaktualną)
        $this->_category->parentId = $this->_category->getInitialStateValue('parentId');
        $this->_category->order = $this->_category->getInitialStateValue('order');
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
        //synchronizacja ról
        $this->_synchronizeRoles($draft);
        //synchronizacja tagów
        $this->_synchronizeTags($draft);
        //usuwanie draftu
        $draft->delete();
        $this->_category->dateAdd = date('Y-m-d H:i:s');
        return $this->_category->save();
    }

    /**
     * Synchronizuje powiązania kategorii z rolami
     * @param \Cms\Orm\CmsCategoryRecord $draft
     * @return bool
     */
    protected function _synchronizeRoles(\Cms\Orm\CmsCategoryRecord $draft)
    {
        //role zaznaczone w draft
        $draftRoles = (new \Cms\Orm\CmsCategoryRoleQuery)
            ->whereCmsCategoryId()->equals($draft->getPk())
            ->findUnique('cms_role_id');
        //role zapisane w bazie
        $savedRoles = (new \Cms\Orm\CmsCategoryRoleQuery)
            ->whereCmsCategoryId()->equals($this->_category->getPk())
            ->findUnique('cms_role_id');
        //usuwanie zbędnych
        $this->_deleteRoles(array_diff($savedRoles, $draftRoles));
        //wstawianie brakujących
        $this->_insertRoles(array_diff($draftRoles, $savedRoles));
        return true;
    }

    /**
     * Usuwa zbędne powiązania kategorii z rolami
     * @param array $delete
     * @return bool
     */
    protected function _deleteRoles(array $delete = [])
    {
        if (empty($delete)) {
            return true;
        }
        return count($delete) === (new \Cms\Orm\CmsCategoryRoleQuery)
                ->whereCmsCategoryId()->equals($this->_category->getPk())
                ->andFieldCmsRoleId()->equals($delete)
                ->find()->delete();
    }

    /**
     * Wstawia brakujące powiązania kategorii z rolami
     * @param array $insert
     * @return bool
     */
    protected function _insertRoles(array $insert = [])
    {
        foreach ($insert as $roleId) {
            $record = new \Cms\Orm\CmsCategoryRoleRecord();
            $record->cmsCategoryId = $this->_category->getPk();
            $record->cmsRoleId = $roleId;
            $record->save();
        }
        return true;
    }

    /**
     * Synchronizuje powiązania kategorii i jej atrybutów z tagami
     * @param \Cms\Orm\CmsCategoryRecord $draft
     * @return bool
     */
    protected function _synchronizeTags(\Cms\Orm\CmsCategoryRecord $draft)
    {
        //usuwamy tagi przypisane do oryginału
        $this->_getTagRelationQuery($this->_category->getPk())
            ->find()
            ->delete();
        //przepisujemy tagi z draftu do oryginału
        foreach ($this->_getTagRelationQuery($draft->id)
            ->find() as $tag) {
            $tag->objectId = $this->_category->getPk();
            if (!$tag->save()) {
                return false;
            }
        }
        return true;
    }

}
