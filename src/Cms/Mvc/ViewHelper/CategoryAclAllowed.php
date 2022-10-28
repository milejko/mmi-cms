<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Mvc\ViewHelper;

use Mmi\Security\AuthInterface;

/**
 * Helper ACL kategorii (uprawnień)
 */
class CategoryAclAllowed extends \Mmi\Mvc\ViewHelper\HelperAbstract
{
    /**
     * @var AuthInterface
     */
    private $auth;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Zwraca czy dozwolone na ACL
     */
    public function categoryAclAllowed(string $categoryId): bool
    {
        //zwrot z ACL kategorii
        return (new \CmsAdmin\Model\CategoryAclModel())->getAcl()->isAllowed($this->auth->getRoles(), $categoryId);
    }
}
