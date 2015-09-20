<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

class DatePicker extends \Mmi\Form\Element\ElementAbstract {

	/**
	 * Funkcja użytkownika, jest wykonywana na końcu konstruktora
	 */
	public function init() {
		$this->addFilter('emptyToNull');
	}

	/**
	 * Ustawia datę startową
	 * @param string $date - sformatowany string czasu
	 * @return \Mmi\Form\Element\DatePicker
	 */
	public function setDateStart($date) {
		return $this->setOption('dateStart', $date);
	}

	/**
	 * Ustawia datę końcową
	 * @param string $date - sformatowany string czasu
	 * @return \Mmi\Form\Element\DatePicker
	 */
	public function setDateEnd($date) {
		return $this->setOption('dateEnd', $date);
	}

	/**
	 * Ustawia format daty
	 * @param string $format
	 * @return \Mmi\Form\Element\DatePicker
	 */
	public function setFormat($format) {
		return $this->setOption('format', $format);
	}

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$view = \Mmi\App\FrontController::getInstance()->getView();
		$format = isset($this->_options['format']) ? $this->_options['format'] : 'Y-m-d';
		$dateStart = isset($this->_options['dateStart']) ? $this->_options['dateStart'] : 'false';
		$dateEnd = isset($this->_options['dateEnd']) ? $this->_options['dateEnd'] : 'false';
		$view->headLink()->appendStylesheet($view->baseUrl . '/resource/cms/css/datetimepicker.css');
		$view->headScript()->prependFile($view->baseUrl . '/resource/cmsAdmin/js/jquery/jquery.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/jquery/datetimepicker.js');
		$id = $this->getOption('id');
		$view->headScript()->appendScript("$(document).ready(function () {
				$('#$id').datetimepicker({'lang':'pl', timepicker: false, dateStart: '$dateStart', dateEnd: '$dateEnd', format:'$format', validateOnBlur: true, closeOnDateSelect: true});
			});
		");
		unset($this->_options['startDate']);
		unset($this->_options['endDate']);
		unset($this->_options['format']);
		$html = '<input id="' . $id . '" class="datePickerField dp-applied" ';
		$html .= 'type="text" ' . $this->_getHtmlOptions() . '/>';

		return $html;
	}

}
