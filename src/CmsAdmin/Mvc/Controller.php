<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Mvc;

use Mmi\App\App;
use Mmi\Security\Auth;

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
        $session = new \Mmi\Session\SessionSpace('cms-language');
        //session already set
        if ($session->lang) {
            return;
        }
        //no identity
        if (!App::$di->get(Auth::class)->hasIdentity()) {
            return;
        }
        //setting session lang by logged user
        $session->lang = (new \Cms\Orm\CmsAuthQuery)
            ->findPk(App::$di->get(Auth::class)->getId())
            ->lang;
    }
}
