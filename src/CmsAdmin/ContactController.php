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
 * Kontroler kontaktów
 */
class ContactController extends Mvc\Controller {

	/**
	 * Lista zgłoszeń
	 */
	public function indexAction() {
		$this->view->grid = new \CmsAdmin\Plugin\ContactGrid();
	}

	/**
	 * Lista tematów zgłoszeń
	 */
	public function subjectAction() {
		$this->view->grid = new \CmsAdmin\Plugin\ContactOptionGrid();
	}

	/**
	 * Edycja tematu
	 */
	public function editSubjectAction() {
		$form = new \CmsAdmin\FOrm\CmsContactOption(new \Cms\Orm\CmsContactOption\Record($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Poprawnie zapisano temat kontaktu', true);
			$this->getResponse()->redirect('cmsAdmin', 'contact', 'subject');
		}
		$this->view->optionForm = $form;
	}

	/**
	 * Usuwanie tematu
	 */
	public function deleteSubjectAction() {
		$option = \Cms\Orm\CmsContactOption\Query::factory()->findPk($this->id);
		if ($option && $option->delete()) {
			$this->getMessenger()->addMessage('Poprawnie usunięto temat', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'contact', 'subject');
	}

	/**
	 * Usuwanie zgłoszenia
	 */
	public function deleteAction() {
		$contact = \Cms\Orm\CmsContactQuery::factory()->findPk($this->id);
		if ($contact && $contact->delete()) {
			$this->getMessenger()->addMessage('Poprawnie usunięto wiadomość', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'contact');
	}

	/**
	 * Edycja/odpowiedź na zgłoszenie
	 */
	public function editAction() {
		$form = new \CmsAdmin\Form\Contact(new \Cms\Orm\CmsContactRecord($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Wysłano odpowiedź na wiadomość', true);
			$this->getResponse()->redirect('cmsAdmin', 'contact');
		}
		$this->view->contactForm = $form;
	}

}
