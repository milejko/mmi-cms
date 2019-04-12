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

    /**
     * Widok konfiguracji
     */
    public function indexAction()
    {
        $config = clone (\App\Registry::$config);
        //za długie dane do wyświetlenia
        $config->navigation = '(...)';
        $config->router = '(...)';
        $this->view->config = \Mmi\Http\ResponseDebugger\Colorify::colorify(print_r($config, true));
    }
}
