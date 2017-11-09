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
 * Model do kopiowania kategorii wraz z wszystkimi elementami zależnymi.
 */
class CategoryCopy
{

    CONST OBJECT_TYPE = 'category';
    CONST CATEGORY_WIDGET_RELATION = 'categoryWidgetRelation';
    CONST CMS_ATTRIBUTE_TYPE = 'cms_attribute_type';
    
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
		\App\Registry::$db->beginTransaction();
        if ($this->_copyAll()) {
            //commit po transakcji
            \App\Registry::$db->commit();
            return true;
        }
        //rollback
        \App\Registry::$db->rollBack();
        return false;
    }
    
    /**
     * Kopiuje kategorię z wszystkimki zależnościami
     * @return boolean
     */
    protected function _copyAll()
    {
        try {
            if (!$this->_copyCategory()) {
                return false;
            }
            if (!$this->_copyWidgetRelations()) {
                return false;
            }
            if (!$this->_copyAttributeValues()) {
                return false;
            }
        } catch (\Exception $ex) {
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
        $this->_copy = new \Cms\Orm\CmsCategoryRecord();
        $this->_copy->setFromArray($this->_category->toArray());
        $this->_copy->id = null;
        $this->_copy->name .= $this->_nameSuffix;
        $this->_copy->active = false;
        $this->_copy->dateAdd = null;
        $this->_copy->dateModify = null;
        return $this->_copy->save();
    }
    
    /**
     * Kopiuje widgety kategorii
     * @return boolean
     */
    protected function _copyWidgetRelations()
    {
        foreach ($this->_category->getWidgetModel()->getWidgetRelations() as $widgetRelation) {
            $relation = new \Cms\Orm\CmsCategoryWidgetCategoryRecord();
            $relation->setFromArray($widgetRelation->toArray());
            $relation->id = null;
            $relation->cmsCategoryId = $this->_copy->id;
            if (!$relation->save()) {
                return false;
            }

            (new AttributeValueRelationModel(self::CATEGORY_WIDGET_RELATION, $relation->id))
                ->deleteAttributeValueRelations();

            $relationAttributes = $widgetRelation->getAttributeValues();
            foreach ($relationAttributes as $key => $value) {
                $attribute = (new \Cms\Orm\CmsAttributeQuery)->withTypeByKey($key)->findFirst();
                if ($attribute->getJoined(self::CMS_ATTRIBUTE_TYPE)->uploader) {
                    foreach ($value as $file) {
                        \Cms\Model\File::copyWithData($file, $relation->id);
                    }
                    (new AttributeValueRelationModel(self::CATEGORY_WIDGET_RELATION, $relation->id))
                        ->createAttributeValueRelationByValue($attribute->id, self::CATEGORY_WIDGET_RELATION . ucfirst($key));
                    continue;
                }
                (new AttributeValueRelationModel(self::CATEGORY_WIDGET_RELATION, $relation->id))
                    ->createAttributeValueRelationByValue($attribute->id, $value);
            }
        }
        return true;
    }
    
    /**
     * Kopiuje atrybuty kategorii
     * @return boolean
     */
    protected function _copyAttributeValues()
    {
        //zrodlowe wartosci atrybutów
        $sourceAttributeValues = (new \Cms\Model\AttributeValueRelationModel(self::OBJECT_TYPE, $this->_category->id))->getAttributeValues();
        //iteracja po atrybutach
        foreach ($sourceAttributeValues as $record) {
            //tworze relacje atrybutu dla nowej kategorii
            (new \Cms\Model\AttributeValueRelationModel(self::OBJECT_TYPE, $this->_copy->id))
                ->createAttributeValueRelationByValue($record->cmsAttributeId, $record->value);
            //jesli uploader to kopiuje też pliki z danymi
            if ($record->getJoined(self::CMS_ATTRIBUTE_TYPE)->uploader) {
                $files = \Cms\Orm\CmsFileQuery::byObject($record->value, $this->_category->id)->find();
                foreach ($files as $file) {
                    \Cms\Model\File::copyWithData($file, $this->_copy->id);
                }
            }
        }
        return true;
    }

}
