<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

class MailServerController extends Mvc\Controller {

	public function indexAction() {
		$grid = new \CmsAdmin\Plugin\MailServerGrid();
		$this->view->grid = $grid;
	}

	public function editAction() {
		$form = new \CmsAdmin\Form\Mail\Server(new \Cms\Orm\Mail\Server\Record($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Zapisano ustawienia serwera', true);
			$this->getResponse()->redirect('cmsAdmin', 'mailServer');
		}
		$this->view->serverForm = $form;
	}

	public function deleteAction() {
		$server = \Cms\Orm\Mail\Server\Query::factory()->findPk($this->id);
		try {
			if ($server && $server->delete()) {
				$this->getMessenger()->addMessage('Usunięto serwer');
			}
		} catch (\Mmi\Db\Exception $e) {
			$this->getMessenger()->addMessage('Nie można usunąć serwera, istnieją powiązane szablony', false);
		}
		$this->getResponse()->redirect('cmsAdmin', 'mailServer');
	}

}
