<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Mvc\ViewHelper;

/**
 * Helper ACL kategorii (uprawnień)
 */
class CategoryAclAllowed extends \Mmi\Mvc\ViewHelper\HelperAbstract
{

    /**
     * Zwraca czy dozwolone na ACL
     * @param array $categoryId
     * @return boolean
     */
    public function categoryAclAllowed($categoryId)
    {
        //zwrot z ACL kategorii
        return (new \CmsAdmin\Model\CategoryAclModel)->getAcl()->isAllowed(\App\Registry::$auth->getRoles(), $categoryId);
    }

}
