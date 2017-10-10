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
 * Element DateTimePicker
 */
class DateTimePicker extends \Mmi\Form\Element\ElementAbstract
{

    /**
     * Ustawia format np. Y-m-d H:i
     * @param string $format
     * @return \Cms\Form\Element\DateTimePicker
     */
    public function setFormat($format)
    {
        return $this->setOption('data-format', $format);
    }

    /**
     * Pobiera format
     * @return string
     */
    public function getFormat()
    {
        return $this->getOption('data-format');
    }

    /**
     * Przekazanie widoku + pliki
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->view = \Mmi\App\FrontController::getInstance()->getView();
        $this->view->headLink()->appendStylesheet('/resource/cmsAdmin/css/datetimepicker.css');
        $this->view->headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js');
        $this->view->headScript()->appendFile('/resource/cmsAdmin/js/jquery/datetimepicker.js');
        $this->addFilterEmptyToNull()
            ->setStep(15)
            ->setDatepicker(true)
            ->setTimepicker(true)
            ->setFormat('Y-m-d H:i');
    }

    /**
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        $dateMin = $this->getDateMin() ? "'" . $this->getDateMin() . "'" : 'false';
        $dateMax = $this->getDateMax() ? "'" . $this->getDateMax() . "'" : 'false';
        $datepicker = $this->getDatepicker() ? 'true' : 'false';
        $timepicker = $this->getTimepicker() ? 'true' : 'false';
        $minFieldId = $this->getDateMinField() ? $this->getDateMinField()->getId() : null;
        $maxFieldId = $this->getDateMaxField() ? $this->getDateMaxField()->getId() : null;
        //brak datepickera - format czasu
        if (!$this->getDatepicker()) {
            $this->setFormat('H:i');
        }
        //filtracja daty do zadanego formatu
        $this->setValue($this->_formatDate($this->getValue()));
        //dodanie skryptu inicjującego pickera
        $this->view->headScript()->appendScript("$(document).ready(function () {
				$('#" . $this->getId() . "').datetimepicker({
					allowBlank: true, scrollInput: false, scrollMonth:false, step: 15, minDate: $dateMin, maxDate: $dateMax,
					datepicker: $datepicker, timepicker: $timepicker, format: '" . $this->getFormat() . "', validateOnBlur: true,
					onShow: function(currentTime, input) {
						if ('" . $minFieldId . "' != '' && jQuery('#" . $minFieldId . "').val()) {
							this.setOptions({
								minDate: jQuery('#" . $minFieldId . "').val()
							});
							input.attr('data-min-date', jQuery('#" . $minFieldId . "').val());
						}
						if ('" . $maxFieldId . "' != '' && jQuery('#" . $maxFieldId . "').val()) {
							this.setOptions({
								maxDate: jQuery('#" . $maxFieldId . "').val()
							});
							input.attr('data-max-date', jQuery('#" . $maxFieldId . "').val());
						}
					},
					onClose: function(currentTime, input) {
						var inputDate = new Date(input.val()),
							maxDate = new Date(input.attr('data-max-date')),
							minDate = new Date(input.attr('data-min-date'));
						if (input.attr('data-min-date') != '' && inputDate < minDate) {
							input.val('');
						}
						if (input.attr('data-max-date') != '' && inputDate > maxDate) {
							input.val('');
						}
					}
					});
				$.datetimepicker.setLocale('pl');
			});
		");

        //czyszczenie niepotrzebnych opcji
        $this->unsetOption('dateMin')
            ->unsetOption('dateMax')
            ->unsetOption('datepicker')
            ->unsetOption('timepicker');

        return '<input class="datePickerField" autocomplete="off" data-min-date="' . $this->_formatDate($dateMin) . '" data-max-date="' . $this->_formatDate($dateMax) . '" type="datetime" ' . $this->_getHtmlOptions() . '/>';
    }

    /**
     * Formatowanie daty
     * @return string
     */
    protected function _formatDate($date)
    {
        $clearDate = trim($date, '\'" ');
        //brak daty, pusty zwrot
        if (!$clearDate || $clearDate == 'false') {
            return;
        }
        return date($this->getFormat(), strtotime($clearDate));
    }

}
