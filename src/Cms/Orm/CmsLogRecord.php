<?php

namespace Cms\Orm;

/**
 * Rekord logu
 */
class CmsLogRecord extends \Mmi\Orm\Record
{

    public $id;
    public $url;
    public $ip;
    public $browser;
    public $operation;
    public $object;
    public $objectId;
    public $data;
    public $success;
    public $cmsAuthId;
    public $dateTime;

}
