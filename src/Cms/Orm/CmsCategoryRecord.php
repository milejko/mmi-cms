<?php

namespace Cms\Orm;

use \Cms\Model\AttributeValueRelationModel,
	\Cms\Model\AttributeRelationModel;

/**
 * Rekord kategorii CMSowych
 */
class CmsCategoryRecord extends \Mmi\Orm\Record {

	public $id;

	/**
	 * Identyfikator szablonu
	 * @var integer
	 */
	public $cmsCategoryTypeId;
	public $lang;

	/**
	 * Nazwa pola
	 * @var string
	 */
	public $name;

	/**
	 * Breadcrumbs
	 * @var string
	 */
	public $uri;

	/**
	 * Opcjonalny adres strony
	 * @var string
	 */
	public $customUri;
	public $mvcParams;
	public $redirectUri;

	/**
	 * Identyfikator rodzica
	 * @var integer
	 */
	public $parentId;

	/**
	 * Kolejność elementów
	 * @var integer
	 */
	public $order;
	public $dateAdd;
	public $dateModify;
	
	/**
	 * Tytuł SEO
	 * @var string
	 */
	public $title;
	
	/**
	 * Opis SEO
	 * @var string
	 */
	public $description;
	
	/**
	 * null - bez zmiany, true - https, false - http
	 * @var string
	 */
	public $https;
	
	/**
	 * Bez flagi nofollow
	 * @var boolean
	 */
	public $follow;
	
	/**
	 * Nowe okno
	 * @var boolean
	 */
	public $blank;

	/**
	 * Data dodania
	 * @var string
	 */
	public $dateStart;
	
	/**
	 * Data modyfikacji
	 * @var string
	 */
	public $dateEnd;
	public $active;

	/**
	 * Model widgetów kategorii
	 * @var \Cms\Model\CategoryWidgetModel
	 */
	private $_widgetModel;

	/**
	 * Rekord rodzica
	 * @var \Cms\Orm\CmsCategoryRecord
	 */
	private $_parentRecord;

	/**
	 * Zapis rekordu
	 * @return boolean
	 */
	public function save() {
		//usunięcie uri
		$this->uri = '';
		//ustawiamy uri na podstawie rodzica
		if ($this->parentId && (null !== $parent = (new CmsCategoryQuery)->findPk($this->parentId))) {
			//nieaktywny rodzic -> nie wlicza się do ścieżki
			if (!$parent->active) {
				$parent->uri = substr($parent->uri, 0, strrpos($parent->uri, '/'));
			}
			$this->uri = ltrim($parent->uri . '/', '/');
		}
		//doklejanie do uri przefiltrowanej końcówki
		$this->uri .= (new \Mmi\Filter\Url)->filter($this->name);
		//domyślnie wstawienie na koniec
		if (null === $this->order) {
			$this->order = $this->_maxChildOrder() + 1;
		}
		//usuwanie cache po zapisie
		\App\Registry::$cache->remove('Mmi-Navigation-' . $this->lang);
		//zapis
		return parent::save();
	}

	/**
	 * Wstawienie kategorii z obliczeniem kodu i przebudową drzewa
	 * @return boolean
	 */
	protected function _insert() {
		//data aktualizacji
		$this->dateAdd = date('Y-m-d H:i:s');
		//próba utworzenia rekordu
		return parent::_insert();
	}

	/**
	 * Aktualizacja kategorii
	 * @return boolean
	 */
	protected function _update() {
		\App\Registry::$cache->remove('category-attributes-' . $this->id);
		//zmodyfikowany szablon
		if ($this->isModified('cmsCategoryTypeId')) {
			//iteracja po różnicy międy obecnymi atrybutami a nowymi
			foreach (array_diff(
				//obecne id atrybutów
				(new AttributeRelationModel('cmsCategoryType', $this->getInitialStateValue('cmsCategoryTypeId')))->getAttributeIds(),
				//nowe id atrybutów
				(new AttributeRelationModel('cmsCategoryType', $this->cmsCategoryTypeId))->getAttributeIds())
			as $deletedAttributeId) {
				//usuwanie wartości usuniętego atrybutu
				(new AttributeValueRelationModel('category', $this->id))
					->deleteAttributeValueRelationsByAttributeId($deletedAttributeId);
			}
		}
		//zmodyfikowany parent
		$parentModified = $this->isModified('parentId');
		//zmodyfikowany order
		$orderModified = $this->isModified('order');
		//data modyfikacji
		$this->dateModify = date('Y-m-d H:i:s');
		//aktualizacja rekordu
		if (!parent::_update()) {
			return false;
		}
		//sortowanie dzieci po przestawieniu rodzica, lub kolejności
		if (($parentModified || $orderModified) && !$this->getOption('block-ordering')) {
			//sortuje dzieci
			$this->_sortChildren();
		}
		//przebudowa dzieci
		$this->_rebuildChildren($this->id);
		return true;
	}

