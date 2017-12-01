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
 * Model do zapisu wersji roboczej kategorii wraz z wszystkimi elementami zależnymi.
 */
class CategoryDraft extends \Cms\Model\CategoryCopy
{
    
    /**
     * Sufiks dla nazwy wersji roboczej kategorii
     * @var string
     */
    protected $_nameSuffix = '';
    
    /**
     * Tworzy wersję roboczą kategorii z wszystkimki zależnościami
     * @return boolean
     */
    public function create()
    {
        return parent::copy();
    }
    
    /**
     * Tworzy wersję roboczą kategorii z wszystkimki zależnościami,
     * obejmując wszystko transakcją na bazie danych
     * @return boolean
     */
    public function createWithTransaction()
    {
        return parent::copyWithTransaction();
    }
    
    /**
     * Kopiuje rekord kategorii
     * @return boolean
     */
    protected function _copyCategory()
    {
        $this->_createCopyRecord();
        $this->_copy->active = $this->_category->active;
        //nadawanie id oryginału (chyba że już nadany w przypadku rekordów z historii)
        $this->_copy->cmsCategoryOriginalId = $this->_category->cmsCategoryOriginalId ? $this->_category->cmsCategoryOriginalId : $this->_category->getPk();
        $this->_copy->status = \Cms\Orm\CmsCategoryRecord::STATUS_DRAFT;
        return $this->_copy->save();
    }
    
    /**
     * Generuje nazwę dla wersionowanej kategorii
     * @return string
     */
    protected function _generateCategoryName()
    {
        return $this->_category->name . $this->_nameSuffix;
    }

}
