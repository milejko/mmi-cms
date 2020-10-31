<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Mvc;

/**
 * Kontroler stron adminowych
 */
abstract class Controller extends \Mmi\Mvc\Controller
{

    /**
     * Inicjalizacja
     */
    public function init()
    {
        //ustawienie języka edycji
        /*
        $session = new \Mmi\Session\SessionSpace('cms-language');
        //session already set
        if ($session->lang) {
            return;
        }
        //no identity
        if (!\App\Registry::$auth->hasIdentity()) {
            return;
        }
        //setting session lang by logged user
        $session->lang = (new \Cms\Orm\CmsAuthQuery)
            ->findPk(\App\Registry::$auth->getId())
            ->lang;
        */
    }
}
