<?php

namespace Cms\Orm;

use Cms\Api\Service\MenuService;
use Cms\App\CmsSkinsetConfig;
use Mmi\App\App;
use Mmi\Cache\CacheInterface;
use Mmi\Mvc\View;
use Psr\Log\LoggerInterface;

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
     * @var string
     */
    public $template;

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
     * Ścieżka złożona z id
     * @var string
     */
    public $path;

    /**
     * Opcjonalny adres strony
     * @var string
     */
    public $customUri;

    /**
     * Opcjonalny adres przekierowania
     * @var string
     */
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
     * Nowe okno
     * @var boolean
     */
    public $blank;

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
    const STATUS_DRAFT = 0;
    //status artykuł aktywny
    const STATUS_ACTIVE = 10;
    //status historia
    const STATUS_HISTORY = 20;
    //status usunięte
    const STATUS_DELETED = 30;
    //nazwa obiektu plików cms
    const FILE_OBJECT = 'cmscategory';
    //prefiks bufora modelu widgetu
    const WIDGET_MODEL_CACHE_PREFIX = 'category-widget-model-';
    //prefiks bufora url->id
    const URI_ID_CACHE_PREFIX = 'category-uri-id-';
    //prefiks bufora obiektu kategorii
    const CATEGORY_CACHE_PREFIX = 'category-';
    //prefix bufora dzieci kategorii
    const CATEGORY_CHILDREN_CACHE_PREFIX = 'category-children-';
    //prefiks bufora przekierowania
    const REDIRECT_CACHE_PREFIX = 'category-redirect-';

    /**
     * Zapis rekordu
     * @return boolean
     */
    public function save()
    {
        //domyślnie wstawienie na koniec (znacznik time dba o to, później się przesortuje)
        if (null === $this->order) {
            $this->order = time();
        }
        //zapis configJson
        $this->setConfigFromArray(array_merge($this->getConfig()->toArray(), $this->getOptions()));
        //uzupełnia path i uri
        $this->_calculatePathAndUri();
        //zapis
        return parent::save() && $this->clearCache();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function simpleUpdate()
    {
        return parent::_update() && $this->clearCache();
    }

    /**
     * Zrzuca rekord do tabeli
     * @return array
     */
    public function toArray()
    {
        //dołącza do danych z rekordu dane spakowane w json
        return parent::toArray() + $this->getConfig()->toArray();
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
        if (self::STATUS_ACTIVE != $this->status) {
            return false;
        }
        return true;
    }

    /**
     * Kalkulacja uri i path
     * @param array $parentsCache
     * @throws \Mmi\App\KernelException
     * @throws \Mmi\Orm\OrmException
     */
    protected function _calculatePathAndUri()
    {
        //usunięcie uri i path
        $this->uri = $this->path = '';
        //ustawiamy uri na podstawie rodzica
        if ($this->parentId && (null !== $parent = $this->getParentRecord())) {
            //nieaktywny nie jest ujawniony w uri
            $this->uri = $parent->active ? $parent->uri : substr($parent->uri, 0, strrpos($parent->uri, '/'));
            //bez względu na aktywność jest w ścieżce - path
            $this->path = trim($parent->path . '/' . $parent->id, '/');
        }
        //doklejanie do uri przefiltrowanej końcówki
        $this->uri .= '/' . (new \Mmi\Filter\Url)->filter(strip_tags($this->name));
        $this->uri = trim($this->uri, '/');
        //filtracja customUri
        $this->customUri = ($this->customUri == '/') ? '/' : trim($this->customUri, '/');
    }

    /**
     * Wstawienie kategorii z obliczeniem kodu i przebudową drzewa
     * @return boolean
     */
    protected function _insert()
    {
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
        //badanie modyfikacji przed update
        //zmodyfikowany parent
        $parentModified = $this->isModified('parentId');
        //zmodyfikowany order
        $orderModified = $this->isModified('order');
        //zmodyfikowana aktywność
        $activeModified = $this->isModified('active');
        //czy nazwa zmodyfikowana
        $nameModified = $this->isModified('name');
        //data modyfikacji
        $this->dateModify = date('Y-m-d H:i:s');
        //aktualizacja rekordu (czyści info o zmodyfikowanych)
        if (!parent::_update()) {
            return false;
        }
        //sortowanie dzieci po przestawieniu rodzica, lub kolejności
        if ($parentModified || $orderModified) {
            //sortuje dzieci
            $this->_sortChildren();
        }
        //zmieniono parenta, nazwę, lub aktywność
        if ($parentModified || $nameModified || $activeModified) {
            //przebudowa dzieci
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
        //brak pk
        if ($this->getPk() === null) {
            return false;
        }
        //pobranie dzieci
        $children = (new \Cms\Model\CategoryModel(new CmsCategoryQuery))->getCategoryTree($this->getPk());
        if (!empty($children)) {
            throw new \Cms\Exception\ChildrenExistException();
        }
        //usuwanie historycznych, draftów i dzieci
        (new CmsCategoryQuery)
            ->whereCmsCategoryOriginalId()->equals($this->id)
            ->orFieldParentId()->equals($this->id)
            ->delete();
        //usuwanie plików
        (new CmsFileQuery)
            ->whereQuery((new CmsFileQuery)
                ->whereObject()->like(CmsCategoryRecord::FILE_OBJECT . '%')
                ->andFieldObject()->notLike(CmsCategoryWidgetCategoryRecord::FILE_OBJECT . '%')
            )
            ->andFieldObjectId()->equals($this->getPk())
            ->delete();
        //usuwanie widgetów
        (new CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryId()->equals($this->getPk())
            ->delete();
        //usuwanie kategorii i czyszczenie bufora
        return parent::delete() && $this->clearCache();
    }

    /**
     * Miękkie kasowanie kategorii
     */
    public function softDelete()
    {
        $this->status = self::STATUS_DELETED;
        $this->_softDeleteChildren($this->id);
        return $this->save();
    }

    /**
     * Przywracanie skasowanej kategorii
     */
    public function restore()
    {
        $this->status = self::STATUS_ACTIVE;
        $parent = $this;
        //przywracanie rodziców
        while ($parent = $parent->getParentRecord()) {
            $parent->status = self::STATUS_ACTIVE;
            $parent->save();
        }
        return $this->save();
    }

    /**
     * Pobiera url kategorii
     * @param boolean $https true - tak, false - nie, null - bez zmiany protokołu
     * @return string
     */
    public function getUrl($https = null)
    {
        //pobranie linku z widoku
        return App::$di->get(View::class)->url(['module' => 'cms', 'controller' => 'category', 'action' => 'dispatch', 'uri' => $this->customUri ? $this->customUri : $this->uri], true, $https);
    }

    /**
     * Pobiera model widgetów
     * @return \Cms\Model\CategoryWidgetModel
     */
    public function getWidgetModel()
    {
        //próba pobrania modelu widgetu z cache
        if (null === $widgetModel = App::$di->get(CacheInterface::class)->load($cacheKey = self::WIDGET_MODEL_CACHE_PREFIX . $this->id)) {
            //pobieranie modelu widgetu
            App::$di->get(CacheInterface::class)->save($widgetModel = new \Cms\Model\CategoryWidgetModel($this->id, App::$di->get(CmsSkinsetConfig::class)), $cacheKey, 0);
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
        if (null === $parent = App::$di->get(CacheInterface::class)->load($cacheKey = self::CATEGORY_CACHE_PREFIX . $this->parentId)) {
            //pobieranie rodzica
            App::$di->get(CacheInterface::class)->save($parent = (new \Cms\Orm\CmsCategoryQuery)
                ->findPk($this->parentId), $cacheKey, 0);
        }
        //zwrot rodzica
        return $parent;
    }

    /**
     * Pobiera rekordy dzieci
     * @return array
     */
    public function getChildrenRecords()
    {
        //próba pobrania dzieci z cache
        if (null === $children = App::$di->get(CacheInterface::class)->load($cacheKey = self::CATEGORY_CHILDREN_CACHE_PREFIX . $this->id)) {
            //pobieranie dzieci
            App::$di->get(CacheInterface::class)->save($children = $this->_getActiveChildren($this->id), $cacheKey, 0);
        }
        return $children;
    }

    /**
     * Pobiera rekordy tego samego poziomu
     * @return array
     */
    public function getSiblingsRecords()
    {
        //próba pobrania dzieci z cache
        if (null === $siblings = App::$di->get(CacheInterface::class)->load($cacheKey = self::CATEGORY_CHILDREN_CACHE_PREFIX . $this->parentId)) {
            //pobieranie dzieci
            App::$di->get(CacheInterface::class)->save($siblings = $this->_getActiveChildren($this->parentId), $cacheKey, 0);
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
            App::$di->get(LoggerInterface::class)->warning('Unable to decode category configJson #' . $this->id);
        }
        //tworznie pustego configa
        if (!isset($configArr)) {
            $configArr = [];
        }
        return (new \Mmi\DataObject())->setParams($configArr);
    }

    /**
     * Ustawia configJson na podstawie danych
     * z filtracją danych zapisanych w atrybutach
     * @param array $data
     * @return bool
     */
    public function setConfigFromArray(array $data = [])
    {
        //kodowanie konfiguracji
        $this->configJson = empty($data) ? null : \json_encode($data);
        return $this;
    }

    /**
     * Zwraca czy istnieją rekordy historyczne
     * @return boolean
     */
    public function hasHistoricalEntries()
    {
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
        foreach ($this->_getActiveChildren($parentId) as $categoryRecord) {
            //wyznaczanie kolejności
            $categoryRecord->order = $i++;
            $categoryRecord->_calculatePathAndUri(true);
            //zapis rekordu
            $categoryRecord->simpleUpdate();
            //zejście rekurencyjne
            $this->_rebuildChildren($categoryRecord->id);
        }
    }

    /**
     * Miękkie usuwanie dzieci
     */
    protected function _softDeleteChildren($parentId)
    {
        foreach ($this->_getActiveChildren($parentId) as $categoryRecord) {
            $categoryRecord->softDelete();
            $this->_softDeleteChildren($categoryRecord->id);
        }
    }

    /**
     * Zwraca dzieci danego rodzica
     * @param integer $parentId id rodzica
     * @param boolean $activeOnly tylko aktywne
     * @return \Cms\Orm\CmsCategoryRecord[]
     */
    protected function _getActiveChildren($parentId)
    {
        //zwrot kolekcji rekordów
        return (new CmsCategoryQuery)
            ->whereParentId()->equals($parentId)
            ->whereStatus()->equals(self::STATUS_ACTIVE)
            ->orderAscOrder()
            ->orderAscId()
            ->find();
    }

    /**
     * Sortuje dzieci wybranego rodzica
     * @param integer $order wstawiona kolejność
     */
    protected function _sortChildren()
    {
        $i = 0;
        //pobranie dzieci swojego rodzica
        foreach ($this->_getActiveChildren($this->parentId) as $categoryRecord) {
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
            //prosty zapis
            $categoryRecord->simpleUpdate();
        }
    }

    /**
     * Usuwa cache
     */
    public function clearCache()
    {
        $scope = substr($this->template, 0, strpos($this->template, '/'));
        //usuwanie cache
        $cache = App::$di->get(CacheInterface::class);
        $cache->remove(self::URI_ID_CACHE_PREFIX . md5($this->uri));
        $cache->remove(self::URI_ID_CACHE_PREFIX . md5($this->getInitialStateValue('uri')));
        $cache->remove(self::URI_ID_CACHE_PREFIX . md5($this->customUri));
        $cache->remove(self::URI_ID_CACHE_PREFIX . md5($this->getInitialStateValue('customUri')));
        $cache->remove(self::REDIRECT_CACHE_PREFIX . md5($scope . $this->uri));
        $cache->remove(self::REDIRECT_CACHE_PREFIX . md5($scope . $this->getInitialStateValue('uri')));
        $cache->remove(self::REDIRECT_CACHE_PREFIX . md5($scope . $this->customUri));
        $cache->remove(self::REDIRECT_CACHE_PREFIX . md5($scope . $this->getInitialStateValue('customUri')));
        $cache->remove(self::WIDGET_MODEL_CACHE_PREFIX . $this->id);
        $cache->remove(self::WIDGET_MODEL_CACHE_PREFIX . $this->cmsCategoryOriginalId);
        //caches associated with active version
        if (self::STATUS_ACTIVE != $this->status) {
            return true;
        }
        //drop navigation cache
        $cache->remove('mmi-cms-navigation-');
        //drop skin menu cache
        $cache->remove(MenuService::CACHE_KEY);
        $cache->remove(MenuService::CACHE_KEY . $scope);
        $cache->remove(self::CATEGORY_CACHE_PREFIX . $this->id);
        $cache->remove(self::CATEGORY_CACHE_PREFIX . $this->cmsCategoryOriginalId);
        //usuwanie cache dzieci kategorii
        $cache->remove(self::CATEGORY_CHILDREN_CACHE_PREFIX . $this->id);
        foreach ($this->_getActiveChildren($this->id) as $childRecord) {
            $cache->remove(self::CATEGORY_CHILDREN_CACHE_PREFIX . $childRecord->id);
        }
        $cache->remove(self::CATEGORY_CHILDREN_CACHE_PREFIX . $this->parentId);
        return true;
    }

}
