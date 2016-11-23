<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

/**
 * Element DatePicker (DateTimePicker z wyłączoną godziną)
 */
class DatePicker extends DateTimePicker {

	/**
	 * Konstruktor
	 */
	public function __construct($name) {
		parent::__construct($name);
		//wyłączanie datepickera
		$this->setTimepicker(false)
			->setFormat('Y-m-d');
	}

}
