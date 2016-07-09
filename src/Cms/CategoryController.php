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
 * Kontroler kategorii
 */
class CategoryController extends \Mmi\Mvc\Controller {

	/**
	 * Akcja rozdzielenia treści
	 */
	public function dispatchAction() {
		//wyszukanie kategorii
		if (null === $category = (new Model\CategoryModel)
			->getCategoryByUri($this->uri)) {
			//przekierowanie
			$this->getResponse()->redirectToUrl('/');
		}
		$type = $category->getJoined('cms_category_type');
		dump($type);
		exit;
		//wywołanie akcji
		$this->displayCategoryAction();
		//render akcji kategorii
		return $this->view->setPlaceholder('content', $this->view->renderTemplate('cms', 'article', 'displayCategory'))
				->renderLayout('cms', 'article');
	}

	public function articleAction() {
		//iteracja po dzieciach kategorii
		foreach ($category->getOption('parents') as $cat) {
			//dodawanie okruszka
			$this->view->navigation()->appendBreadcrumb($cat->name, $this->view->url(['path' => $cat->uri]));
		}
		$this->view->navigation()->appendBreadcrumb($category->name, $this->view->url(['path' => $category->uri]));
		//przekazanie kategorii
		$this->view->category = $category;
	}

}
