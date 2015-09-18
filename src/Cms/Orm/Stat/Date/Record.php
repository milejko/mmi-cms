<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Stat\Date;

class Record extends \Mmi\Orm\Record {

	public $id;
	public $hour;
	public $day;
	public $month;
	public $year;
	public $object;
	public $objectId;
	public $count;

}
