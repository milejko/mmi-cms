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

/**
 * Model do kopiowania kategorii wraz z wszystkimi elementami zależnymi.
 */
class CategoryCopy
{

    CONST OBJECT_TYPE = 'category';
    CONST CATEGORY_WIDGET_RELATION = 'categoryWidgetRelation';
    CONST CMS_ATTRIBUTE_TYPE = 'cms_attribute_type';
    CONST FILE_CATEGORY_OBJECT = 'cmscategory';
    CONST FILE_CATEGORY_WIDGET_OBJECT = 'cmscategorywidgetcategory';
    
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
     * Mapowanie plików powiązanych z kategorią: oryginałów na kopie
     * @var array
     */
    private $_categoryFiles = [];
    
    /**
     * Mapowanie plików powiązanych z widgetem: oryginałów na kopie
     * @var array
     */
    private $_categoryWidgetFiles = [];

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
     * Czyści stan obiektu
     * @return \Cms\Model\CategoryCopy
     */
    protected function _clear()
    {
        $this->_copy = null;
        $this->_categoryFiles = [];
        $this->_categoryWidgetFiles = [];
        return $this;
    }
    
    /**
     * Kopiuje kategorię z wszystkimki zależnościami
     * @return boolean
     */
    protected function _copyAll()
    {
        $this->_clear();
        try {
            if (!$this->_copyCategory()) {
                return false;
            }
            if (!$this->_copyCategoryRoles()) {
                return false;
            }
            if (!$this->_copyCategoryFiles()) {
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
        $filterUrl = new \Mmi\Filter\Url;
        //bazowe Uri skopiowanej kategorii na podstawie rodzica
        $baseUri = '';
        if ($this->_category->parentId && (null !== $parent = (new CmsCategoryQuery)->findPk($this->_category->parentId))) {
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
        } while((new CmsCategoryQuery)->searchByUri($copyUri)->count());
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
        foreach (\Cms\Orm\CmsFileQuery::byObject(self::FILE_CATEGORY_OBJECT, $this->_category->getPk())
                ->find() as $original) {
            if (null === $copy = \Cms\Model\File::copyWithData($original, $this->_copy->getPk())) {
                return false;
            }
            //zapamiętujemy mapowanie plików
            array_push($this->_categoryFiles, ['original' => $original, 'copy' => $copy]);
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
        $this->_categoryWidgetFiles = [];
        //jeśli rekord relacji jest niezapisany
        if (!$newRelation->getPk()) {
            return false;
        }
        //dla każdego pliku powiązanego z widgetem
        foreach (\Cms\Orm\CmsFileQuery::byObject(self::FILE_CATEGORY_WIDGET_OBJECT, $relationId)
                ->find() as $original) {
            if (null === $copy = \Cms\Model\File::copyWithData($original, $newRelation->getPk())) {
                return false;
            }
            //zapamiętujemy mapowanie plików
            array_push($this->_categoryWidgetFiles, ['original' => $original, 'copy' => $copy]);
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
        foreach ($this->_category->getWidgetModel()->getWidgetRelations() as $widgetRelation) {
            //nowa relacja
            $relation = new \Cms\Orm\CmsCategoryWidgetCategoryRecord();
            $relation->setFromArray($widgetRelation->toArray());
            $relation->id = null;
            $relation->cmsCategoryId = $this->_copy->id;
            if (!$relation->save()) {
                return false;
            }
            
            //kopiowanie plików powiązanych w widgetem
            if (!$this->_copyWidgetRelationFiles($widgetRelation->id, $relation)) {
                return false;
            }

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
                    ->createAttributeValueRelationByValue($attribute->id, $this->_updateValue($value, $this->_categoryWidgetFiles));
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
                ->createAttributeValueRelationByValue($record->cmsAttributeId, $this->_updateValue($record->value, $this->_categoryFiles));
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
    
    /**
     * Aktualizuje wartość - zamienia ścieżki do plików
     * @param string $value
     * @param array $filesMap mapowanie plików
     * @return string
     */
    protected function _updateValue($value, $filesMap = [])
    {
        //dla każdej pozycji z mapy
        foreach ($filesMap as $map) {
            if (!isset($map['original']) || !isset($map['copy'])) {
                continue;
            }
            $oName = $map['original']->name;
            $cName = $map['copy']->name;
            $value = preg_replace('@/data/'.$oName[0].'/'.$oName[1].'/'.$oName[2].'/'.$oName[3].'/(scalecrop|scalex|scaley|default)/([0-9x]{0,10})/'.$oName.'@',
                '/data/'.$cName[0].'/'.$cName[1].'/'.$cName[2].'/'.$cName[3].'/$1/$2/'.$cName, $value);
        }
        return $value;
    }
    
    /**
     * Kopiuje powiązania roli z rekordem kategorii
     * @return boolean
     */
    protected function _copyCategoryRoles()
    {
		//role zapisane w bazie
		$roles = (new \Cms\Orm\CmsCategoryRoleQuery)
				->whereCmsCategoryId()->equals($this->_category->getPk())
				->findUnique('cms_role_id');
		foreach ($roles as $roleId) {
			$record = new \Cms\Orm\CmsCategoryRoleRecord();
			$record->cmsCategoryId = $this->_copy->getPk();
			$record->cmsRoleId = $roleId;
			if (!$record->save()) {
				return false;
			}
		}
		return true;
    }

}
