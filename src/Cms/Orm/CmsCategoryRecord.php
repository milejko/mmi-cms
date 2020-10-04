<?php

namespace Cms\Orm;

use Cms\Model\AttributeRelationModel,
    Cms\Model\AttributeValueRelationModel;

/**
 * Rekord kategorii CMSowych
 */
class CmsCategoryRecord extends \Mmi\Orm\Record
{

    //domyślna długość bufora
    const DEFAULT_CACHE_LIFETIME = 2592000;

    /**
     * Identyfikator
     * @var integer
     */
    public $id;

    /**
     * Właściciel
     * @var integer
     */
    public $cmsAuthId;

    /**
     * Identyfikator szablonu
     * @var integer
     */
    public $cmsCategoryTypeId;

    /**
     * Identyfikator głównego rekordu wersji
     * @var integer
     */
    public $cmsCategoryOriginalId;

    /**
     * Status - draft, wpis historyczny, artykuł
     * @var integer
     */
    public $status;

    /**
     * Język
     * @var string
     */
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

    /**
     * Aktywność
     * @var integer 
     */
    public $active;

    //status draft
    CONST STATUS_DRAFT = 0;
    //status artykuł aktywny
    CONST STATUS_ACTIVE = 10;
    //status historia
    CONST STATUS_HISTORY = 20;
    //nazwa obiektu plików cms
    CONST FILE_OBJECT = 'cmscategory';

    /**
     * Zapis rekordu
     * @return boolean
     */
    public function save()
    {
        $this->_calculateUri();
        //domyślnie wstawienie na koniec
        if (null === $this->order) {
            $this->order = $this->_maxChildOrder() + 1;
        }
        //zapis
        return parent::save() && $this->clearCache();
    }

    /**
     * Zapisuje draft lub przywraca wersję
     * @return boolean
     */
    public function commitVersion()
    {
        //sprawdzenie czy posiada rodzica (oryginał)
        if (!$this->cmsCategoryOriginalId) {
            return true;
        }
        //wyszukiwanie oryginału
        $originalRecord = (new CmsCategoryQuery)->findPk($this->cmsCategoryOriginalId);
        //tworzenie wersji
        $versionModel = new \Cms\Model\CategoryVersion($originalRecord);
        $versionModel->create();
        //zmiana miejscami draftu z oryginałem
        return $versionModel->exchangeOriginal($this);
    }

    /**
     * Czy kategoria (strona) jest widoczna na froncie - aktywna itp.
     * @return boolean
     */
    public function isVisible()
    {
        //jeśli nie jest aktywna
        if (!$this->active) {
            return false;
        }
        //jeśli status różny od aktywna (bieżąca)
        if ($this->status != \Cms\Orm\CmsCategoryRecord::STATUS_ACTIVE) {
            return false;
        }
        //nie osiągnięto czasu publikacji
        if (null !== $this->dateStart && $this->dateStart > date('Y-m-d H:i:s')) {
            return false;
        }
        //przekroczono czas publikacji
        if (null !== $this->dateEnd && $this->dateEnd < date('Y-m-d H:i:s')) {
            return false;
        }
        return true;
    }

