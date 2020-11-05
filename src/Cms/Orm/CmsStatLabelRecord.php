<?php

namespace Cms\Orm;

use Mmi\App\App;

/**
 * Rekord opisu statystyki
 */
class CmsStatLabelRecord extends \Mmi\Orm\Record
{

    public $id;
    public $lang;
    public $object;
    public $label;
    public $description;

    /**
     * Zapis z językiem
     * @return boolean
     */
    protected function _insert()
    {
        $this->lang = App::$di->get(Request::class)->lang;
        return parent::_insert();
    }

}
