<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Controller;

class Mail extends Action {

	/**
	 * Kolejka maili
	 */
	public function indexAction() {
		$this->view->grid = new \Cms\Plugin\MailGrid();
	}

	/**
	 * Usuniecie maila
	 */
	public function deleteAction() {
		$mail = \Cms\Orm\Mail\Query::factory()->findPk($this->id);
		if ($mail && $mail->delete()) {
			$this->getHelperMessenger()->addMessage('Email został usunięty z kolejki', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'mail', 'index');
	}

	/**
	 * Podglad treści maila
	 */
	public function previewAction() {
		$mail = \Cms\Orm\Mail\Query::factory()->findPk($this->id);
		$this->view->message = $mail->message;
	}
	
	/**
	 * Wysyłka z kolejki
	 */
	public function sendAction() {
		$result = \Cms\Model\Mail::send();
		if ($result['success'] > 0) {
			$this->getHelperMessenger()->addMessage('Maile z kolejki zostały wysłane', true);
		}
		if ($result['error'] > 0) {
			$this->getHelperMessenger()->addMessage('Przy wysyłaniu wystąpiły błędy', false);
		}
		if ($result['success'] + $result['error'] == 0) {
			$this->getHelperMessenger()->addMessage('Brak maili do wysyłki');
		}
		$this->getResponse()->redirect('cmsAdmin', 'mail', 'index');
	}

}