    /**
     * Kalkulacja uri
     * @throws \Mmi\App\KernelException
     * @throws \Mmi\Orm\OrmException
     */
    protected function _calculateUri()
    {
        //strona główna
        if ($this->customUri == '/') {
            //usunięcie uri
            $this->uri = '';
            return;
        }
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
        $this->uri .= (new \Mmi\Filter\Url)->filter(strip_tags($this->name));
        //filtracja customUri
        $this->customUri = ($this->customUri == '/') ? '/' : trim($this->customUri, '/');
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
        $this->dateAdd = $this->dateAdd ? $this->dateAdd : date('Y-m-d H:i:s');
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
        //zmodyfikowana aktywność
        $activeModified = $this->isModified('active');
        //zmodyfikowane uri
        $uriModified = $this->isModified('uri');
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
        //zmieniono parenta, nazwę, lub aktywność
        if ($parentModified || $uriModified || $activeModified) {
            //przebudowa dzieci
            $this->_rebuildChildren($this->id);
        }
        //synchronizacja draftów
        if ($parentModified || $uriModified || $activeModified || $orderModified) {
            //synchronizacja pól w draftach
            $this->_synchronizeDrafts();
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
        //brak pk
        if ($this->getPk() === null) {
            return false;
        }
        //usuwanie historycznych, draftów i dzieci
        (new CmsCategoryQuery)
            ->whereCmsCategoryOriginalId()->equals($this->id)
            ->orFieldParentId()->equals($this->id)
            ->find()
            ->delete();
        //pobranie dzieci
        $children = (new \Cms\Model\CategoryModel)->getCategoryTree($this->getPk());
        if (!empty($children)) {
            throw new \Cms\Exception\ChildrenExistException();
        }
        //usuwanie plików
        (new CmsFileQuery)
            ->whereQuery((new CmsFileQuery)
                ->whereObject()->like(CmsCategoryRecord::FILE_OBJECT . '%')
                ->andFieldObject()->notLike(CmsCategoryWidgetCategoryRecord::FILE_OBJECT . '%')
            )        
            ->andFieldObjectId()->equals($this->getPk())
            ->find()
            ->delete();
        //usuwanie widgetów
        (new CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryId()->equals($this->getPk())
            ->find()
            ->delete();
        //usuwanie kategorii i czyszczenie bufora
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
        //brak parenta
        if (!$this->parentId) {
            return;
        }
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
            \App\Registry::$cache->save($siblings = $this->_getChildren($this->parentId, true), $cacheKey);
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
     * Zwraca czy istnieją rekordy historyczne
     * @return boolean
     */
    public function hasHistoricalEntries() {
        return 0 < (new CmsCategoryQuery)->whereCmsCategoryOriginalId()->equals($this->cmsCategoryOriginalId ? $this->cmsCategoryOriginalId : $this->id)
                ->andFieldStatus()->equals(self::STATUS_HISTORY)
                ->count();
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
     * Synchronizuje drafty
     */
    protected function _synchronizeDrafts()
    {
        //iteracja po draftach bieżącego dokumentu
        foreach ((new CmsCategoryQuery())->whereCmsCategoryOriginalId()->equals($this->id)
            ->andFieldStatus()->equals(self::STATUS_DRAFT)
            ->find()
            as $draftRecord) {
            //synchronizacja uri
            $draftRecord->uri = $this->uri;
            //synchronizacja parentId
            $draftRecord->parentId = $this->parentId;
            //synchronizacja kolejności
            $draftRecord->order = $this->order;
            //bez przebudowy kolejności
            $draftRecord->setOption('block-ordering', true);
            //zapis
            $draftRecord->save();
        }
    }

    /**
     * Zwraca dzieci danego rodzica
     * @param integer $parentId id rodzica
     * @param boolean $activeOnly tylko aktywne
     * @return \Cms\Orm\CmsCategoryRecord[]
     */
    protected function _getChildren($parentId, $activeOnly = false)
    {
        //inicjalizacja zapytania
        $query = (new CmsCategoryQuery)
            ->whereParentId()->equals($parentId)
            ->joinLeft('cms_category_type')->on('cms_category_type_id')
            ->orderAscOrder()
            ->orderAscId();
        //tylko aktywne
        if ($activeOnly) {
            $query->whereStatus()->equals(self::STATUS_ACTIVE);
        }
        //zwrot w postaci tablicy rekordów
        return $query->find()->toObjectArray();
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
            ->andFieldStatus()->equals(self::STATUS_ACTIVE)
            ->findMax('order');
        //będzie inkrementowany
        return $maxOrder === null ? -1 : $maxOrder;
    }

    /**
     * Sortuje dzieci wybranego rodzica
     * @param integer $order wstawiona kolejność
     */
    protected function _sortChildren()
    {
        $i = 0;
        //pobranie dzieci swojego rodzica
        foreach ($this->_getChildren($this->parentId, true) as $categoryRecord) {
            //ten rekord musi pozostać w niezmienionej pozycji (był sortowany)
            if ($categoryRecord->id == $this->id) {
                continue;
            }
            //ten sam order wskakuje za rekord
            if ($this->order == $i) {
                $i++;
            }
            //obliczanie nowej kolejności
            $categoryRecord->order = $i++;
            //blokada dalszego sortowania i zapis
            $categoryRecord
                ->setOption('block-ordering', true)
                ->save();
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
        \App\Registry::$cache->remove('category-' . $this->cmsCategoryOriginalId);
        \App\Registry::$cache->remove('category-html-' . $this->id);
        \App\Registry::$cache->remove('category-html-' . $this->cmsCategoryOriginalId);
        \App\Registry::$cache->remove('category-id-' . md5($this->uri));
        \App\Registry::$cache->remove('category-redirect-' . md5($this->uri));
        \App\Registry::$cache->remove('category-id-' . md5($this->getInitialStateValue('uri')));
        \App\Registry::$cache->remove('category-id-' . md5($this->customUri));
        \App\Registry::$cache->remove('category-id-' . md5($this->getInitialStateValue('customUri')));
        \App\Registry::$cache->remove('category-attributes-' . $this->id);
        \App\Registry::$cache->remove('category-attributes-' . $this->cmsCategoryOriginalId);
        \App\Registry::$cache->remove('category-widget-model-' . $this->id);
        \App\Registry::$cache->remove('category-widget-model-' . $this->cmsCategoryOriginalId);
        \App\Registry::$cache->remove('categories-roles');
        return true;
    }

}
