<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

/**
 * Zarządzanie tekstami statycznymi
 */
class TextController extends Mvc\Controller {

	/**
	 * Grid tekstów
	 */
	public function indexAction() {
		$this->view->grid = new \CmsAdmin\Plugin\TextGrid();
	}

	/**
	 * Akcja edycji tekstu
	 */
	public function editAction() {
		$form = new \CmsAdmin\Form\Text(new \Cms\Orm\CmsTextRecord($this->id));
		$this->view->textForm = $form;
		//brak wysłanych danych
		if (!$form->isMine()) {
			return;
		}
		//zapisany
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Poprawnie zapisano tekst', true);
			$this->getResponse()->redirect('cmsAdmin', 'text');
		}
		$this->getMessenger()->addMessage('Błąd zapisu tekstu, tekst o tym kluczu już istnieje', false);
	}

	/**
	 * Klonowanie tekstu
	 */
	public function cloneAction() {
		$form = new \CmsAdmin\Form\Text\Copy(new \Cms\Orm\CmsTextRecord());
		$this->view->copyForm = $form;
		//brak wysłanych danych
		if (!$form->isMine()) {
			return;
		}
		//zapis
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Poprawnie sklonowano teksty', true);
			$this->getResponse()->redirect('cmsAdmin', 'text');
		}
		$this->getMessenger()->addMessage('Błąd klonowania tekstów', false);
	}

	/**
	 * Usuwanie tekstu
	 */
	public function deleteAction() {
		$text = (new \Cms\Orm\CmsTextQuery)->findPk($this->id);
		//jeśli znaleziono tekst i udało się usunąć
		if ($text && $text->delete()) {
			$this->getMessenger()->addMessage('Poprawnie skasowano tekst', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'text');
	}

}
