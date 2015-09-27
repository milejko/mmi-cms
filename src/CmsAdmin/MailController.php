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
 * Kontroler poczty
 */
class MailController extends Mvc\Controller {

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
			$this->getMessenger()->addMessage('Email został usunięty z kolejki', true);
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
			$this->getMessenger()->addMessage('Maile z kolejki zostały wysłane', true);
		}
		if ($result['error'] > 0) {
			$this->getMessenger()->addMessage('Przy wysyłaniu wystąpiły błędy', false);
		}
		if ($result['success'] + $result['error'] == 0) {
			$this->getMessenger()->addMessage('Brak maili do wysyłki');
		}
		$this->getResponse()->redirect('cmsAdmin', 'mail', 'index');
	}

}
