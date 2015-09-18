<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Controller;

class Article extends \Mmi\Controller\Action {

	public function indexAction() {
		//po uri
		if ($this->uri) {
			$uri = $this->uri;
			$cacheKey = 'Cms-Article-' . $uri;
			//po id
		} else {
			$id = intval($this->id);
			$cacheKey = 'Cms-Article-' . $id;
		}
		if (null === ($article = \App\Registry::$cache->load($cacheKey))) {
			if (isset($uri)) {
				$article = \Cms\Orm\Article\Query::byUri($uri)->findFirst();
			} else {
				$article = \Cms\Orm\Article\Query::factory()->findPk($id);
			}
			if ($article === null) {
				$this->getResponse()->redirectToUrl('/');
			}
			\App\Registry::$cache->save($article, $cacheKey);
		}
		if ($article->noindex) {
			$this->view->headMeta(['name' => 'robots', 'content' => 'noindex,nofollow']);
		}
		$this->view->article = $article;
		$this->view->navigation()->modifyLastBreadcrumb(strip_tags($article->title), $this->view->url(), strip_tags($article->title), strip_tags($article->title . ', ' . mb_substr(strip_tags($article->text), 0, 150) . '...'));
	}

	public function widgetAction() {
		$uri = $this->uri;
		$cacheKey = 'Cms-Article-' . $uri;
		if (null === ($article = \App\Registry::$cache->load($cacheKey))) {
			$article = \Cms\Orm\Article\Query::byUri($uri)
				->findFirst();
			\App\Registry::$cache->save($article, $cacheKey);
		}
		$this->view->article = $article;
	}

}
