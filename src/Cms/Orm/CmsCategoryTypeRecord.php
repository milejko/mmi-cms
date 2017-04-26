<?php

namespace Cms\Orm;

/**
 * Rekord typów kategorii (szablonów)
 */
class CmsCategoryTypeRecord extends \Mmi\Orm\Record
{

    public $id;
    public $name;
    public $key;
    public $mvcParams;
    public $cacheLifetime;

    /**
     * Aktualizacja szablonu
     * @return boolean
     */
    protected function _update()
    {
        //zapis z usunięciem bufora
        return parent::_update() && $this->clearCache();
    }

    /**
     * Usuwanie zbuforowanych renderów widgetów
     * @return boolean
     */
    public function clearCache()
    {
        //iteracja po relacjach widgetów
        foreach ((new CmsCategoryQuery)
            ->whereCmsCategoryTypeId()
            ->equals($this->id)
            ->findPairs('id', 'id') as $categoryId) {
            //usuwanie bufora
            \App\Registry::$cache->remove('category-html-' . $categoryId);
        }
        return true;
    }

}
