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
            ->setLabel('grid.mailDefinition.lang.label'));

        //nazwa
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('grid.mailDefinition.name.label'));

        //treść w html
        $this->addColumn((new Column\CheckboxColumn('html'))
            ->setLabel('grid.mailDefinition.html.label'));

        //temat
        $this->addColumn((new Column\TextColumn('subject'))
            ->setLabel('grid.mailDefinition.subject.label'));

        //nazwa od
        $this->addColumn((new Column\TextColumn('fromName'))
            ->setLabel('grid.mailDefinition.fromName.label'));

        //odpowiedz
        $this->addColumn((new Column\TextColumn('replyTo'))
            ->setLabel('grid.mailDefinition.replyTo.label'));

        //serwer
        $this->addColumn((new Column\SelectColumn('cmsMailServerId'))
            ->setMultioptions((new \Cms\Orm\CmsMailServerQuery())->findPairs('id', 'address'))
            ->setLabel('grid.mailDefinition.cmsMailServerId.label'));

        //data dodania
        $this->addColumn((new Column\TextColumn('dateAdd'))
            ->setLabel('grid.mailDefinition.dateAdd.label'));

        //data modyfikacji
        $this->addColumn((new Column\TextColumn('dateModify'))
            ->setLabel('grid.mailDefinition.dateModify.label'));

        //aktywny
        $this->addColumn((new Column\CheckboxColumn('active'))
            ->setLabel('grid.mailDefinition.active.label'));

        //operacje
        $this->addColumn(new Column\OperationColumn());
    }
}
