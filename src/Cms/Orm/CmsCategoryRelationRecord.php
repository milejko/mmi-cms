<?php

namespace Cms\Orm;

/**
 * Rekord relacji kategorii
 */
class CmsCategoryRelationRecord extends \Mmi\Orm\Record
{

    public $id;

    /**
     * Identyfikator kategorii
     * @var integer
     */
    public $cmsCategoryId;
    public $object;
    public $objectId;

}
