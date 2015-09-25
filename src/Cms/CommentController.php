<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

/**
 * Kontroler komentarzy
 */
class CommentController extends \Mmi\Mvc\Controller {

	public function indexAction() {
		if (!$this->object) {
			return;
		}
		if (!$this->objectId) {
			return;
		}
		$this->view->comments = \Cms\Orm\Comment\Query::byObject($this->object, $this->objectId, $this->descending)
			->limit(100)
			->find();

		if (!($this->allowGuests || \App\Registry::$auth->hasIdentity())) {
			return;
		}
		$form = new \Cms\Form\Comment(new \Cms\Orm\Comment\Record(), [
			'object' => $this->object,
			'objectId' => $this->objectId
		]);
		if ($form->isSaved()) {
			$this->getHelperMessenger()->addMessage('Dodano komentarz', true);
			$this->getResponse()->redirectToUrl($this->getRequest()->getReferer());
		}
		$this->view->commentForm = $form;
	}

}
