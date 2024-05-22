<?php

namespace Cms\Orm;

use Cms\App\CmsAppMvcEvents;
use Cms\Exception\CategoryWidgetException;
use Cms\Model\CategoryWidgetModel;
use DI\DependencyException;
use DI\NotFoundException;
use Mmi\App\App;
use Mmi\Cache\CacheInterface;
use Mmi\DataObject;
use Mmi\EventManager\EventManagerInterface;
use Mmi\Mvc\View;
use Psr\Log\LoggerInterface;

/**
 * Rekord kategorii CMSowych
 */
class CmsCategoryRecord extends \Mmi\Orm\Record
{
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
     * Aktywność
     * @var integer
     */
    public $active;

    /**
     * Widoczność
     * @var bool
     */
    public $visible;

    //status draft
    public const STATUS_DRAFT = 0;
    //status artykuł aktywny
    public const STATUS_ACTIVE = 10;
    //status historia
    public const STATUS_HISTORY = 20;
    //status usunięte
    public const STATUS_DELETED = 30;
    //nazwa obiektu plików cms
    public const FILE_OBJECT = 'cmscategory';
    //nazwa obiektu pliku OG image
    public const OG_IMAGE_OBJECT = self::FILE_OBJECT . 'ogimage';
    //nazwa obiektów tagów
    public const TAG_OBJECT = 'cmscategory';
    //prefiks bufora modelu widgetu
    public const WIDGET_MODEL_CACHE_PREFIX = 'category-widget-model-';
    //prefiks bufora url->id
    public const URI_ID_CACHE_PREFIX = 'category-uri-id-';
    //prefiks bufora obiektu kategorii
    public const CATEGORY_CACHE_PREFIX = 'category-';
    //prefiks bufora obiektu transportowego kategorii
    public const CATEGORY_CACHE_TRANSPORT_PREFIX = 'category-transport-';
    //prefiks bufora obiektu transportowego kategorii
    public const CATEGORY_CACHE_STRUCTURE_PREFIX = 'category-structure-';
    //prefix bufora dzieci kategorii
    public const CATEGORY_CHILDREN_CACHE_PREFIX = 'category-children-ids-';
    //prefiks bufora przekierowania
    public const REDIRECT_CACHE_PREFIX = 'category-redirect-';

