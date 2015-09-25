<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

class ContactController extends Mvc\Controller {

	public function indexAction() {
		$this->view->grid = new \Cms\Plugin\ContactGrid();
	}

	public function subjectAction() {
		$this->view->grid = new \Cms\Plugin\ContactOptionGrid();
	}

	public function editSubjectAction() {
		$form = new \CmsAdmin\Form\Contact\Option(new \Cms\Orm\Contact\Option\Record($this->id));
		if ($form->isSaved()) {
			$this->getHelperMessenger()->addMessage('Poprawnie zapisano temat kontaktu', true);
			$this->getResponse()->redirect('cmsAdmin', 'contact', 'subject');
		}
		$this->view->optionForm = $form;
	}

	public function deleteSubjectAction() {
		$option = \Cms\Orm\Contact\Option\Query::factory()->findPk($this->id);
		if ($option && $option->delete()) {
			$this->getHelperMessenger()->addMessage('Poprawnie usunięto temat', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'contact', 'subject');
	}

	public function deleteAction() {
		$contact = \Cms\Orm\Contact\Query::factory()->findPk($this->id);
		if ($contact && $contact->delete()) {
			$this->getHelperMessenger()->addMessage('Poprawnie usunięto wiadomość', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'contact');
	}

	public function editAction() {
		$form = new \CmsAdmin\Form\Contact(new \Cms\Orm\Contact\Record($this->id));
		if ($form->isSaved()) {
			$this->getHelperMessenger()->addMessage('Wysłano odpowiedź na wiadomość', true);
			$this->getResponse()->redirect('cmsAdmin', 'contact');
		}
		$this->view->contactForm = $form;
	}

}
