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
 * Kontroler harmonogramu zadań
 */
class CronController extends Mvc\Controller {

	/**
	 * Lista zadań
	 */
	public function indexAction() {
		$grid = new \CmsAdmin\Plugin\CronGrid();
		$this->view->grid = $grid;
	}

	/**
	 * Edycja zadania
	 */
	public function editAction() {
		$form = new \CmsAdmin\Form\Cron(new \Cms\Orm\Cron\Record($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Zadanie zapisane poprawnie', true);
			$this->getResponse()->redirect('cmsAdmin', 'cron');
		}
		$this->view->cronForm = $form;
	}

	/**
	 * Usuwanie zadania
	 */
	public function deleteAction() {
		$record = \Cms\Orm\Cron\Query::factory()->findPk($this->id);
		if ($record && $record->delete()) {
			$this->getMessenger()->addMessage('Zadanie CRON poprawnie usunięte', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'cron');
	}

}
