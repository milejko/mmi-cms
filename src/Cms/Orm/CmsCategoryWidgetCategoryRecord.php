<?php

namespace Cms\Orm;

/**
 * Rekord łączenia widget - kategoria
 */
class CmsCategoryWidgetCategoryRecord extends \Mmi\Orm\Record
{

    public $id;
    public $uuid;
    public $widget;
    public $section;
    public $cmsCategoryWidgetId;
    public $cmsCategoryId;
    public $cmsCategorySectionId;
    public $configJson;
    public $active = 1;

    /**
     * Kolejność
     * @var integer
     */
    public $order;
    
    //prefiks obiektów plików dla widgetu
    const FILE_OBJECT_PREFIX = 'cmscategorywidgetcategory';
    //prefiks atrybutów
    const CMS_ATTRIBUTE_PREFIX = 'cmsAttribute';
    //prefiks bufora widgetów
    const HTML_CACHE_PREFIX = 'category-widget-html-';

    /**
     * Zapis rekordu
     * @return boolean
     */
    public function save()
    {
        //nadawanie uuid
        if (!$this->uuid) {
            $this->uuid = $this->_generateUuid();
        }
        //zapis configJson
        $this->setConfigFromArray(array_merge($this->getConfig()->toArray(), $this->getOptions()));
        //zapis z usunięciem cache
        return parent::save() && $this->clearCache();
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
     * Usunięcie rekordu
     * @return boolean
     */
    public function delete()
    {
        //usuwanie plików
        (new CmsFileQuery)->whereObject()->like(self::FILE_OBJECT_PREFIX . '%')
            ->andFieldObjectId()->equals($this->getPk())
            ->find()
            ->delete();
        //usunięcie z czyszczeniem bufora
        return parent::delete() && $this->clearCache();
    }

    /**
     * Zwraca rekord kategorii
     * @return CmsCategoryRecord
     * @throws \Cms\Exception\CategoryWidgetException
     */
    public function getCategoryRecord()
    {
        //zwrot dołączonegj kategorii
        if ($this->getJoined('cms_category')) {
            return $this->getJoined('cms_category');
        }
        //wyszukiwanie kategorii
        return (new CmsCategoryQuery())->findPk($this->cmsCategoryId);
    }

    /**
     * Zwraca rekord widgeta
     * @return CmsCategoryWidgetRecord
     * @throws \Cms\Exception\CategoryWidgetException
     */
    public function getWidgetRecord()
    {
        //zwrot dołączonego widgeta
        if ($this->getJoined('cms_category_widget')) {
            return $this->getJoined('cms_category_widget');
        }
        //brak widgeta
        throw new \Cms\Exception\CategoryWidgetException('Widget not joined');
    }

    /**
     * Pobiera rekordy wartości atrybutów w formie obiektu danych
     * @see \Mmi\DataObiect
     * @return \Mmi\DataObject
     */
    public function getAttributeValues()
    {
        //zwrot atrybutów
        return (new \Cms\Model\AttributeValueRelationModel('categoryWidgetRelation', $this->id))->getGrouppedAttributeValues();
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
            \Mmi\App\FrontController::getInstance()->getLogger()->warning('Unable to decode widget configJson #' . $this->id);
        }
        //tworznie pustego configa
        if (!isset($configArr)) {
            $configArr = [];
        }
        $config = (new \Mmi\DataObject())->setParams($configArr);
        return $config;
    }

    /**
     * Ustawia configJson na podstawie danych
     * z filtracją danych zapisanych w atrybutach
     * @param array $data
     * @return bool
     */
    public function setConfigFromArray(array $data = []) {
        //kodowanie konfiguracji
        $this->configJson = empty($data) ? null : \json_encode($data);
        return $this;
    }

    /**
     * Przełączenie widoczności widgeta
     * @return boolean
     */
    public function toggle()
    {
        //aktywacja/deaktywacja
        $this->active = ($this->active == 1) ? 0 : 1;
        return $this->save();
    }

    /**
     * Usuwanie bufora
     */
    public function clearCache()
    {
        //usuwanie cache
        \App\Registry::$cache->remove('category-widget-model-' . $this->cmsCategoryId);
        \App\Registry::$cache->remove(CmsCategoryRecord::HTML_CACHE_PREFIX . $this->cmsCategoryId);
        \App\Registry::$cache->remove(self::HTML_CACHE_PREFIX . $this->id);
        return true;
    }

    /**
     * Generuje Uuid
     * @return string
     */
    protected function _generateUuid()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,
            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

}
