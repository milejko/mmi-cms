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
 * Wybór daty i czasu
 */
class DateTimePicker extends \Cms\Form\Element\DatePicker {

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$format = isset($this->_options['format']) ? $this->_options['format'] : 'Y-m-d H:i';

		$dateStart = isset($this->_options['dateStart']) ? $this->_options['dateStart'] : 'false';
		$dateEnd = isset($this->_options['dateEnd']) ? $this->_options['dateEnd'] : 'false';
		$dateMin = isset($this->_options['dateMin']) ? "'" . $this->_options['dateMin'] . "'" : 'false';
		$dateMax = isset($this->_options['dateMax']) ? "'" . $this->_options['dateMax'] . "'" : 'false';
		$datepicker = isset($this->_options['datepicker']) ? $this->_options['datepicker'] : 'true';
		
		$id = $this->getOption('id');

		$this->view->headScript()->appendScript("$(document).ready(function () {
				$('#$id').datetimepicker({step: 15, minDate: $dateMin, maxDate: $dateMax, dateStart: '$dateStart', dateEnd: '$dateEnd', datepicker: '$datepicker', format:'$format', validateOnBlur: true, closeOnDateSelect: false $this->dateEvent});
				$.datetimepicker.setLocale('pl');					
			});
		");

		unset($this->_options['dateStart']);
		unset($this->_options['dateEnd']);
		unset($this->_options['format']);
		unset($this->_options['datepicker']);
		$html = '<div class="field"><input id="' . $id . '" class="datePickerField dp-applied" ';
		$html .= 'type="text" ' . $this->_getHtmlOptions() . '/></div>';

		return $html;
	}

}
