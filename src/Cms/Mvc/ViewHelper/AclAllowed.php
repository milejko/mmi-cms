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
        //defaults
        $params['module'] = isset($params['module']) ? $params['module'] : 'mmi';
        $params['controller'] = isset($params['controller']) ? $params['controller'] : 'index';
        $params['action'] = isset($params['action']) ? $params['action'] : 'index';
        //zwrot z ACL
        return $this->view->getAcl()->isAllowed($this->view->getAuth()->getRoles(), strtolower($params['module'] . ':' . $params['controller'] . ':' . $params['action']));
    }
}
