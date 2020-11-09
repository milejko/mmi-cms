<?php

namespace Cms\Orm;

use Mmi\App\App;
use Mmi\Cache\CacheInterface;

/**
 * Rekord widgetu kategorii
 */
class CmsCategoryWidgetRecord extends \Mmi\Orm\Record
{

    public $id;
    public $name;
    public $formClass;
    public $mvcParams;
    public $mvcPreviewParams;
    public $cacheLifetime = self::DEFAULT_CACHE_LIFETIME;

    //domyślna długość bufora
    const DEFAULT_CACHE_LIFETIME = 2592000;

    /**
     * Pobiera parametrów mvc jako request
     * @return \Mmi\Http\Request
     */
    public function getMvcParamsAsRequest()
    {
        $mvcParams = [];
        //parsowanie ciągu
        parse_str($this->mvcParams, $mvcParams);
        return new \Mmi\Http\Request($mvcParams);
    }

    /**
     * Pobiera parametry podglądu mvc jako request
     * @return \Mmi\Http\Request
     */
    public function getMvcPreviewParamsAsRequest()
    {
        $mvcParams = [];
        //parsowanie ciągu
        parse_str($this->mvcPreviewParams, $mvcParams);
        return new \Mmi\Http\Request($mvcParams);
    }

    /**
     * Aktualizacja widgeta
     * @return boolean
     */
    protected function _update()
    {
        //zapis z usunięciem bufora
        return parent::_update() && $this->clearCache();
    }

    /**
     * Usunięcie definicji widgeta
     */
    public function delete()
    {
        //usuwanie utworzonych widgetów
        (new CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryWidgetId()->equals($this->getPk())
            ->find()
            ->delete();
        //usuwanie widgeta
        return parent::delete();
    }

    /**
     * Usuwanie zbuforowanych renderów widgetów
     * @return boolean
     */
    public function clearCache()
    {
        //iteracja po relacjach widgetów
        foreach ((new CmsCategoryWidgetCategoryQuery)
            ->join('cms_category')->on('cms_category_id')
            ->whereCmsCategoryWidgetId()
            ->equals($this->id)
            ->findPairs('cms_category_widget_category.id', 'cms_category.id') as $id => $categoryId) {
            //usuwanie bufora
            App::$di->get(CacheInterface::class)->remove('category-widget-html-' . $id);
            App::$di->get(CacheInterface::class)->remove('category-widget-model-' . $categoryId);
            App::$di->get(CacheInterface::class)->remove('category-html-' . $categoryId);
        }
        return true;
    }

}
