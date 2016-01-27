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
 * Kontroler tagów
 */
class TagController extends Mvc\Controller {

	/**
	 * Lista tagów
	 */
	public function indexAction() {
		$this->view->grid = new \CmsAdmin\Plugin\TagGrid();
	}

	/**
	 * Edycja tagów
	 */
	public function editAction() {
		$form = new \CmsAdmin\Form\Tag(new \Cms\Orm\CmsTagRecord($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Tag zapisany poprawnie', true);
			$this->getResponse()->redirect('cmsAdmin', 'tag', 'index');
		}
		$this->view->tagForm = $form;
	}

	/**
	 * Usuwanie tagu
	 */
	public function deleteAction() {
		$tag = (new \Cms\Orm\CmsTagQuery)->findPk($this->id);
		if ($tag && $tag->delete()) {
			$this->getMessenger()->addMessage('Tag usunięty', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'tag', 'index');
	}

}
