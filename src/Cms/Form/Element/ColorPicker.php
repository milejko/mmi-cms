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
 */
class ColorPicker extends \Mmi\Form\Element\Text {

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$view = \Mmi\App\FrontController::getInstance()->getView();
		$view->headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js');
		$view->headScript()->appendFile('/resource/cmsAdmin/js/jquery/farbtastic.js');
		$view->headScript()->appendScript('
			$(document).ready(function() {
				$(\'#' . $this->getOption('id') . 'Picker\').farbtastic(\'#' . $this->getOption('id') . '\');
			});
		');
		$this->readonly = 'readonly';
		$view->headLink()->appendStylesheet('/resource/cms/css/farbtastic.css');
		if (!$this->value) {
			$this->value = '#ffffff';
		}
		$html = '<input class="colorField" ';
		$html .= 'type="text" ' . $this->_getHtmlOptions() . '/><div class="' . $this->getOption('id') . 'Picker"></div>';
		return $html;
	}

}
