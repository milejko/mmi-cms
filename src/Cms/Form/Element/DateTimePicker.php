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
 * @method self setTimepicker(string $timepicker)
 * 
 * @method ?string getDateMin()
 * @method ?string getDateMax()
 * @method ?string getDateMinField()
 * @method ?string getDateMaxField()
 * @method ?string getDatepicker()
 * @method ?string getTimepicker()
 */
class DateTimePicker extends \Mmi\Form\Element\ElementAbstract
{
    //szablon początku pola
    public const TEMPLATE_BEGIN = 'cmsAdmin/form/element/element-abstract/begin';
    //szablon opisu
    public const TEMPLATE_DESCRIPTION = 'cmsAdmin/form/element/element-abstract/description';
    //szablon końca pola
    public const TEMPLATE_END = 'cmsAdmin/form/element/element-abstract/end';
    //szablon błędów
    public const TEMPLATE_ERRORS = 'cmsAdmin/form/element/element-abstract/errors';
    //szablon etykiety
    public const TEMPLATE_LABEL = 'cmsAdmin/form/element/element-abstract/label';

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
        $this->view->headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js');
        $this->view->headScript()->appendFile('/resource/cmsAdmin/js/datetimepicker/jquery.datetimepicker.full.min.js');
        $this->view->headLink()->appendStylesheet('/resource/cmsAdmin/js/datetimepicker/jquery.datetimepicker.min.css');
        $this->addFilter(new \Mmi\Filter\EmptyToNull())
            ->addClass('form-control')
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
				$.datetimepicker.setLocale(request.locale);
			});
        ");
        //@TODO: get locale from view
        //czyszczenie niepotrzebnych opcji
        $this->unsetOption('dateMin')
            ->unsetOption('dateMax')
            ->unsetOption('datepicker')
            ->unsetOption('timepicker');

        return '<input class="form-control datePickerField" autocomplete="off" data-min-date="' . $this->_formatDate($dateMin) . '" data-max-date="' . $this->_formatDate($dateMax) . '" type="datetime" ' . $this->_getHtmlOptions() . '/>';
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
