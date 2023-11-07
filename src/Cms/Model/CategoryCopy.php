<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Cms\Orm\CmsTagRelationRecord;
use Mmi\App\App;
use Mmi\Db\DbInterface;

/**
 * Model do kopiowania kategorii wraz z wszystkimi elementami zależnymi.
 */
class CategoryCopy
{
    /**
     * Obiekt kategorii Cms do skopiowania
     * @var \Cms\Orm\CmsCategoryRecord
     */
    protected $_category;

    /**
     * Obiekt nowo utworzonej kopii kategorii Cms
     * @var \Cms\Orm\CmsCategoryRecord
     */
    protected $_copy;

    /**
     * Sufiks dla nazwy kopiowanej kategorii
     * @var string
     */
    protected $_nameSuffix = '_kopia';

    /**
     * Konstruktor
     * @param \Cms\Orm\CmsCategoryRecord $category
     */
    public function __construct(\Cms\Orm\CmsCategoryRecord $category)
    {
        $this->_category = $category;
    }

    /**
     * Ustawia rekord kategorii do skopiowania
     * @param \Cms\Orm\CmsCategoryRecord $category
     * @return \Cms\Model\CategoryCopy
     */
    public function setCategory(\Cms\Orm\CmsCategoryRecord $category)
    {
        $this->_category = $category;
        return $this;
    }

    /**
     * Ustawia sufiks dla nazwy nowej kategorii
     * @param string $suffix
     * @return \Cms\Model\CategoryCopy
     */
    public function setNameSuffix($suffix)
    {
        $this->_nameSuffix = $suffix;
        return $this;
    }

    /**
     * Zwraca rekord skopiowanej kategorii
     * @return \Cms\Orm\CmsCategoryRecord
     */
    public function getCopyRecord()
    {
        return $this->_copy;
    }

    /**
     * Kopiuje kategorię z wszystkimki zależnościami
     * @return boolean
     */
    public function copy()
    {
        return $this->_copyAll();
    }

    /**
     * Kopiuje kategorię z wszystkimki zależnościami,
     * obejmując wszystko transakcją na bazie danych
     * @return boolean
     */
    public function copyWithTransaction()
    {
        //rozpoczęcie transakcji
        $db = App::$di->get(DbInterface::class);
        $db->beginTransaction();
        if ($this->_copyAll()) {
            //commit po transakcji
            $db->commit();
            return true;
        }
        //rollback
        $db->rollBack();
        return false;
    }

    /**
     * Czyści stan obiektu
     * @return \Cms\Model\CategoryCopy
     */
    protected function _clear()
    {
        $this->_copy = null;
        return $this;
    }

    /**
     * Kopiuje kategorię z wszystkimki zależnościami
     * @return boolean
     */
    protected function _copyAll()
    {
        $this->_clear();
        if (!$this->_copyCategory()) {
            return false;
        }
        if (!$this->_copyCategoryFiles()) {
            return false;
        }
        if (!$this->_copyWidgetRelations()) {
            return false;
        }
        if (!$this->_copyTagRelations()) {
            return false;
        }
        return true;
    }

    /**
     * Kopiuje rekord kategorii
     * @return boolean
     */
    protected function _copyCategory()
    {
        $this->_createCopyRecord();
        $this->_copy->cmsCategoryOriginalId = null;
        $this->_copy->status = \Cms\Orm\CmsCategoryRecord::STATUS_ACTIVE;
        return $this->_copy->save();
    }

    /**
     * Tworzy obiekt rekordu kopii kategorii - bez zapisu
     * @return \Cms\Orm\CmsCategoryRecord
     */
    protected function _createCopyRecord()
    {
        $this->_copy = new \Cms\Orm\CmsCategoryRecord();
        $this->_copy->setFromArray($this->_category->toArray());
        $this->_copy->id = null;
        $this->_copy->active = false;
        $this->_copy->dateAdd = null;
        $this->_copy->dateModify = null;
        $this->_copy->name = $this->_generateCategoryName();
        return $this->_copy;
    }

