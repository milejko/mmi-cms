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
 * Kontroler komentarzy
 */
class CommentController extends Mvc\Controller {

	/**
	 * Lista komentarzy
	 */
	public function indexAction() {
		$this->view->grid = new \CmsAdmin\Plugin\CommentGrid();
	}

	/**
	 * Usuwanie komentarza
	 */
	public function deleteAction() {
		$comment = \Cms\Orm\CmsCommentQuery::factory()->findPk($this->id);
		if ($comment && $comment->delete()) {
			$this->getMessenger()->addMessage('Poprawnie usunięto artykuł', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'comment');
	}

}
