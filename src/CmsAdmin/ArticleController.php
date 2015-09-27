<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

class ArticleController extends Mvc\Controller {

	public function indexAction() {
		$this->view->grid = new \Cms\Plugin\ArticleGrid();
	}

	public function editAction() {
		$form = new \CmsAdmin\Form\Article(new \Cms\Orm\Article\Record($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Artykuł zapisany poprawnie', true);
			$this->getResponse()->redirect('cmsAdmin', 'article');
		}
		$this->view->articleForm = $form;
	}

	public function deleteAction() {
		$record = \Cms\Orm\Article\Query::factory()->findPk($this->id);
		if ($record && $record->delete()) {
			$this->getMessenger()->addMessage('Poprawnie usunięto artykuł', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'article');
	}

}
