<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Controller;

class Page extends \Mmi\Controller\Action {

	public function indexAction() {
		if (!$this->id || null === ($page = \Cms\Model\Page::firstById($this->id))) {
			$this->getResponse()->redirectToUrl('/');
		}
		/* @var $page \Cms\Orm\Page\Record */
		$this->view->content = $page->text;
	}

}
