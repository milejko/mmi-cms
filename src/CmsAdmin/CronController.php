<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

class CronController extends Mvc\Controller {

	public function indexAction() {
		$grid = new \Cms\Plugin\CronGrid();
		$this->view->grid = $grid;
	}

	public function editAction() {
		$form = new \CmsAdmin\Form\Cron(new \Cms\Orm\Cron\Record($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Zadanie zapisane poprawnie', true);
			$this->getResponse()->redirect('cmsAdmin', 'cron');
		}
		$this->view->cronForm = $form;
	}

	public function deleteAction() {
		$record = \Cms\Orm\Cron\Query::factory()->findPk($this->id);
		if ($record && $record->delete()) {
			$this->getMessenger()->addMessage('Zadanie CRON poprawnie usunięte', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'cron');
	}

}
