<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Controller;

class Form extends \Mmi\Controller\Action {

	public function validateAction() {
		//typ odpowiedzi: plain
		$this->getResponse()->setTypePlain();
		//wyłączenie layoutu
		$this->view->setLayoutDisabled();

		//sprawdzenie obecności obowiązkowych pól w poscie
		if (!$this->getPost()->ctrl || !$this->getPost()->field) {
			return '';
		}
		//ekstrakcja opcji z CTRL
		$options = \Mmi\Convert\Table::fromString($this->getPost()->ctrl);
		//brak obowiązkowych opcji w CTRL
		if (!isset($options['class']) || !isset($options['options']) || !isset($options['recordClass'])) {
			return '';
		}
		//nazwa klasy forma
		$class = $options['class'];
		//nazwa klasy rekordu
		$recordClass = $options['recordClass'];
		//powoływanie forma
		$form = new $class($recordClass ? new $recordClass(isset($options['id']) ? $options['id'] : null) : null, $options['options']);
		/* @var $form \Mmi\Form */
		//pobieranie elementu do walidacji
		$element = $form->getElement($this->getPost()->field);
		//jeśli brak elementu - wyjście
		if (!$element instanceof \Mmi\Form\Element\ElementAbstract) {
			return '';
		}
		//ustawienie wartości elementu
		$element->setValue(urldecode($this->getPost()->value));
		//walidacja i zwrot wyniku
		if (!$element->isValid() && $element->hasErrors()) {
			$this->view->errors = $element->getErrors();
			return;
		}
		//poprawna walidacja
		return '';
	}

}
