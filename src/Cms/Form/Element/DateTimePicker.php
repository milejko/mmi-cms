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
				$('#$id').datetimepicker({
					step: 15, minDate: $dateMin, maxDate: $dateMax, dateStart: '$dateStart', dateEnd: '$dateEnd',
					datepicker: '$datepicker', format:'$format', validateOnBlur: true, 
					onClose:function(ct,i){
						var data = new DateFormatter();
						var d = data.formatDate(this.getValue(), 'Y-m-d H:i');    
						i.find('input').val(d);
					},
					closeOnDateSelect: false $this->dateEvent
					});
				$.datetimepicker.setLocale('pl');
				
				$('#$id').find('input').on('keypress keyup keydown', function (e) {
					if(e.keyCode == 8 || e.keyCode == 46 || e.keyCode == 32){
						$('#$id').datetimepicker('reset');
						$('#$id').datetimepicker('hide');
						$('#$id').find('input').val('');
					}
				});
				$('#$id').find('i.reset').on('click', function (e) {
						$('#$id').datetimepicker('reset');
						$('#$id').datetimepicker('hide');
						$('#$id').find('input').val('');
				});
			});
		");

		unset($this->_options['dateStart']);
		unset($this->_options['dateEnd']);
		unset($this->_options['format']);
		unset($this->_options['datepicker']);
		unset($this->_options['id']);

		$html = '<div class = "input-group" id = "' . $id . '" style="display: inline-block;position: relative;">';
		$html .= '<input class="datePickerField dp-applied" type="text" ' . $this->_getHtmlOptions() . '/><i class="reset icon-remove-circle" style="cursor: pointer;padding: 7px;position: absolute;right: 0;top: 0;"></i>';
		$html .= '</div>';

		return $html;
	}

}
