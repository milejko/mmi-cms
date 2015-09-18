<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Controller;

/**
 * Kontroler admina aktualności
 */
class News extends Action {

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
			$this->getHelperMessenger()->addMessage('News zapisany poprawnie', true);
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
			$this->getHelperMessenger()->addMessage('News usunięty poprawnie', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'news');
	}

}
