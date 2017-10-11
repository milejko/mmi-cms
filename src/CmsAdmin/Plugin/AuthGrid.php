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
            ->setLabel('nazwa użytkownika'));

        //email
        $this->addColumn((new Column\TextColumn('email'))
            ->setLabel('e-mail'));

        //imię
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('pełna nazwa użytkownika'));

        //przypisane role użytkownika
        $this->addColumn((new Column\CustomColumn('roles'))
            ->setLabel('role')
            ->setTemplateCode('{$record->getRolesAsString()}'));

        //ostatnie logowanie
        $this->addColumn((new Column\TextColumn('lastLog'))
            ->setLabel('ostatnio zalogowany'));

        //ostatnie IP
        $this->addColumn((new Column\TextColumn('lastIp'))
            ->setLabel('ostatni IP'));

        //błędne logowanie
        $this->addColumn((new Column\TextColumn('lastFailLog'))
            ->setLabel('błędne logowanie'));

        //ostatnie ip błędnego logowania
        $this->addColumn((new Column\TextColumn('lastFailIp'))
            ->setLabel('IP błędnego logowania'));

        //aktywny
        $this->addColumn((new Column\CheckboxColumn('active'))
            ->setLabel('aktywny'));

        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
