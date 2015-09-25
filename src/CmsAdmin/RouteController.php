<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

class RouteController extends Mvc\Controller {

	public function indexAction() {
		$this->view->grid = new \Cms\Plugin\RouteGrid();
	}

	public function editAction() {
		$form = new \CmsAdmin\Form\Route(new \Cms\Orm\Route\Record($this->id));
		if ($form->isSaved()) {
			$this->getHelperMessenger()->addMessage('Poprawnie zapisano trasę', true);
			$this->getResponse()->redirect('cmsAdmin', 'route');
		}
		$this->view->routeForm = $form;
	}

	public function deleteAction() {
		$text = \Cms\Orm\Route\Query::factory()->findPk($this->id);
		if ($text && $text->delete()) {
			$this->getHelperMessenger()->addMessage('Poprawnie skasowano trasę');
		}
		$this->getResponse()->redirect('cmsAdmin', 'route', 'index');
	}

}
