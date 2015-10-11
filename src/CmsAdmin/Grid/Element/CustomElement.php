<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid\Element;
use Mmi\App\FrontController;

/**
 * Klasa elementu dowolnego
 * 
 * @method CustomElement setTemplateCode($code) dodaje kod
 * @method string getTemplateCode() pobiera kod szablonu
 * @method self setName($name) ustawia nazwę pola
 * @method string getName() pobiera nazwę pola
 * @method self setLabel($label) ustawia labelkę
 * @method string getLabel() pobiera labelkę
 */
class CustomElement extends ElementAbstract {
	
	/**
	 * Renderuje customowe elementy
	 * @param \Mmi\Orm\RecordRo $record
	 * @return string
	 */
	public function renderCell(\Mmi\Orm\RecordRo $record) {
		$view = FrontController::getInstance()->getView();
		$view->record = $record;
		return $view->renderDirectly($this->getTemplateCode());
	}

}
