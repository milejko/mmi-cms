<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

class ColorPicker extends \Mmi\Form\Element\Text {

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$view = \Mmi\Controller\Front::getInstance()->getView();
		$view->headScript()->prependFile($view->baseUrl . '/resource/cmsAdmin/js/jquery/jquery.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/jquery/farbtastic.js');
		$view->headScript()->appendScript('
			$(document).ready(function() {
				$(\'#' . $this->getOption('id') . 'Picker\').farbtastic(\'#' . $this->getOption('id') . '\');
			});
		');
		$this->readonly = 'readonly';
		$view->headLink()->appendStylesheet($view->baseUrl . '/resource/cms/css/farbtastic.css');
		if (!$this->value) {
			$this->value = '#ffffff';
		}
		$html = '<input class="colorField" ';
		$html .= 'type="text" ' . $this->_getHtmlOptions() . '/><div class="' . $this->getOption('id') . 'Picker"></div>';
		return $html;
	}

}
