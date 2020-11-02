<?php

use CmsAdmin\Mvc\ViewHelper\AdminNavigation;
use Mmi\Cache\PrivateCache;
use Mmi\Mvc\ActionHelper;
use Mmi\Mvc\View;
use Mmi\Mvc\ViewHelper\Navigation;
use Mmi\Security\Acl;
use Psr\Container\ContainerInterface;

return [

    Acl::class => function (ContainerInterface $container) {
        //ustawienie acl
        if (null === ($acl = $container->get(PrivateCache::class)->load($cacheKey = 'mmi-cms-acl'))) {
            $acl = \Cms\Model\Acl::setupAcl();
            $container->get(PrivateCache::class)->save($acl, $cacheKey, 0);
        }
        $container->get(ActionHelper::class)->setAcl($acl);
        $container->get(View::class)->setAcl($acl);
        return $acl;
    }

];