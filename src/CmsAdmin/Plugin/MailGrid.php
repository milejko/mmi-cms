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
                0 => 'do wysyłki',
                1 => 'wysłany',
                2 => 'w trakcie wysyłki',
            ])
            ->setLabel('wysłany'));

        //data dodania
        $this->addColumn((new Column\TextColumn('dateAdd'))
            ->setLabel('data dodania'));

        //data wysyłki
        $this->addColumn((new Column\TextColumn('dateSent'))
            ->setLabel('data wysłania'));

        //do
        $this->addColumn((new Column\TextColumn('to'))
            ->setLabel('do'));

        //temat
        $this->addColumn((new Column\TextColumn('subject'))
            ->setLabel('temat'));

        //nazwa od
        $this->addColumn((new Column\TextColumn('fromName'))
            ->setLabel('od'));

        //operacje
        $this->addColumnOperation()
            ->setEditParams(['module' => 'cmsAdmin', 'controller' => 'mail', 'action' => 'preview', 'id' => '%id%']);
    }

}
