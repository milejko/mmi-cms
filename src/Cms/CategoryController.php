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
		//rekord kategorii do widoku
		$this->view->category = $category;
		//wczytanie zbuforowanej strony (dla niezalogowanych)
		if (!\App\Registry::$auth->hasIdentity() && (null !== $html = \App\Registry::$cache->load($cacheKey = 'category-html-' . $category->id))) {
			//wysyłanie nagłówka o buforowaniu strony
			$this->getResponse()->setHeader('X-Cache', 'HIT');
			//zwrot html
			return $html;
		}
		//przekazanie rekordu kategorii do widoku
		$this->view->category = $category;
		//renderowanie docelowej akcji
		$html = \Mmi\Mvc\ActionHelper::getInstance()->forward($this->_prepareForwardRequest($category));
		//jeśli zalogowany, lub bufor wyłączony
		if (\App\Registry::$auth->hasIdentity() || (0 == $cacheLifetime = $category->cacheLifetime)) {
			//zwrot html
			return $html;
		}
		//iteracja po widgetach
		foreach ($category->getWidgetModel()->getWidgetRelations() as $widgetRelation) {
			//bufor wyłączony przez widget
			if (0 == $widgetCacheLifetime = $widgetRelation->getWidgetRecord()->cacheLifetime) {
				//zwrot html
				return $html;
			}
			//wpływ widgeta na czas buforowania kategorii
			$cacheLifetime = ($cacheLifetime > $widgetCacheLifetime) ? $widgetCacheLifetime : $cacheLifetime;
		}
		//zapis html kategorii do cache
		\App\Registry::$cache->save($html, $cacheKey, $cacheLifetime);
		//zwrot html
		return $html;
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
		//inicjalizacja zmiennej
		$category = null;
		//próba mapowania uri na ID kategorii z cache
		if (null === $categoryId = \App\Registry::$cache->load($cacheKey = 'category-id-' . md5($uri))) {
			//próba pobrania kategorii po URI
			if (null === $category = (new Orm\CmsCategoryQuery)->getCategoryByUri($uri)) {
				//zapis informacji o braku kategorii w cache 
				\App\Registry::$cache->save('-1', $cacheKey, 0);
				//404
				throw new \Mmi\Mvc\MvcNotFoundException('Category not found: ' . $uri);
			}
			//id kategorii
			$categoryId = $category->id;
			//zapis id kategorii i kategorii w cache 
			\App\Registry::$cache->save($categoryId, $cacheKey, 0) && \App\Registry::$cache->save($category, 'category-' . $categoryId, 0);
		}
		//w buforze jest informacja o braku strony
		if ($categoryId == -1) {
			//404
			throw new \Mmi\Mvc\MvcNotFoundException('Category not found: ' . $uri);
		}
		//kategoria
		if ($category) {
			return $this->_checkCategory($category);
		}
		//pobranie kategorii z bufora
		if (null === $category = \App\Registry::$cache->load($cacheKey = 'category-' . $categoryId)) {
			//zapis pobranej kategorii w cache
			\App\Registry::$cache->save($category = (new Orm\CmsCategoryQuery)->withType()->findPk($categoryId), $cacheKey, 0);
		}
		//sprawdzanie kategorii
		return $this->_checkCategory($category);
	}
	
	/**
	 * Sprawdza aktywność kategorii do wyświetlenia
	 * przekierowuje na 404 i na inne strony (zgodnie z redirectUri)
	 * @param \Cms\Orm\CmsCategoryRecord $category
	 * @throws \Mmi\Mvc\MvcNotFoundException
	 * @return \Cms\Orm\CmsCategoryRecord $category
	 */
	protected function _checkCategory(\Cms\Orm\CmsCategoryRecord $category) {
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
			throw new \Mmi\Mvc\MvcNotFoundException('Category not active: ' . $uri);
		}
		//nie osiągnięto czasu publikacji
		if (null !== $category->dateStart && $category->dateStart > date('Y-m-d H:i:s')) {
			//404
			throw new \Mmi\Mvc\MvcNotFoundException('Category not yet published: ' . $uri);
		}
		//przekroczono czas publikacji
		if (null !== $category->dateEnd && $category->dateEnd < date('Y-m-d H:i:s')) {
			//404
			throw new \Mmi\Mvc\MvcNotFoundException('Category expired: ' . $uri);
		}
		//kategoria posiada customUri, a wejście jest na natywny uri
		if ($category->customUri && $this->uri == $category->uri) {
			//przekierowanie na customUri
			$this->getResponse()->redirect('cms', 'category', 'dispatch', ['uri' => $category->customUri]);
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
