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
		$view = \Mmi\App\FrontController::getInstance()->getView();
		$format = isset($this->_options['format']) ? $this->_options['format'] : 'Y-m-d H:i';
		$dateStart = isset($this->_options['dateStart']) ? $this->_options['dateStart'] : 'false';
		$dateEnd = isset($this->_options['dateEnd']) ? $this->_options['dateEnd'] : 'false';
		$datepicker = isset($this->_options['datepicker']) ? $this->_options['datepicker'] : 'true';
		$view->headLink()->appendStylesheet($view->baseUrl . '/resource/cmsAdmin/css/datetimepicker.css');
		$view->headScript()->prependFile($view->baseUrl . '/resource/cmsAdmin/js/jquery/jquery.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/jquery/datetimepicker.js');
		$id = $this->getOption('id');
		$view->headScript()->appendScript("$(document).ready(function () {
				$('#$id').datetimepicker({'lang':'pl', step: 15, dateStart: '$dateStart', dateEnd: '$dateEnd', datepicker: '$datepicker', format:'$format', validateOnBlur: true, closeOnDateSelect: false});
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
