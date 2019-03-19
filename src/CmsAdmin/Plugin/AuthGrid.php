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
 * Grid użytkowników
 */
class AuthGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //domyślne zapytanie
        $this->setQuery((new \Cms\Orm\CmsAuthQuery)->orderAscUsername());

        //nazwa
        $this->addColumn((new Column\TextColumn('username'))
            ->setLabel('grid.auth.username.label'));

        //email
        $this->addColumn((new Column\TextColumn('email'))
            ->setLabel('grid.auth.email.label'));

        //imię
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('grid.auth.name.label'));

        //przypisane role użytkownika
        $this->addColumn((new Column\CustomColumn('roles'))
            ->setLabel('grid.auth.roles.label')
            ->setTemplateCode('{$record->getRolesAsString()}'));

        //ostatnie logowanie
        $this->addColumn((new Column\TextColumn('lastLog'))
            ->setLabel('grid.auth.lastLog.label'));

        //ostatnie IP
        $this->addColumn((new Column\TextColumn('lastIp'))
            ->setLabel('grid.auth.lastIp.label'));

        //błędne logowanie
        $this->addColumn((new Column\TextColumn('lastFailLog'))
            ->setLabel('grid.auth.lastFailLog.label'));

        //ostatnie ip błędnego logowania
        $this->addColumn((new Column\TextColumn('lastFailIp'))
            ->setLabel('grid.auth.lastFailIp.label'));

        //aktywny
        $this->addColumn((new Column\CheckboxColumn('active'))
            ->setLabel('grid.auth.active.label'));

        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
