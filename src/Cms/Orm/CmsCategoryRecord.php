<?php

namespace Cms\Orm;

use Cms\Model\AttributeRelationModel;
use Cms\Model\AttributeValueRelationModel;

/**
 * Rekord kategorii CMSowych
 */
class CmsCategoryRecord extends \Mmi\Orm\Record
{

    //domyślna długość bufora
    const DEFAULT_CACHE_LIFETIME = 2592000;
    //interwały buforów
    const CACHE_LIFETIMES = [2592000 => 'po zmianie', 0 => 'zawsze', 60 => 'co minutę', 300 => 'co 5 minut', 600 => 'co 10 minut', 3600 => 'co godzinę', 28800 => 'co 8 godzin', 86400 => 'raz na dobę'];

    /**
     * Identyfikator
     * @var integer
     */
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
     * JSON konfiguracyjny
     * @var string
     */
    public $configJson;

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

    /**
     * Czas życia bufora
     * @var integer
     */
    public $cacheLifetime;
    public $active;

    /**
     * Zapis rekordu
     * @return boolean
     */
    public function save()
    {
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
        //zapis
        return $this->clearCache() && parent::save() && $this->clearCache();
    }

    /**
     * Wstawienie kategorii z obliczeniem kodu i przebudową drzewa
     * @return boolean
     */
    protected function _insert()
    {
        //usunięcie cache uprawnień
        \App\Registry::$cache->remove('mmi-cms-category-acl');
        //data aktualizacji
        $this->dateAdd = date('Y-m-d H:i:s');
        //próba utworzenia rekordu
        return parent::_insert();
    }

    /**
     * Aktualizacja kategorii
     * @return boolean
     */
    protected function _update()
    {
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
        //zmodyfikowana nazwa
        $nameModified = $this->isModified('name');
        //zmodyfikowana aktywność
        $activeModified = $this->isModified('active');
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
        if ($nameModified || $activeModified) {
            $this->_rebuildChildren($this->id);
        }
        return true;
    }

    /**
     * Kasowanie obiektu
     * @return boolean
     * @throws \Cms\Exception\ChildrenExistException
     */
    public function delete()
    {
        if ($this->getPk() === null) {
            return false;
        }
        //pobranie dzieci
        $children = (new \Cms\Model\CategoryModel)->getCategoryTree($this->getPk());
        if (!empty($children)) {
            throw new \Cms\Exception\ChildrenExistException();
        }
        //usuwanie kategorii
        return parent::delete() && $this->clearCache();
    }

    /**
     * Pobiera url kategorii
     * @param boolean $https true - tak, false - nie, null - bez zmiany protokołu
     * @return string
     */
    public function getUrl($https = null)
    {
        //pobranie linku z widoku
        return \Mmi\App\FrontController::getInstance()->getView()->url(['module' => 'cms', 'controller' => 'category', 'action' => 'dispatch', 'uri' => $this->customUri ? $this->customUri : $this->uri], true, $https);
    }

    /**
     * Pobiera rekordy wartości atrybutów w formie obiektu danych
     * @see \Mmi\DataObiect
     * @return \Mmi\DataObject
     */
    public function getAttributeValues()
    {
        //próba pobrania atrybutów z cache
        if (null === $attributeValues = \App\Registry::$cache->load($cacheKey = 'category-attributes-' . $this->id)) {
            //pobieranie atrybutów
            \App\Registry::$cache->save($attributeValues = (new \Cms\Model\AttributeValueRelationModel('category', $this->id))->getGrouppedAttributeValues(), $cacheKey, 0);
        }
        //zwrot atrybutów
        return $attributeValues;
    }

    /**
     * Pobiera model widgetów
     * @return \Cms\Model\CategoryWidgetModel
     */
    public function getWidgetModel()
    {
        //próba pobrania modelu widgetu z cache
        if (null === $widgetModel = \App\Registry::$cache->load($cacheKey = 'category-widget-model-' . $this->id)) {
            //pobieranie modelu widgetu
            \App\Registry::$cache->save($widgetModel = new \Cms\Model\CategoryWidgetModel($this->id), $cacheKey, 0);
        }
        //zwrot atrybutów
        return $widgetModel;
    }

    /**
     * Pobiera rekord rodzica
     * @return \Cms\Orm\CmsCategoryRecord
     */
    public function getParentRecord()
    {
        //próba pobrania rodzica z cache
        if (null === $parent = \App\Registry::$cache->load($cacheKey = 'category-' . $this->parentId)) {
            //pobieranie rodzica
            \App\Registry::$cache->save($parent = (new \Cms\Orm\CmsCategoryQuery)
                ->withType()
                ->findPk($this->parentId), $cacheKey, 0);
        }
        //zwrot rodzica
        return $parent;
    }

    /**
     * Pobiera rodzeństwo elementu (wraz z nim samym)
     * @return \Cms\Orm\CmsCategoryRecord[]
     */
    public function getSiblings()
    {
        //próba pobrania dzieci z cache
        if (null === $siblings = \App\Registry::$cache->load($cacheKey = 'category-siblings-' . $this->parentId)) {
            //pobieranie dzieci
            \App\Registry::$cache->save($siblings = $this->_getChildren($this->parentId), $cacheKey);
        }
        return $siblings;
    }

    /**
     * Zwraca konfigurację
     * @return \Mmi\DataObject
     */
    public function getConfig()
    {
        //próba dekodowania konfiguracji json
        try {
            $configArr = \json_decode($this->configJson, true);
        } catch (\Exception $e) {
            \Mmi\App\FrontController::getInstance()->getLogger()->warning('Unable to decode category configJson #' . $this->id);
        }
        //tworznie pustego configa
        if (!isset($configArr)) {
            $configArr = [];
        }
        $config = (new \Mmi\DataObject())->setParams($configArr);
        return $config;
    }

    /**
     * Przebudowuje dzieci (wywołuje save)
     * @param integer $parentId rodzic
     */
    protected function _rebuildChildren($parentId)
    {
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
    protected function _getChildren($parentId)
    {
        return (new CmsCategoryQuery)
            ->whereParentId()->equals($parentId)
            ->joinLeft('cms_category_type')->on('cms_category_type_id')
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
    protected function _maxChildOrder()
    {
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
    protected function _sortChildren()
    {
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

    /**
     * Usuwa cache
     */
    public function clearCache()
    {
        //usuwanie cache
        \App\Registry::$cache->remove('mmi-cms-navigation-' . $this->lang);
        \App\Registry::$cache->remove('category-' . $this->id);
        \App\Registry::$cache->remove('category-html-' . $this->id);
        \App\Registry::$cache->remove('category-id-' . md5($this->uri));
        \App\Registry::$cache->remove('category-id-' . md5($this->getInitialStateValue('uri')));
        \App\Registry::$cache->remove('category-id-' . md5($this->customUri));
        \App\Registry::$cache->remove('category-id-' . md5($this->getInitialStateValue('customUri')));
        \App\Registry::$cache->remove('category-attributes-' . $this->id);
        \App\Registry::$cache->remove('category-widget-model-' . $this->id);
        \App\Registry::$cache->remove('categories-roles');
        return true;
    }
}
