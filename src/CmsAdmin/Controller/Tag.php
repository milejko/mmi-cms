<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Controller;

class Tag extends Action {

	public function indexAction() {
		$this->view->grid = new \Cms\Plugin\TagGrid();
	}

	public function editAction() {
		$form = new \CmsAdmin\Form\Tag(new \Cms\Orm\Tag\Record($this->id));
		if ($form->isSaved()) {
			$this->getHelperMessenger()->addMessage('Tag zapisany poprawnie', true);
			$this->getResponse()->redirect('cmsAdmin', 'tag', 'index');
		}
		$this->view->tagForm = $form;
	}

	public function deleteAction() {
		$tag = \Cms\Orm\Tag\Query::factory()->findPk($this->id);
		if ($tag && $tag->delete()) {
			$this->getHelperMessenger()->addMessage('Tag usunięty', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'tag', 'index');
	}

}
