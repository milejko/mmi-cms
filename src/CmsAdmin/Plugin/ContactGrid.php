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
            ->setLabel('data dodania'));

        //tekst
        $this->addColumn((new Column\TextColumn('text'))
            ->setLabel('zapytanie'));

        //email
        $this->addColumn((new Column\TextColumn('email'))
            ->setLabel('e-mail'));

        //strona wejściowa
        $this->addColumn((new Column\TextColumn('uri'))
            ->setLabel('strona wejściowa'));

        //ip
        $this->addColumn((new Column\TextColumn('ip'))
            ->setLabel('ip'));

        //aktywny
        $this->addColumn((new Column\CheckboxColumn('active'))
            ->setLabel('czeka')
            ->setDisabled());

        //operacje
        $this->addColumnOperation()
            ->setEditParams(['module' => 'cmsAdmin', 'controller' => 'contact', 'action' => 'edit', 'id' => '%id%'])
            ->setDeleteParams([]);
    }

}