	/**
	 * Kasowanie obiektu
	 * @return boolean
	 * @throws \Cms\Exception\ChildrenExistException
	 */
	public function delete() {
		if ($this->getPk() === null) {
			return false;
		}
		//pobranie dzieci
		$children = (new \Cms\Model\CategoryModel)->getCategoryTree($this->getPk());
		if (!empty($children)) {
			throw new \Cms\Exception\ChildrenExistException();
		}
		//usuwanie cache
		\App\Registry::$cache->remove('Mmi-Navigation-' . $this->lang);
		//usuwanie kategorii
		return parent::delete();
	}

	/**
	 * Pobiera url kategorii
	 * @param boolean $https true - tak, false - nie, null - bez zmiany protokołu
	 */
	public function getUrl($https = null) {
		//pobranie linku z widoku
		return \Mmi\App\FrontController::getInstance()->getView()->url(['module' => 'cms', 'controller' => 'category', 'action' => 'dispatch', 'uri' => $this->customUri ? $this->customUri : $this->uri], true, $https);
	}

	/**
	 * Pobiera rekordy wartości atrybutów w formie obiektu danych
	 * @see \Mmi\DataObiect
	 * @return \Mmi\DataObject
	 */
	public function getAttributeValues() {
		//próba pobrania atrybutów z cache
		if (null === $attributeValues = \App\Registry::$cache->load($cacheKey = 'category-attributes-' . $this->id)) {
			//pobieranie atrybutów
			\App\Registry::$cache->save($attributeValues = (new \Cms\Model\AttributeValueRelationModel('category', $this->id))->getGrouppedAttributeValues(), $cacheKey);
		}
		//zwrot atrybutów
		return $attributeValues;
	}

	/**
	 * Pobiera model widgetów
	 * @return \Cms\Model\CategoryWidgetModel
	 */
	public function getWidgetModel() {
		//model widgetów już pobrany
		if (null !== $this->_widgetModel) {
			return $this->_widgetModel;
		}
		//tworzenie modelu widgetów
		return $this->_widgetModel = new \Cms\Model\CategoryWidgetModel($this->id);
	}

	/**
	 * Pobiera rekord rodzica
	 * @return \Cms\Orm\CmsCategoryRecord
	 */
	public function getParentRecord() {
		//rekord już pobrany
		if (null !== $this->_parentRecord) {
			return $this->_parentRecord;
		}
		//wyszukanie rekordu
		return $this->_parentRecord = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->parentId);
	}
	
	/**
	 * Pobiera rodzeństwo elementu (wraz z nim samym)
	 * @return \Cms\Orm\CmsCategoryRecord[]
	 */
	public function getSiblings() {
		return $this->_getChildren($this->parentId);
	}

	/**
	 * Przebudowuje dzieci (wywołuje save)
	 * @param integer $parentId rodzic
	 */
	protected function _rebuildChildren($parentId) {
		$i = 0;
		//iteracja po dzieciach
		foreach ($this->_getChildren($parentId) as $categoryRecord) {
			//wyznaczanie kolejności
			$categoryRecord->order = $i++;
			$categoryRecord->setOption('block-ordering', true);
			//zapis dziecka
			$categoryRecord->save();
			//zejście rekurencyjne
			$this->_rebuildChildren($categoryRecord->id);
		}
	}

	/**
	 * Zwraca dzieci danego rodzica
	 * @param integer $parentId id rodzica
	 * @return \Cms\Orm\CmsCategoryRecord[]
	 */
	protected function _getChildren($parentId) {
		//zapytanie wyszukujące dzieci (z sortowaniem)
		return (new CmsCategoryQuery)
				->whereParentId()->equals($parentId)
				->join('cms_category_type')->on('cms_category_type_id')
				->orderAscOrder()
				->orderAscId()
				->find()
				->toObjectArray();
	}

	/**
	 * Wyszukuje maksymalną wartość kolejności w dzieciach wybranego rodzica
	 * @param integer $parentId id rodzica
	 * @return integer
	 */
	protected function _maxChildOrder() {
		//wyszukuje maksymalny order
		$maxOrder = (new CmsCategoryQuery)
			->whereParentId()->equals($this->parentId)
			->findMax('order');
		//będzie inkrementowany
		return $maxOrder === null ? -1 : $maxOrder;
	}

	/**
	 * Sortuje dzieci wybranego rodzica
	 * @param integer $parentId rodzic
	 */
	protected function _sortChildren() {
		$children = $this->_getChildren($this->parentId);
		//usuwanie bieżącej kategorii
		foreach ($children as $key => $categoryRecord) {
			if ($categoryRecord->id == $this->id) {
				unset($children[$key]);
			}
		}
		//sklejanie kategorii
		$ordered = array_merge(array_slice($children, 0, $this->order, true), [$this->order => $this], array_slice($children, $this->order, count($children), true));
		$i = 0;
		//ustawianie orderów
		foreach ($ordered as $key => $categoryRecord) {
			//wyznaczanie kolejności
			$categoryRecord->order = $i++;
			$categoryRecord->setOption('block-ordering', true);
			//zapis dziecka
			$categoryRecord->save();
		}
	}

}
