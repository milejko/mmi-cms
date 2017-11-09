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
class DatePicker extends DateTimePicker
{

    //szablon początku pola
    CONST TEMPLATE_BEGIN = 'cmsAdmin/form/element/element-abstract/begin';
    //szablon opisu
    CONST TEMPLATE_DESCRIPTION = 'cmsAdmin/form/element/element-abstract/description';
    //szablon końca pola
    CONST TEMPLATE_END = 'cmsAdmin/form/element/element-abstract/end';
    //szablon błędów
    CONST TEMPLATE_ERRORS = 'cmsAdmin/form/element/element-abstract/errors';
    //szablon etykiety
    CONST TEMPLATE_LABEL = 'cmsAdmin/form/element/element-abstract/label';

    /**
     * Konstruktor
     */
    public function __construct($name)
    {
        parent::__construct($name);
        //wyłączanie datepickera
        $this->setTimepicker(false)
            ->setFormat('Y-m-d');
    }

}
