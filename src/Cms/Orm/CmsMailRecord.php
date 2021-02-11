<?php

namespace Cms\Orm;

/**
 * Rekord listu mailowego
 */
class CmsMailRecord extends \Mmi\Orm\Record
{

    public $id;
    public $cmsMailDefinitionId;
    public $fromName;
    public $to;
    public $replyTo;
    public $subject;
    public $message;
    public $attachments;
    public $type;
    public $dateAdd;
    public $dateSent;
    public $dateSendAfter;
    public $active;

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
