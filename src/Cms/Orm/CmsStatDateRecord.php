<?php

namespace Cms\Orm;

/**
 * Rekord statystyk zagregowanych
 */
class CmsStatDateRecord extends \Mmi\Orm\Record
{

    public $id;
    public $hour;
    public $day;
    public $month;
    public $year;
    public $object;
    public $objectId;
    public $count;

}
