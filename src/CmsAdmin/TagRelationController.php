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
 * Kontroler relacji tagów
 */
class TagRelationController extends Mvc\Controller {

	/**
	 * Lista tagów
	 */
	public function indexAction() {
		$this->view->grid = new \CmsAdmin\Plugin\TagRelationGrid();
	}

	/**
	 * Edycja tagów
	 */
	public function editAction() {
		$form = new \CmsAdmin\Form\TagRelation(new \Cms\Orm\CmsTagRelationRecord($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Powiązanie tagu zapisane poprawnie', true);
			$this->getResponse()->redirect('cmsAdmin', 'tagRelation', 'index');
		}
		$this->view->tagRelationForm = $form;
	}

	/**
	 * Usuwanie tagu
	 */
	public function deleteAction() {
		$tagRel = (new \Cms\Orm\CmsTagRelationQuery)->findPk($this->id);
		if ($tagRel && $tagRel->delete()) {
			$this->getMessenger()->addMessage('Powiązanie tagu usunięte', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'tagRelation', 'index');
	}

}
