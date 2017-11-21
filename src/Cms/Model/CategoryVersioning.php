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
class CategoryVersioning extends \Cms\Model\CategoryCopy
{
    
    /**
     * Sufiks dla nazwy wersionowanej kategorii
     * @var string
     */
    protected $_nameSuffix = '';
    
    /**
     * Wersjonuje kategorię z wszystkimki zależnościami
     * @return boolean
     */
    public function versionig()
    {
        return parent::copy();
    }
    
    /**
     * Wersjonuje kategorię z wszystkimki zależnościami,
     * obejmując wszystko transakcją na bazie danych
     * @return boolean
     */
    public function versioningWithTransaction()
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
