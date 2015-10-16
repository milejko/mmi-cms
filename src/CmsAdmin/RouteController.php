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
 * Kontroler tras
 */
class RouteController extends Mvc\Controller {

	/**
	 * Lista tras
	 */
	public function indexAction() {
		$this->view->grid = new \CmsAdmin\Plugin\RouteGrid();
	}

	/**
	 * Edycja trasy
	 */
	public function editAction() {
		$form = new \CmsAdmin\Form\Route(new \Cms\Orm\CmsRouteRecord($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Poprawnie zapisano trasę', true);
			$this->getResponse()->redirect('cmsAdmin', 'route');
		}
		$this->view->routeForm = $form;
	}

	/**
	 * Usuwanie trasy
	 */
	public function deleteAction() {
		$text = (new \Cms\Orm\CmsRouteQuery)->findPk($this->id);
		if ($text && $text->delete()) {
			$this->getMessenger()->addMessage('Poprawnie skasowano trasę');
		}
		$this->getResponse()->redirect('cmsAdmin', 'route', 'index');
	}

}
