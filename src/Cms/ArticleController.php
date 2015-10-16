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
 * Kontroler stron statycznych
 */
class ArticleController extends \Mmi\Mvc\Controller {

	/**
	 * Akcja strony
	 */
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
		//ładowanie z bufora
		if (null === ($article = \App\Registry::$cache->load($cacheKey))) {
			if (isset($uri)) {
				$article = \Cms\Orm\CmsArticleQuery::byUri($uri)->findFirst();
			} else {
				$article = (new \Cms\Orm\CmsArticleQuery)->findPk($id);
			}
			if ($article === null) {
				$this->getResponse()->redirectToUrl('/');
			}
			\App\Registry::$cache->save($article, $cacheKey);
		}
		//opcja noindex
		if ($article->noindex) {
			$this->view->headMeta(['name' => 'robots', 'content' => 'noindex,nofollow']);
		}
		//przekazanie do widoku
		$this->view->article = $article;
		//seo
		$this->view->navigation()->modifyLastBreadcrumb(strip_tags($article->title), $this->view->url(), strip_tags($article->title), strip_tags($article->title . ', ' . mb_substr(strip_tags($article->text), 0, 150) . '...'));
	}

	/**
	 * Widget strony
	 */
	public function widgetAction() {
		$uri = $this->uri;
		$cacheKey = 'Cms-Article-' . $uri;
		if (null === ($article = \App\Registry::$cache->load($cacheKey))) {
			$article = \Cms\Orm\CmsArticleQuery::byUri($uri)
				->findFirst();
			\App\Registry::$cache->save($article, $cacheKey);
		}
		$this->view->article = $article;
	}

}
