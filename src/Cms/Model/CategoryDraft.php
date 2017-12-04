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
        $result = parent::copy();
        //ustawienie daty dodania na oryginalną kategorię
        $this->getCopyRecord()->dateAdd = $this->_category->dateAdd;
        return $result && $this->getCopyRecord()->save();
    }

    /**
     * Tworzy wersję roboczą kategorii z wszystkimki zależnościami,
     * obejmując wszystko transakcją na bazie danych
     * @return \Cms\Orm\CmsCategoryRecord
     */
    public function createAndGetDraftForUser($userId, $force = false)
    {
        //wyszukiwanie najnowszego draftu (chyba że wymuszony nowy)
        if (!$force && null !== $lastDraft = (new \Cms\Orm\CmsCategoryQuery)
            ->whereCmsCategoryOriginalId()->equals($this->_category->id)
            ->andFieldStatus()->equals(\Cms\Orm\CmsCategoryRecord::STATUS_DRAFT)
            ->andFieldCmsAuthId()->equals($userId)
            ->orderDescId()
            ->findFirst()) {
            //czyszczenie starych draftów
            $this->_gc($userId, $lastDraft->id);
            //zwrot ostatniego draftu
            return $lastDraft;
        }
        //próba kopiowania
        if (!parent::copyWithTransaction()) {
            return;
        }
        //pobranie drafta
        $draft = $this->getCopyRecord();
        //przypinanie użytkownika do draftu
        $draft->cmsAuthId = $userId;
        $draft->save();
        //czyszczenie starych draftów
        $this->_gc($userId, $draft->id);
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

    /**
     * Metoda usuwa przestarzałe drafty użytkownika
     * zachowując bieżący (nowy lub wczytany)
     * @param integer $userId
     * @param integer $currentDraftId
     */
    protected function _gc($userId, $currentDraftId)
    {
        //usuwanie starych śmieci (GC)
        (new \Cms\Orm\CmsCategoryQuery)
            ->whereCmsCategoryOriginalId()->equals($this->_category->cmsCategoryOriginalId ? $this->_category->cmsCategoryOriginalId : $this->_category->id)
            ->whereQuery((new \Cms\Orm\CmsCategoryQuery)
                ->whereCmsAuthId()->equals($userId)
                ->orFieldCmsAuthId()->equals(null)
            )
            ->andFieldId()->notEquals($currentDraftId)
            ->andFieldStatus()->equals(\Cms\Orm\CmsCategoryRecord::STATUS_DRAFT)
            ->find()
            ->delete();
    }

}
