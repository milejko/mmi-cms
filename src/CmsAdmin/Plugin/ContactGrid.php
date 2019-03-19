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
 * Grid kontaktu
 */
class ContactGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery(new \Cms\Orm\CmsContactQuery);

        //id
        $this->addColumn((new Column\CustomColumn('id'))
            ->setTemplateCode('#{$record->id}'));

        //data dodania
        $this->addColumn((new Column\TextColumn('dateAdd'))
            ->setLabel('grid.contact.dateAdd.label'));

        //tekst
        $this->addColumn((new Column\TextColumn('text'))
            ->setLabel('grid.contact.text.label'));

        //email
        $this->addColumn((new Column\TextColumn('email'))
            ->setLabel('grid.contact.email.label'));

        //strona wejściowa
        $this->addColumn((new Column\TextColumn('uri'))
            ->setLabel('grid.contact.uri.label'));

        //ip
        $this->addColumn((new Column\TextColumn('ip'))
            ->setLabel('grid.contact.ip.label'));

        //aktywny
        $this->addColumn((new Column\CheckboxColumn('active'))
            ->setLabel('grid.contact.active.label')
            ->setDisabled());

        //operacje
        $this->addColumn((new Column\OperationColumn())->setEditParams(['module' => 'cmsAdmin', 'controller' => 'contact', 'action' => 'edit', 'id' => '%id%'])
            ->setDeleteParams([]));
    }

}
