<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid\Column;

use Mmi\App\FrontController;

/**
 * Obsługa requestu
 */
class CheckboxRequestHandler {

	/**
	 * Obiekt checkboxa
	 * @var CheckboxColumn
	 */
	protected $_checkbox;

	/**
	 * Konstruktor przypina obiekt checkboxa
	 * @param CheckboxColumn $checkbox
	 */
	public function __construct(CheckboxColumn $checkbox) {
		$this->_checkbox = $checkbox;
	}

	/**
	 * Obsługa requestu jeśli się pojawił
	 */
	public function handleRequest() {
		//obsługa danych z POST
		$post = FrontController::getInstance()->getRequest()->getPost();
		//brak posta
		if ($post->isEmpty()) {
			return;
		}
		if ($this->_changeRecord($post)) {
			exit;
		}
	}

	/**
	 * Zwraca obiekt sortowania na podstawie post
	 * @param \Mmi\Http\RequestPost $post
	 * @return boolean
	 */
	protected function _changeRecord(\Mmi\Http\RequestPost $post) {
		//brak danych dla tego checkboxa
		if ($post->name != $this->_checkbox->getFormColumnName()) {
			return;
		}
		//brak id
		if (!$post->id || !$post->value) {
			return;
		}
		//wybór rekordu
		$record = $this->_checkbox->getGrid()
			->getQuery()
			->findPk($post->id);
		//brak property z checkboxa
		if (!property_exists($record, $this->_checkbox->getName())) {
			return;
		}
		//ustawianie property
		$record->{$this->_checkbox->getName()} = ($post->checked == 'true') ? $post->value : 0;
		return $record->save();
	}

}
