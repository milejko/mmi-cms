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
    //maksymalny czas życia draftu (format strtotime)
    public const DRAFT_MAX_LIFETIME = '-48 hours';

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
     * @return \Cms\Orm\CmsCategoryRecord
     */
    public function createAndGetDraftForUser($userId)
    {
        //próba kopiowania
        if (!$this->copyWithTransaction()) {
            return;
        }
        //pobranie drafta
        $draft = $this->getCopyRecord();
        //przypinanie użytkownika do draftu
        $draft->cmsAuthId = $userId;
        $draft->dateAdd = date('Y-m-d H:i:s');
        $draft->save();
        //zwrot drafta
        return $draft;
    }

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
