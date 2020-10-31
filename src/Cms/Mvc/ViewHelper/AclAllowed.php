<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Mvc\ViewHelper;

/**
 * Helper ACL (uprawnień)
 */
class AclAllowed extends \Mmi\Mvc\ViewHelper\HelperAbstract
{

    /**
     * Zwraca czy dozwolone na ACL
     * @param array $params
     * @return boolean
     */
    public function aclAllowed(array $params)
    {
        //zwrot z ACL
        //@TODO: integrate with ACL
        return true;//\App\Registry::$acl->isAllowed(\App\Registry::$auth->getRoles(), strtolower($urlParams['module'] . ':' . $urlParams['controller'] . ':' . $urlParams['action']));
    }

}