    /**
     * Zapis rekordu
     * @return boolean
     */
    public function save()
    {
        if (null === $this->order) {
            $this->order = App::$di->get(CmsCategoryRepository::class)->getChildrenMaxOrder($this->parentId) + 1;
        }
        //zapis configJson
        $this->setConfigFromArray(array_merge($this->getConfig()->toArray(), $this->getOptions()));
        //uzupełnia path i uri
        $this->_calculatePathAndUri();
        //zapis
        return parent::save();
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
        $originalRecord = App::$di->get(CmsCategoryRepository::class)->getCategoryRecordById($this->cmsCategoryOriginalId);
        if (null === $originalRecord) {
            return true;
        }
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
    public function isActive()
    {
        //jeśli status różny od aktywna (bieżąca)
        if (self::STATUS_ACTIVE != $this->status) {
            return false;
        }
        //sprawdzanie aktywności rekordu i jego rodziców
        $record = $this;
        while (null !== $record) {
            if (!$record->active) {
                return false;
            }
            $record = $record->getParentRecord();
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
        $parent = $this->getParentRecord();
        if (null !== $parent) {
            //nieaktywny nie jest ujawniony w uri
            $this->uri = $parent->visible ? $parent->uri : substr($parent->uri, 0, strrpos($parent->uri, '/'));
            //bez względu na aktywność jest w ścieżce - path
            $this->path = trim($parent->path . '/' . $parent->id, '/');
        }
        //doklejanie do uri przefiltrowanej końcówki
        $this->uri .= '/' . (new \Mmi\Filter\Url())->filter(strip_tags($this->name ?? ''));
        $this->uri = trim($this->uri, '/');
        //filtracja customUri
        $this->customUri = ($this->customUri == '/') ? '/' : trim(($this->customUri ?? ''), '/');
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
        //zmodyfikowana widoczność
        $visibleModified = $this->isModified('visible');
        //czy nazwa zmodyfikowana
        $nameModified = $this->isModified('name');
        //data modyfikacji
        $this->dateModify = date('Y-m-d H:i:s');
        //aktualizacja rekordu (czyści info o zmodyfikowanych)
        if (!parent::_update()) {
            return false;
        }
        if (self::STATUS_ACTIVE != $this->status) {
            return true;
        }
        //zmieniono parenta, nazwę, lub aktywność
        if ($parentModified || $nameModified || $activeModified || $visibleModified) {
            //przebudowa dzieci
            $this->_rebuildChildren($this->id);
        }
        return $this->clearCache();
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
        $children = (new \Cms\Model\CategoryModel(new CmsCategoryQuery()))->getCategoryTree($this->getPk());
        if (!empty($children)) {
            throw new \Cms\Exception\ChildrenExistException();
        }
        //usuwanie historycznych, draftów i dzieci
        (new CmsCategoryQuery())
            ->whereCmsCategoryOriginalId()->equals($this->id)
            ->orFieldParentId()->equals($this->id)
            ->delete();
        //usuwanie plików
        (new CmsFileQuery())
            ->whereQuery((new CmsFileQuery())
                ->whereObject()->like(CmsCategoryRecord::FILE_OBJECT . '%')
                ->andFieldObject()->notLike(CmsCategoryWidgetCategoryRecord::FILE_OBJECT . '%'))
            ->andFieldObjectId()->equals($this->getPk())
            ->delete();
        //usuwanie tagów
        (new CmsTagRelationQuery())
            ->whereObject()->like(CmsCategoryRecord::TAG_OBJECT . '%')
            ->andFieldObjectId()->equals($this->getPk())
            ->delete();
        //usuwanie widgetów
        (new CmsCategoryWidgetCategoryQuery())
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
        foreach ($this->_getPublishedChildren($this->id, $this->getScope()) as $childRecord) {
            $childRecord->softDelete();
        }
        $this->status = self::STATUS_DELETED;
        $this->simpleUpdate();
        $parent = $this->getParentRecord();
        if (null !== $parent) {
            $parent->clearCache();
        }
        return true;
    }

    /**
     * Przywracanie skasowanej kategorii
     */
    public function restore()
    {
        $this->status = self::STATUS_ACTIVE;
        $parent = $this;
        while ($parent = $parent->getParentRecord()) {
            if (self::STATUS_ACTIVE == $parent->status) {
                $parent->clearCache();
                continue;
            }
            $parent->status = self::STATUS_ACTIVE;
            $parent->simpleUpdate();
        }
        return $this->save();
    }

    /**
     * Pobiera url kategorii
     * @param boolean $https true - tak, false - nie, null - bez zmiany protokołu
     * @return string
     */
    public function getUrl()
    {
        //pobranie linku z widoku
        return App::$di->get(View::class)->url(['module' => 'cms', 'controller' => 'category', 'action' => 'dispatch', 'uri' => $this->getUri()], true);
    }

    /**
     * Pobiera bezwzględną kolejność (uwzględnia rodziców)
     */
    public function getAbsoluteOrder(): string
    {
        $parentRecords = [$this];
        $parentRecord = $this;
        while (null !== $parentRecord = $parentRecord->getParentRecord()) {
            $parentRecords[] = $parentRecord;
        }
        $path = '';
        foreach (array_reverse($parentRecords) as $parentRecord) {
            $path .= str_pad($parentRecord->order, 10, '0', STR_PAD_LEFT);
        }
        return $path;
    }

    /**
     * Pobiera model widgetów
     * @return CategoryWidgetModel
     * @throws CategoryWidgetException
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getWidgetModel()
    {
        //próba pobrania modelu widgetu z cache
        if (null === $widgetModel = App::$di->get(CacheInterface::class)->load($cacheKey = self::WIDGET_MODEL_CACHE_PREFIX . $this->id)) {
            //pobieranie modelu widgetu
            App::$di->get(CacheInterface::class)->save($widgetModel = new \Cms\Model\CategoryWidgetModel($this->id), $cacheKey, 0);
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
        return App::$di->get(CmsCategoryRepository::class)
            ->getCategoryRecordById($this->parentId);
    }

    /**
     * Pobiera rekordy dzieci
     * @return array
     */
    public function getChildrenRecords()
    {
        $childrenIds = App::$di->get(CmsCategoryRepository::class)->getChildrenCategoryIds($this->id);
        $children = [];
        foreach ($childrenIds as $childId) {
            $child = App::$di->get(CmsCategoryRepository::class)
                ->getCategoryRecordById($childId);
            if (null === $child) {
                continue;
            }
            $children[] = $child;
        }
        return $children;
    }

    /**
     * Zwraca konfigurację
     * @return \Mmi\DataObject
     */
    public function getConfig()
    {
        if (null === $this->configJson) {
            return new DataObject();
        }
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
        return (new DataObject())->setParams($configArr);
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
        return 0 < (new CmsCategoryQuery())->whereCmsCategoryOriginalId()->equals($this->cmsCategoryOriginalId ? $this->cmsCategoryOriginalId : $this->id)
            ->andFieldStatus()->equals(self::STATUS_HISTORY)
            ->count();
    }

    /**
     * Pobiera uri (z uwzględnieniem custom uri)
     */
    public function getUri(): string
    {
        return $this->customUri ? $this->customUri : $this->uri;
    }

    /**
     * Pobiera scope
     */
    public function getScope(): string
    {
        return substr($this->template, 0, strpos($this->template, '/'));
    }

    /**
     * Pobiera klucz szablonu
     */
    public function getTemplateKey(): string
    {
        return substr($this->template, strpos($this->template, '/') + 1);
    }

    /**
     * Pobiera numer poziomu zagnieżdżenia
     */
    public function getLevel(): int
    {
        return count(explode('/', $this->path));
    }

    /**
     * Przebudowuje dzieci (wywołuje save)
     * @param integer $parentId rodzic
     */
    protected function _rebuildChildren($parentId)
    {
        $i = 0;
        //clear parent cache
        App::$di->get(CacheInterface::class)->remove(self::CATEGORY_CACHE_PREFIX . $parentId);
        //iteracja po dzieciach
        foreach ($this->_getPublishedChildren($parentId, $this->getScope()) as $categoryRecord) {
            //wyznaczanie kolejności
            $categoryRecord->order = $i++;
            $categoryRecord->_calculatePathAndUri();
            //zapis rekordu
            $categoryRecord->simpleUpdate();
            //zejście rekurencyjne
            $this->_rebuildChildren($categoryRecord->id);
        }
    }

    /**
     * Zwraca dzieci danego rodzica
     * @param integer $parentId id rodzica
     * @param boolean $activeOnly tylko aktywne
     * @return \Cms\Orm\CmsCategoryRecord[]
     */
    protected function _getPublishedChildren($parentId, $scope)
    {
        //zwrot kolekcji rekordów
        return (new CmsCategoryQuery())
            ->whereParentId()->equals($parentId)
            ->whereTemplate()->like($scope . '%')
            ->whereStatus()->equals(self::STATUS_ACTIVE)
            ->orderAscOrder()
            ->orderAscId()
            ->find();
    }

    /**
     * Usuwa cache
     */
    public function clearCache()
    {
        $scope = $this->getScope();
        //usuwanie cache
        $cache = App::$di->get(CacheInterface::class);
        $cache->remove(self::CATEGORY_CACHE_TRANSPORT_PREFIX . $this->id);
        $cache->remove(self::WIDGET_MODEL_CACHE_PREFIX . $this->id);
        foreach ((new CategoryWidgetModel($this->id))->getWidgetRelations() as $widget) {
            $cache->remove(CmsCategoryWidgetCategoryRecord::HTML_CACHE_PREFIX . $widget->id);
            $cache->remove(CmsCategoryWidgetCategoryRecord::JSON_CACHE_PREFIX . $widget->id);
        }
        //don't drop cache on draft saves
        if (self::STATUS_DRAFT == $this->status) {
            return true;
        }
        $cache->remove(self::WIDGET_MODEL_CACHE_PREFIX . $this->cmsCategoryOriginalId);
        $cache->remove(self::URI_ID_CACHE_PREFIX . md5($scope . $this->uri));
        $cache->remove(self::URI_ID_CACHE_PREFIX . md5($scope . $this->customUri));
        $cache->remove(self::REDIRECT_CACHE_PREFIX . md5($scope . $this->uri));
        $cache->remove(self::REDIRECT_CACHE_PREFIX . md5($scope . $this->customUri));
        $newestHistoricalVersion = (new CmsCategoryQuery())
            ->whereCmsCategoryOriginalId()->equals($this->cmsCategoryOriginalId ?: $this->id)
            ->andFieldStatus()->equals(CmsCategoryRecord::STATUS_HISTORY)
            ->orderDescDateModify()
            ->findFirst();
        //drop previous version cache
        if ($newestHistoricalVersion) {
            $cache->remove(self::URI_ID_CACHE_PREFIX . md5($scope . $newestHistoricalVersion->customUri));
            $cache->remove(self::URI_ID_CACHE_PREFIX . md5($scope . $newestHistoricalVersion->uri));
            $cache->remove(self::REDIRECT_CACHE_PREFIX . md5($scope . $newestHistoricalVersion->uri));
            $cache->remove(self::REDIRECT_CACHE_PREFIX . md5($scope . $newestHistoricalVersion->customUri));
        }
        //drop skin menu cache
        $cache->remove(self::CATEGORY_CACHE_TRANSPORT_PREFIX . $scope);
        $cache->remove(self::CATEGORY_CACHE_STRUCTURE_PREFIX . $scope);
        $cache->remove(self::CATEGORY_CACHE_PREFIX . $this->id);
        $cache->remove(self::CATEGORY_CACHE_PREFIX . $this->cmsCategoryOriginalId);
        $cache->remove(self::CATEGORY_CACHE_TRANSPORT_PREFIX . $this->id);
        $cache->remove(self::CATEGORY_CACHE_TRANSPORT_PREFIX . $this->cmsCategoryOriginalId);
        $cache->remove(self::CATEGORY_CHILDREN_CACHE_PREFIX . $this->id);
        $cache->remove(self::CATEGORY_CHILDREN_CACHE_PREFIX . $this->cmsCategoryOriginalId);
        App::$di->get(EventManagerInterface::class)->trigger($this->isActive() ? CmsAppMvcEvents::CATEGORY_UPDATE : CmsAppMvcEvents::CATEGORY_DELETE, $this);
        return true;
    }
}
