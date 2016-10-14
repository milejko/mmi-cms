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
	 * Akcja dispatchera kategorii
	 */
	public function dispatchAction() {
		//pobranie kategorii
		$category = $this->_getPublishedCategoryByUri($this->uri);
		//kategoria posiada customUri, a wejście jest na natywny uri
		if ($category->customUri && $this->uri == $category->uri) {
			//przekierowanie na customUri
			$this->getResponse()->redirect('cms', 'category', 'dispatch', ['uri' => $category->customUri]);
		}
		//tworzy parametry SEO
		$this->_buildSeoParams($category);
		//model widgetu do widoku
		$this->view->widgetModel = new Model\CategoryWidgetModel($category->id);
		//forward do akcji docelowej
		return \Mmi\Mvc\ActionHelper::getInstance()->forward($this->_prepareForwardRequest($category));
	}

	/**
	 * Akcja artykułu
	 */
	public function articleAction() {
		//pobranie kategorii z modelu
		$category = $this->view->widgetModel->getCategoryRecord();
		//przekazanie atrybutów
		$this->view->attributes = (new Model\AttributeValueRelationModel('category', $category->id))->getAttributeValues();
		//przekazanie tagów
		$this->view->tags = (new Model\TagRelationModel('cmscategory', $category->id))->getTagRelations();
	}

	/**
	 * Pobiera opublikowaną kategorię po uri
	 * @param string $uri
	 * @return \Cms\Orm\CmsCategoryRecord
	 * @throws \Mmi\Mvc\MvcNotFoundException
	 */
	private function _getPublishedCategoryByUri($uri) {
		//wyszukanie kategorii
		if ((null === $category = (new Model\CategoryModel)
			->getCategoryByUri($uri))) {
			//404
			throw new \Mmi\Mvc\MvcNotFoundException('Category not found: ' . $uri);
		}
		//kategoria dozwolona - rola redaktora
		if (\App\Registry::$acl->isAllowed(\App\Registry::$auth->getRoles(), 'cmsAdmin:category:index')) {
			return $category;
		}
		//kategoria manualnie wyłączona
		if (!$category->active) {
			//404
			throw new \Mmi\Mvc\MvcNotFoundException('Category not found: ' . $uri);
		}
		//nie osiągnięto czasu publikacji
		if (null !== $category->dateStart && $category->dateStart > date('Y-m-d H:i:s')) {
			//404
			throw new \Mmi\Mvc\MvcNotFoundException('Category not found: ' . $uri);
		}
		//przekroczono czas publikacji
		if (null !== $category->dateEnd && $category->dateEnd < date('Y-m-d H:i:s')) {
			//404
			throw new \Mmi\Mvc\MvcNotFoundException('Category not found: ' . $uri);
		}
		//opublikowana kategoria
		return $category;
	}

	/**
	 * Pobiera request do przekierowania
	 * @param \Cms\Orm\CmsCategoryRecord $category
	 * @return \Mmi\Http\Request
	 * @throws \Mmi\App\KernelException
	 */
	private function _prepareForwardRequest(\Cms\Orm\CmsCategoryRecord $category) {
		//tworzenie nowego requestu na podstawie obecnego
		$request = clone $this->getRequest();
		$request->setModuleName('cms')
			->setControllerName('category')
			->setActionName('article');
		//pobranie typu i ustalenie template
		if (!$category->getJoined('cms_category_type')->template) {
			return $request;
		}
		//tablica z tpl
		$mcaArr = explode('/', $category->getJoined('cms_category_type')->template);
		//zła ilość argumentów
		if (count($mcaArr) != 3) {
			throw new \Mmi\App\KernelException('Template invalid: "' . $category->getJoined('cms_category_type')->template . '"');
		}
		//ustawienie request
		return $request->setModuleName($mcaArr[0])
				->setControllerName($mcaArr[1])
				->setActionName($mcaArr[2]);
	}

	/**
	 * Buduje parametry SEO
	 * @param \Cms\Orm\CmsCategoryRecord $category
	 */
	private function _buildSeoParams(\Cms\Orm\CmsCategoryRecord $category) {
		//iteracja po dzieciach kategorii
		foreach ($category->getOption('parents') as $cat) {
			//brak widoczności w menu
			if (!$cat->active) {
				continue;
			}
			//dodawanie okruszka
			$this->view->navigation()->appendBreadcrumb($cat->name, $this->view->url(['uri' => $cat->uri]), $cat->title ? $cat->title : $cat->name, $cat->description ? $cat->description : $cat->lead);
		}
		//dodawanie okruszka z kategorią główną
		$this->view->navigation()->appendBreadcrumb($category->name, $this->view->url(['uri' => $category->uri]), $category->title ? $category->title : $category->name, $category->description ? $category->description : $category->lead);
	}

}
