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
		$this->view->attributes = (new Model\AttributeValueRelationModel('category', $category->id))->getGrouppedAttributeValues();
		//przekazanie tagów
		$this->view->tags = (new Model\TagRelationModel('cmscategory', $category->id))->getTagRelations();
	}
	
	/**
	 * Akcja prostego widgetu z atrybutami
	 */
	public function widgetAction() {
		$widgetModel = $this->view->widgetModel;
		/* @var $widgetModel \Cms\Model\CategoryWidgetModel */
		//brak widgeta
		if (null === $widgetRelation = $widgetModel->findWidgetRelationById($this->widgetId)) {
			return '';
		}
		//atrybuty do widoku
		$this->view->attributes = (new Model\AttributeValueRelationModel('categoryWidgetRelation', $widgetRelation->id))->getGrouppedAttributeValues();
		//relacja do widoku
		$this->view->widgetRelation = $widgetRelation;
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
		//pobranie typu i parametrów mvc
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
