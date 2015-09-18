<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Controller;

class Contact extends \Mmi\Controller\Action {

	public function indexAction() {
		//ciasteczko sesyjne - zapamietanie sciezki
		$namespace = new \Mmi\Session\Space('contact');
		//formularz kontaktowy z rekordem kontaktu
		$form = new \Cms\Form\Contact(new \Cms\Orm\Contact\Record(), [
			'subjectId' => $this->subjectId
		]);
		//do widoku
		$this->view->contactForm = $form;
		//zapis
		if ($form->isSaved()) {
			$this->getHelperMessenger()->addMessage('Wiadomość wysłano poprawnie.', true);
			if ($namespace->referer) {
				$link = $namespace->referer;
			} else {
				$link = $this->view->url();
			}
			$namespace->unsetAll();
			$this->getResponse()->redirectToUrl($link);
		} elseif (\Mmi\Controller\Front::getInstance()->getEnvironment()->httpReferer) {
			$namespace->referer = \Mmi\Controller\Front::getInstance()->getEnvironment()->httpReferer;
		}
	}

}
