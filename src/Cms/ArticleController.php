<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

/**
 * Kontroler stron statycznych
 */
class ArticleController extends \Mmi\Mvc\Controller {

	/**
	 * Akcja wyświetlania artykułu
	 */
	public function displayAction() {
		//wyszukanie kategorii
		$this->_category = (new Model\CategoryModel)
			->getCategoryByUri($this->path);
		//forward do kategorii
		if ($this->_category !== null) {
			//wywołanie akcji
			$this->displayCategoryAction();
			//render akcji kategorii
			return $this->view->setPlaceholder('content', $this->view->renderTemplate('cms', 'article', 'displayCategory'))
				->renderLayout('cms', 'article');
		}
		//wyszykanie artykułu
		$article = (new Model\ArticleModel)
			->searchByPath($this->path);
		//nie znaleziono artykułu
		if (null === $article) {
			//przekierowanie
			$this->getResponse()->redirectToUrl('/');
		}
		//artykuł ma podpiętą kategorię
		if ($article->getOption('category') !== null) {
			//iteracja po dzieciach kategorii
			foreach ($article->getOption('category')->getOption('parents') as $category) {
				//dodawanie okruszka
				$this->view->navigation()->appendBreadcrumb($category->name, $this->view->url(['path' => $category->uri]));
			}
			//dodawanie okruszka najwyższej kategorii
			$this->view->navigation()->appendBreadcrumb($article->getOption('category')->name, $this->view->url(['path' => $article->getOption('category')->uri]));
		}
		//dodawanie okruszka
		$this->view->navigation()->appendBreadcrumb($article->title, $this->view->url(), $article->title, mb_substr($article->lead . $article->text, 0, 150) . '...');
		$this->view->article = $article;
	}

	/**
	 * Wyświetlenie kategorii
	 */
	public function displayCategoryAction() {
		//brak kategorii
		if (null === $this->_category) {
			//przekierowanie
			$this->getResponse()->redirectToUrl('/');
		}
		//iteracja po dzieciach kategorii
		foreach ($this->_category->getOption('parents') as $category) {
			//dodawanie okruszka
			$this->view->navigation()->appendBreadcrumb($category->name, $this->view->url(['path' => $category->uri]));
		}
		$this->view->navigation()->appendBreadcrumb($this->_category->name, $this->view->url(['path' => $this->_category->uri]));
		//przekazanie kategorii
		$this->view->category = $this->_category;
	}

}
