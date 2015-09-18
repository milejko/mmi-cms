<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Controller;

class Cron extends \Mmi\Controller\Action {

	public function indexAction() {
		\Cms\Model\Cron::run();
		return 'OK';
	}

	public function sendMailAction() {
		if (rand(0, 120) == 12) {
			$this->view->cleared = \Cms\Model\Mail::clean();
		}
		$this->view->result = \Cms\Model\Mail::send();
	}

	public function agregateAction() {
		$this->view->result = \Cms\Model\Stat::agregate();
	}

	public function cleanAction() {
		$months = 24;
		if ($this->months > 0) {
			$months = intval($this->months);
		}
		$this->view->result = \Cms\LogModel::clean($months);
	}

}
