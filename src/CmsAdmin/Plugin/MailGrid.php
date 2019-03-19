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
 * Grid maila
 */
class MailGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery((new \Cms\Orm\CmsMailQuery)
                ->orderDescId());

        //wysłany
        $this->addColumn((new Column\SelectColumn('active'))
            ->setMultioptions([
                0 => 'grid.mail.active.options.0',
                1 => 'grid.mail.active.options.1',
                2 => 'grid.mail.active.options.2',
            ])
            ->setLabel('grid.mail.active.label'));

        //data dodania
        $this->addColumn((new Column\TextColumn('dateAdd'))
            ->setLabel('grid.mail.dateAdd.label'));

        //data wysyłki
        $this->addColumn((new Column\TextColumn('dateSent'))
            ->setLabel('grid.mail.dateSent.label'));

        //do
        $this->addColumn((new Column\TextColumn('to'))
            ->setLabel('grid.mail.to.label'));

        //temat
        $this->addColumn((new Column\TextColumn('subject'))
            ->setLabel('grid.mail.subject.label'));

        //nazwa od
        $this->addColumn((new Column\TextColumn('fromName'))
            ->setLabel('grid.mail.fromName.label'));

        //operacje
        $this->addColumn((new Column\OperationColumn)
            ->setEditParams(['module' => 'cmsAdmin', 'controller' => 'mail', 'action' => 'preview', 'id' => '%id%']));
    }

}
