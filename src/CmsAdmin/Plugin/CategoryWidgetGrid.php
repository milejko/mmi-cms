<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

use CmsAdmin\Grid\Column;

/**
 * Grid widgetów
 */
class CategoryWidgetGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //query
        $this->setQuery((new \Cms\Orm\CmsCategoryWidgetQuery)
                ->orderAscId());

        //nazwa
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('nazwa'));

        $widgets = [null => '---'] + \CmsAdmin\Model\Reflection::getOptionsWildcard(3, '/widget/');

        //klasa modułu wyświetlania
        $this->addColumn((new Column\SelectColumn('mvcParams'))
            ->setMultioptions($widgets)
            ->setLabel('moduł wyświetlania'));

        //klasa modułu wyświetlania
        $this->addColumn((new Column\SelectColumn('mvcPreviewParams'))
            ->setMultioptions($widgets)
            ->setLabel('modułu podglądu'));

        //klasa forma
        $this->addColumn((new Column\TextColumn('formClass'))
            ->setLabel('klasa formularza konfiguracji'));

        //długość bufora
        $this->addColumn((new Column\SelectColumn('cacheLifetime'))
            ->setLabel('odświeżanie')
            ->setMultioptions(\Cms\Orm\CmsCategoryWidgetRecord::CACHE_LIFETIMES));

        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
