<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Controller;

class Log extends Action {

	public function indexAction() {
		$grid = new \Cms\Plugin\LogGrid();
		$this->view->grid = $grid;
	}

	public function errorAction() {
		$logFile = BASE_PATH . '/var/log/error.execution.log';
		$this->view->data = nl2br(file_get_contents($logFile, 0, NULL, filesize($logFile) - 32000));
	}

}
