<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2019 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

/**
 * Kontroler podglądu konfiguracji
 */
class ConfigController extends Mvc\Controller
{

    const THREE_DOTS = '(...)';

    /**
     * Widok konfiguracji
     */
    public function indexAction()
    {
        $config = clone (\App\Registry::$config);
        //za długie dane do wyświetlenia
        $config->navigation = self::THREE_DOTS;
        $config->router = self::THREE_DOTS;
        $this->view->config = \Mmi\Http\ResponseDebugger\Colorify::colorify(print_r($config, true));
        $this->view->server = \Mmi\Http\ResponseDebugger\Colorify::colorify(print_r($_SERVER, true));
    }
}
