<?php

namespace Cms\Orm;

/**
 * Rekord relacji atrybutu
 */
class CmsAttributeRelationRecord extends \Mmi\Orm\Record
{

    public $id;
    public $cmsAttributeId;

    /**
     * Wartość domyślna
     * @var mixed
     */
    public $cmsAttributeValueId;
    public $filterClasses;
    public $validatorClasses;
    public $required;
    public $unique;
    public $materialized;
    public $object;
    public $objectId;
    public $order;

    /**
     * Czy zmaterializowany
     * @return boolean
     */
    public function isMaterialized()
    {
        return ($this->materialized == 1);
    }

    /**
     * Czy zmaterializowany, odziedziczony
     * @return boolean
     */
    public function isMaterializedInherited()
    {
        return ($this->materialized == 2);
    }

}
