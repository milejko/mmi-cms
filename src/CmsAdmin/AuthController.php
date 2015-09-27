<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

class AuthController extends Mvc\Controller {

	public function indexAction() {
		$this->view->grid = new \Cms\Plugin\AuthGrid();
	}

	public function editAction() {
		$form = new \CmsAdmin\Form\Auth(new \Cms\Orm\Auth\Record($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Poprawnie zapisano użytkownika', true);
			$this->getResponse()->redirect('cmsAdmin', 'auth');
		}
		$this->view->authForm = $form;
	}

	public function deleteAction() {
		$auth = \Cms\Orm\Auth\Query::factory()->findPk($this->id);
		if ($auth && $auth->delete()) {
			$this->getMessenger()->addMessage('Poprawnie skasowano użytkownika', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'auth');
	}

}
