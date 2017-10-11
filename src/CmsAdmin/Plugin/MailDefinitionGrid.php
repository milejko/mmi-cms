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
 * Grid definicji maila
 */
class MailDefinitionGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery(\Cms\Orm\CmsMailDefinitionQuery::lang());

        //język
        $this->addColumn((new Column\CustomColumn('lang'))
            ->setLabel('język'));

        //nazwa
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('nazwa'));

        //treść w html
        $this->addColumn((new Column\CheckboxColumn('html'))
            ->setLabel('HTML'));

        //temat
        $this->addColumn((new Column\TextColumn('subject'))
            ->setLabel('temat'));

        //nazwa od
        $this->addColumn((new Column\TextColumn('fromName'))
            ->setLabel('nazwa od'));

        //odpowiedz
        $this->addColumn((new Column\TextColumn('replyTo'))
            ->setLabel('odpowiedz'));

        //serwer
        $this->addColumn((new Column\SelectColumn('cmsMailServerId'))
            ->setMultioptions((new \Cms\Orm\CmsMailServerQuery)->findPairs('id', 'address'))
            ->setLabel('serwer'));

        //data dodania
        $this->addColumn((new Column\TextColumn('dateAdd'))
            ->setLabel('data dodania'));

        //data modyfikacji
        $this->addColumn((new Column\TextColumn('dateModify'))
            ->setLabel('data modyfikacji'));

        //aktywny
        $this->addColumn((new Column\CheckboxColumn('active'))
            ->setLabel('aktywny'));

        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
