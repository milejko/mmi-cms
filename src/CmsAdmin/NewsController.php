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
 * Kontroler admina aktualności
 */
class NewsController extends Mvc\Controller {

	/**
	 * Lista aktualności
	 */
	public function indexAction() {
		$this->view->grid = new \Cms\Plugin\NewsGrid();
	}

	/**
	 * Edycja artykułu
	 */
	public function editAction() {
		$form = new \CmsAdmin\Form\News(new \Cms\Orm\News\Record($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('News zapisany poprawnie', true);
			$this->getResponse()->redirect('cmsAdmin', 'news');
		}
		$this->view->newsForm = $form;
	}

	/**
	 * Usuwanie artykułu
	 */
	public function deleteAction() {
		$article = \Cms\Orm\News\Query::factory()->findPk($this->id);
		if ($article && $article->delete()) {
			$this->getMessenger()->addMessage('News usunięty poprawnie', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'news');
	}

}
