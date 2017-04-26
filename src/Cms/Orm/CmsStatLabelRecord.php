<?php

namespace Cms\Orm;

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
        $this->lang = \Mmi\App\FrontController::getInstance()->getRequest()->lang;
        return parent::_insert();
    }

}
