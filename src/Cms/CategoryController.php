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
		//próba pobrania kategorii z cache
		if (null === $category = \App\Registry::$cache->load($cacheKey = 'category-' . md5($this->uri))) {
			//pobranie kategorii
			$category = $this->_getPublishedCategoryByUri($this->uri);
			//zapis cache
			\App\Registry::$cache->save($category, $cacheKey);
		}
		//kategoria posiada customUri, a wejście jest na natywny uri
		if ($category->customUri && $this->uri == $category->uri) {
			//przekierowanie na customUri
			$this->getResponse()->redirect('cms', 'category', 'dispatch', ['uri' => $category->customUri]);
		}
		//rekord kategorii do widoku
		$this->view->category = $category;
		//forward do akcji docelowej
		return \Mmi\Mvc\ActionHelper::getInstance()->forward($this->_prepareForwardRequest($category));
	}

	/**
	 * Akcja artykułu
	 */
	public function articleAction() {
		
	}

	/**
	 * Akcja prostego widgetu z atrybutami
	 */
	public function widgetAction() {
		//brak kategorii
		if (!$this->view->category) {
			//pobranie kategorii
			$this->view->category = new Orm\CmsCategoryRecord($this->id);
		}
		//wyszukiwanie widgeta
		if (null === $this->view->widgetRelation = $this->view->category->getWidgetModel()->findWidgetRelationById($this->widgetId)) {
			//brak - pusty zwrot
			return '';
		}
	}

	/**
	 * Pobiera opublikowaną kategorię po uri
	 * @param string $uri
	 * @return \Cms\Orm\CmsCategoryRecord
	 * @throws \Mmi\Mvc\MvcNotFoundException
	 */
	protected function _getPublishedCategoryByUri($uri) {
		//wyszukanie kategorii
		if ((null === $category = (new Model\CategoryModel)
			->getCategoryByUri($uri))) {
			//404
			throw new \Mmi\Mvc\MvcNotFoundException('Category not found: ' . $uri);
		}
		//kategoria to przekierowanie
		if ($category->redirectUri) {
			//przekierowanie na uri
			$this->getResponse()->redirectToUrl($category->redirectUri);
		}
		//kategoria dozwolona - flaga podglądu + rola redaktora
		if ($this->preview == 1 && \App\Registry::$acl->isAllowed(\App\Registry::$auth->getRoles(), 'cmsAdmin:category:index')) {
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
	protected function _prepareForwardRequest(\Cms\Orm\CmsCategoryRecord $category) {
		//tworzenie nowego requestu na podstawie obecnego
		$request = clone $this->getRequest();
		$request->setModuleName('cms')
			->setControllerName('category')
			->setActionName('article');
		//przekierowanie MVC
		if ($category->mvcParams) {
			//tablica z tpl
			$mvcParams = [];
			//parsowanie parametrów mvc
			parse_str($category->mvcParams, $mvcParams);
			return $request->setParams($mvcParams);
		}
		//pobranie typu (szablonu) i jego parametrów mvc
		if (!$category->getJoined('cms_category_type')->mvcParams) {
			return $request;
		}
		//tablica z tpl
		$mvcParams = [];
		//parsowanie parametrów mvc
		parse_str($category->getJoined('cms_category_type')->mvcParams, $mvcParams);
		return $request->setParams($mvcParams);
	}

}
