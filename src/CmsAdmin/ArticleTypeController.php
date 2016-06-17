<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

/**
 * Artykuły
 */
class ArticleTypeController extends Mvc\Controller {

	/**
	 * Lista artykułów
	 */
	public function indexAction() {
		$this->view->grid = new \CmsAdmin\Plugin\ArticleTypeGrid;
	}

	/**
	 * Edycja artykułu
	 */
	public function editAction() {
		$form = new \CmsAdmin\Form\ArticleType(new \Cms\Orm\CmsArticleTypeRecord($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Typ artykułu zapisany poprawnie', true);
			$this->getResponse()->redirect('cmsAdmin', 'articleType');
		}
		$this->view->articleTypeForm = $form;
	}

	/**
	 * Usuwanie artykułu
	 */
	public function deleteAction() {
		$record = (new \Cms\Orm\CmsArticleTypeQuery)->findPk($this->id);
		if ($record && $record->delete()) {
			$this->getMessenger()->addMessage('Poprawnie usunięto typ artykułu', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'articleType');
	}

}
