<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Controller;

class Comment extends Action {

	public function indexAction() {
		$this->view->grid = new \Cms\Plugin\CommentGrid();
	}

	public function deleteAction() {
		$comment = \Cms\Orm\Comment\Query::factory()->findPk($this->id);
		if ($comment && $comment->delete()) {
			$this->getHelperMessenger()->addMessage('Poprawnie usunięto artykuł', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'comment');
	}

}
