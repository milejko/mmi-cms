<?php

namespace Cms\Orm;

/**
 * Rekord łączący tag z obiektem
 */
class CmsTagLinkRecord extends \Mmi\Orm\Record {

	public $id;
	public $cmsTagId;
	public $object;
	public $objectId;

}
