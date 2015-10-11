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
 * Klasa elementu indeksującego
 * 
 * @method IndexElement setIndex($index) ustawia index
 * @method integer getIndex() pobiera wartość indeksu
 * @method self setName($name) ustawia nazwę pola
 * @method string getName() pobiera nazwę pola
 * @method self setLabel($label) ustawia labelkę
 * @method string getLabel() pobiera labelkę
 */
class OperationElement extends ElementAbstract {

	/**
	 * Konstruktor ustawia domyślny label
	 * pole bez nazwy
	 */
	public function __construct() {
		$this->setLabel('operacje');
		parent::__construct('_operation_');
	}

	/**
	 * Renderuje komórkę
	 * @param \Mmi\Orm\RecordRo $record
	 * @return string
	 */
	public function renderCell(\Mmi\Orm\RecordRo $record) {
		$view = FrontController::getInstance()->getView();
		return '<a href="' . $view->url(['action' => 'edit', 'id' => $record->getPk()]) . '"><i class="icon-pencil"></i></a> ' .
			'<a href="' . $view->url(['action' => 'delete', 'id' => $record->getPk()]) . '" title="Czy na pewno usunąć" class="confirm"><i class="icon-remove-circle"></i></a>';
	}

}
