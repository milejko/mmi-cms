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
 * Kontroler newsów
 */
class NewsController extends \Mmi\Mvc\Controller {

	/**
	 * Lista aktualności
	 */
	public function indexAction() {
		//przekierowanie z posta z ilością podstron
		if ($this->getPost()->pages) {
			$this->getResponse()->redirect('cms', 'news', 'index', ['pages' => intval($this->getPost()->pages)]);
		}
		$paginator = new \Mmi\Paginator\Paginator();
		$pages = 10;
		//ustawianie ilości stron na liście
		if ($this->pages) {
			if ($this->pages % 10 != 0) {
				$this->getResponse()->redirect('cms', 'news', 'index');
			}
			$pages = (int) $this->pages;
		}
		$paginator->setRowsPerPage($pages);
		$paginator->setRowsCount(\Cms\Orm\CmsNewsQuery::active()->count());
		$this->view->news = \Cms\Orm\CmsNewsQuery::active()
			->limit($paginator->getLimit())
			->offset($paginator->getOffset())
			->find();
		$this->view->paginator = $paginator;
	}

	/**
	 * Lista top aktualności
	 */
	public function topAction() {
		$limit = $this->limit ? intval($this->limit) : 5;
		$this->view->news = \Cms\Orm\CmsNewsQuery::active()
			->limit($limit)
			->find();
	}

	/**
	 * Wyświetlenie aktualności
	 */
	public function displayAction() {
		$this->view->item = \Cms\Orm\CmsNewsQuery::activeByUri($this->uri)
			->findFirst();
		if ($this->view->item === null) {
			$this->getResponse()->redirect('cms', 'news', 'index');
		}
		$this->view->navigation()->modifyLastBreadcrumb($this->view->item->title, $this->view->url());
	}

}
