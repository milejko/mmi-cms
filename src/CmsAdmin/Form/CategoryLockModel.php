<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Mmi\Orm\CacheRecord;
use Mmi\Orm\CacheQuery;

/**
 * Model blokad zapisu treści
 */
class CategoryLockModel
{
    //prefix w buforze
    public const CACHE_PREFIX = 'category-lock-';
    //czas blokady (maksymalny czas przeznaczony na zapis) - domyślnie 5 minut
    public const LOCK_TIMEOUT = 300;
    //dodatkowy czas po transakcji (propagacja po klastrze itp.) - domyślnie 2 sekundy
    public const RELEASE_TIMEOUT = 2;

    /**
     * Identyfikator kategorii
     * @var integer
     */
    private $_categoryId;

    /**
     * Konstruktor
     * @param integer $categoryId
     */
    public function __construct($categoryId)
    {
        //przypisanie ID kategorii
        $this->_categoryId = $categoryId;
    }

    /**
     * Zakłada blokadę
     * @return boolean
     */
    public function lock(): void
    {
        $this->addLockRecord(time() + self::LOCK_TIMEOUT);
    }

    public function release(): void
    {
        $this->addLockRecord(time() + self::RELEASE_TIMEOUT);
    }

    public function isLocked(): bool
    {
        $lockRecord = $this->getLockRecord();
        if (null === $lockRecord) {
            return false;
        }
        //blokada ciągle aktywna jeśli czas jest przed ttl
        return time() < $lockRecord->ttl;
    }

    private function addLockRecord(int $ttl): void
    {
        //wyszukiwanie blokady dla kategorii
        $lockRecord = $this->getLockRecord();
        if (null === $lockRecord) {
            $lockRecord = new CacheRecord();
            $lockRecord->id = $this->getLockRecordId();
            $lockRecord->data = true;
        }
        //zakładanie blokady
        $lockRecord->ttl = $ttl;
        $lockRecord->save();
    }

    private function getLockRecord(): ?CacheRecord
    {
        return (new CacheQuery())->findPk($this->getLockRecordId());
    }

    private function getLockRecordId(): string
    {
        return self::CACHE_PREFIX . $this->_categoryId;
    }
}