    /**
     * Generuje nazwę dla skopiowanej kategorii (unika kolizji URI)
     * @return string
     */
    protected function _generateCategoryName()
    {
        //filtr Url
        $filterUrl = new \Mmi\Filter\Url();
        //bazowe Uri skopiowanej kategorii na podstawie rodzica
        $baseUri = '';
        if ($this->_category->parentId && (null !== $parent = (new CmsCategoryQuery())->findPk($this->_category->parentId))) {
            //nieaktywny rodzic -> nie wlicza się do ścieżki
            if (!$parent->active) {
                $parent->uri = substr($parent->uri, 0, strrpos($parent->uri, '/'));
            }
            $baseUri = ltrim($parent->uri . '/', '/');
        }
        $baseName = $this->_category->name . $this->_nameSuffix;
        //unikamy kolizji URI - dodajemy pierwszą wolną liczbę na koniec
        $number = 0;
        do {
            $number++;
            $copyName = $baseName . (($number > 1) ? '_' . $number : '');
            $copyUri = $baseUri . $filterUrl->filter($copyName);
        } while ((new CmsCategoryQuery())->searchByUri($copyUri)->count());
        return $copyName;
    }

    /**
     * Kopiuje pliki powiązane z rekordem kategorii, np. wgrane przez TinyMce
     * @return boolean
     */
    protected function _copyCategoryFiles()
    {
        //jeśli rekord kopii jest niezapisany
        if (!$this->_copy->getPk()) {
            return false;
        }
        //dla każdego pliku powiązanego z kategorią
        foreach ((new \Cms\Orm\CmsFileQuery())
            ->whereObject()->like(CmsCategoryRecord::FILE_OBJECT . '%')
            ->andFieldObject()->notLike(CmsCategoryWidgetCategoryRecord::FILE_OBJECT . '%')
            ->andFieldObjectId()->equals($this->_category->id)
            ->findUnique('object') as $object) {
            \Cms\Model\File::link($object, $this->_category->id, $object, $this->_copy->getPk());
        }
        return true;
    }

    /**
     * Kopiuje pliki powiązane z widgetem, np. wgrane przez TinyMce
     * @param integer $relationId
     * @param \Cms\Orm\CmsCategoryWidgetCategoryRecord $newRelation
     * @return boolean
     */
    protected function _copyWidgetRelationFiles($relationId, \Cms\Orm\CmsCategoryWidgetCategoryRecord $newRelation)
    {
        //jeśli rekord relacji jest niezapisany
        if (!$newRelation->getPk()) {
            return false;
        }
        //kopiowanie plików ze starej relacji do nowej
        foreach ((new \Cms\Orm\CmsFileQuery())
            //obiekt podobny do categoryWidgetRelation
            ->whereObject()->like(CmsCategoryWidgetCategoryRecord::FILE_OBJECT . '%')
            ->andFieldObjectId()->equals($relationId)
            ->findUnique('object') as $object) {
            \Cms\Model\File::link($object, $relationId, $object, $newRelation->id);
        }
        return true;
    }

    /**
     * Kopiuje tagi
     * @param integer $relationId
     * @param \Cms\Orm\CmsCategoryWidgetCategoryRecord $newRelation
     * @return boolean
     */
    protected function _copyTagRelations()
    {
        //jeśli rekord kopii jest niezapisany
        if (!$this->_copy->getPk()) {
            return false;
        }
        //dla każdego pliku powiązanego z kategorią
        foreach ((new \Cms\Orm\CmsTagRelationQuery())
            ->whereObject()->like(CmsCategoryRecord::TAG_OBJECT . '%')
            ->andFieldObjectId()->equals($this->_category->id)
            ->find() as $tagRelation) {
            $newTagRelation = new CmsTagRelationRecord();
            $newTagRelation->cmsTagId = $tagRelation->cmsTagId;
            $newTagRelation->object = $tagRelation->object;
            $newTagRelation->objectId = $this->_copy->getPk();
            $newTagRelation->save();
        }
        return true;
    }

    /**
     * Kopiuje widgety kategorii
     * @return boolean
     */
    protected function _copyWidgetRelations()
    {
        //dla każdego widgetu
        foreach ((new \Cms\Model\CategoryWidgetModel($this->_category->id))->getWidgetRelations() as $widgetRelation) {
            //nowa relacja
            $relation = new \Cms\Orm\CmsCategoryWidgetCategoryRecord();
            $relation->uuid = $widgetRelation->uuid;
            $relation->widget = $widgetRelation->widget;
            $relation->configJson = $widgetRelation->configJson;
            $relation->order = $widgetRelation->order;
            $relation->active = $widgetRelation->active;
            $relation->cmsCategoryId = $this->_copy->id;
            if (!$relation->save()) {
                return false;
            }
            //kopiowanie plików powiązanych w widgetem
            if (!$this->_copyWidgetRelationFiles($widgetRelation->id, $relation)) {
                return false;
            }
        }
        return true;
    }
}
