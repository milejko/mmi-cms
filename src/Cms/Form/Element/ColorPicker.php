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
 * Wybór koloru
 * @property string $value
 * @property string $readonly
 */
class ColorPicker extends Text
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
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        $this->view->headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js');
        $this->view->headScript()->appendFile('/resource/cmsAdmin/js/jquery/farbtastic.js');
        $this->view->headScript()->appendScript('
			$(document).ready(function() {
				$(\'#' . $this->getOption('id') . 'Picker\').farbtastic(\'#' . $this->getOption('id') . '\');
			});
		');
        $this->readonly = 'readonly';
        $this->view->headLink()->appendStylesheet('/resource/cms/css/farbtastic.css');
        if (!$this->value) {
            $this->value = '#ffffff';
        }
        $html = '<input class="colorField" ';
        $html .= 'type="text" ' . $this->_getHtmlOptions() . '/><div class="' . $this->getOption('id') . 'Picker"></div>';
        return $html;
    }
}
