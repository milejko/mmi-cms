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
 * Kontroler użytkowników
 */
class AuthController extends Mvc\Controller {

	/**
	 * Lista użytkowników
	 */
	public function indexAction() {
		$this->view->grid = new \CmsAdmin\Plugin\AuthGrid();
	}

	/**
	 * Edycja użytkownika
	 */
	public function editAction() {
		$form = new \CmsAdmin\Form\Auth(new \Cms\Orm\CmsAuthRecord($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Poprawnie zapisano użytkownika', true);
			$this->getResponse()->redirect('cmsAdmin', 'auth');
		}
		$this->view->authForm = $form;
	}

	/**
	 * Kasowanie użytkownika
	 */
	public function deleteAction() {
		$auth = \Cms\Orm\CmsAuthQuery::factory()->findPk($this->id);
		if ($auth && $auth->delete()) {
			$this->getMessenger()->addMessage('Poprawnie skasowano użytkownika', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'auth');
	}

}
