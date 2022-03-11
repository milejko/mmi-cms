<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use \Mmi\Orm\CacheRecord,
    \Mmi\Orm\CacheQuery;

/**
 * Model blokad zapisu treści
 */
class CategoryLockModel
{

    //prefix w buforze
    const CACHE_PREFIX = 'category-lock';
    //czas blokady (na transakcję)
    const LOCK_TIMEOUT = 15;
    //dodatkowa blokada po zapisie
    const RELEASE_TIMEOUT = 3;

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
    public function lock()
    {
        //wyszukiwanie blokady dla kategorii
        if (null === $lockRecord = (new CacheQuery)->findPk($lockKey = self::CACHE_PREFIX . $this->_categoryId)) {
            $lockRecord = new CacheRecord;
            $lockRecord->id = $lockKey;
            $lockRecord->data = true;
        }
        //brak możliwości założenia blokady
        if ($lockRecord->ttl > time()) {
            return false;
        }
        //zakładanie blokady
        $lockRecord->ttl = time() + self::LOCK_TIMEOUT;
        return $lockRecord->save();
    }

    /**
     * Zwalnia blokadę
     * @return boolean
     */
    public function releaseLock()
    {
        //wyszukiwanie blokady dla kategorii
        if (null === $lockRecord = (new CacheQuery)->findPk($lockKey = self::CACHE_PREFIX . $this->_categoryId)) {
            //brak blokady
            return true;
        }
        //zwalnianie blokady z opóźnieniem
        $lockRecord->ttl = time() + self::RELEASE_TIMEOUT;
        return $lockRecord->save();
    }

}
