<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2019 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use App\Registry;

/**
 * Kontroler podglądu konfiguracji
 */
class ConfigController extends Mvc\Controller
{

    const THREE_DOTS = '(...)';
    const ADMIN_ROLE = 'admin';

    /**
     * Widok konfiguracji
     */
    public function indexAction()
    {
        $config = clone (\App\Registry::$config);
        //za długie dane do wyświetlenia
        $config->navigation = self::THREE_DOTS;
        $config->router = self::THREE_DOTS;
        //ukrycie hasła dla użytkowników pozbawionych roli admina
        if (!Registry::$auth->hasRole(self::ADMIN_ROLE)) {
            $config->db->password = self::THREE_DOTS;
        }
        if ($config->skinset) {
            $skins = [];
            foreach ($config->skinset->getSkins() as $skin) {
                $skins[] = $skin->getKey();
            }
            $config->skinset = implode(', ', $skins);
        }
        $this->view->config = \Mmi\Http\ResponseDebugger\Colorify::colorify(print_r($config, true));
        $this->view->server = \Mmi\Http\ResponseDebugger\Colorify::colorify(print_r($_SERVER, true));
    }
}
