<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsCategoryRecord;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;

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
        //usuwanie plików tinymce i uploaderów
        (new \Cms\Orm\CmsFileQuery)
            ->whereQuery((new \Cms\Orm\CmsFileQuery)
                ->whereObject()->like(CmsCategoryRecord::FILE_OBJECT . '%')
                ->andFieldObject()->notLike(CmsCategoryWidgetCategoryRecord::FILE_OBJECT . '%')
            )
            ->andFieldObjectId()->equals($this->_category->id)
            ->find()
            ->delete();
        //usuwanie widgetów
        (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryId()->equals($this->_category->id)
            ->find()
            ->delete();
        //nadpisanie danych oryginału
        $this->_category->setFromArray($draft->toArray());
        //id, parent, order i path pozostają niezmienione
        $this->_category->id = $this->_category->getInitialStateValue('id');
        $this->_category->parentId = $this->_category->getInitialStateValue('parentId');
        $this->_category->path = $this->_category->getInitialStateValue('path');
        $this->_category->order = $this->_category->getInitialStateValue('order');
        //status active
        $this->_category->status = \Cms\Orm\CmsCategoryRecord::STATUS_ACTIVE;
        //czyszczenie id oryginału
        $this->_category->cmsCategoryOriginalId = null;
        //przenoszenie plików
        \Cms\Model\File::move(CmsCategoryRecord::FILE_OBJECT, $draft->id, CmsCategoryRecord::FILE_OBJECT, $this->_category->id);
        //przepinanie widgetów
        foreach ((new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryId()->equals($draft->id)
            ->find() as $widgetCategory) {
            //przepinanie id
            $widgetCategory->cmsCategoryId = $this->_category->id;
            $widgetCategory->save();
        }
        //przepinanie plików
        foreach ((new \Cms\Orm\CmsFileQuery)
            //tinymce i uploadery
            ->whereQuery((new \Cms\Orm\CmsFileQuery)
                ->whereObject()->like(CmsCategoryRecord::FILE_OBJECT . '%')
                ->andFieldObject()->notLike(CmsCategoryWidgetCategoryRecord::FILE_OBJECT . '%')
            )
            ->andFieldObjectId()->equals($draft->id)
            ->find() as $file) {
            //przepinanie id
            $file->objectId = $this->_category->id;
            $file->save();
        }
        //synchronizacja ról
        $this->_synchronizeRoles($draft);
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

}
