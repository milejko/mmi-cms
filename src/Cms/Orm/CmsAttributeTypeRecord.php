<?php

namespace Cms\Orm;

/**
 * Rekord typu atrybutu (pola formularza)
 */
class CmsAttributeTypeRecord extends \Mmi\Orm\Record {

	public $id;
	public $name;
	public $fieldClass;
	public $restricted;
	public $multiple;
	public $uploader;

}
