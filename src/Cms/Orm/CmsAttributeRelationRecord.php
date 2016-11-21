<?php

namespace Cms\Orm;

/**
 * Rekord relacji atrybutu
 */
class CmsAttributeRelationRecord extends \Mmi\Orm\Record {

	public $id;
	public $cmsAttributeId;
	
	/**
	 * Wartość domyślna
	 * @var mixed
	 */
	public $cmsAttributeValueId;
	public $object;
	public $objectId;
	public $order;
	
}
