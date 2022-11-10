<?php

namespace Cms\Orm;

/**
 * Rekord harmonogramu
 */
class CmsCronRecord extends \Mmi\Orm\Record
{
    /**
     * Identyfikator
     * @var integer
     */
    public $id;
    public $active;
    public $lock;

    /**
     * Minuta
     * @var integer
     */
    public $minute;

    /**
     * Godzina
     * @var string
     */
    public $hour;

    /**
     * Dzień miesiąca
     * @var type
     */
    public $dayOfMonth;

    /**
     * Miesiąc
     * @var integer
     */
    public $month;

    /**
     * Dzień tygodnia
     * @var integer
     */
    public $dayOfWeek;
    public $name;
    public $description;
    public $module;
    public $controller;
    public $action;
    public $message;
    public $dateAdd;
    public $dateModified;

    /**
     * Data ostatniego uruchomienia
     * @var string
     */
    public $dateLastExecute;

    /**
     * Zapis rekordu
     * @return boolean
     */
    public function save()
    {
        $this->dateModified = date('Y-m-d H:i:s');
        return parent::save();
    }

    /**
     * Blokuje rekord
     * @return boolean
     */
    public function lock()
    {
        $this->lock = 1;
        $this->dateLastExecute = date('Y-m-d H:i:s');
        return parent::save();
    }

    /**
     * Odblokowuje rekord po wykonaniu
     * @return boolean
     */
    public function unlockAfterExecution()
    {
        $this->lock = 0;
        return parent::save();
    }

    /**
     * Wstawienie rekordu
     * @return boolean
     */
    protected function _insert()
    {
        $this->dateAdd = date('Y-m-d H:i:s');
        return parent::_insert();
    }
}
