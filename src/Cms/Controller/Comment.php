<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Controller;

class Comment extends \Mmi\Controller\Action {

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
