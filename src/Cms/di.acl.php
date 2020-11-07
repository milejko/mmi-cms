<?php

use Mmi\Cache\Cache;
use Mmi\Mvc\ActionHelper;
use Mmi\Mvc\View;
use Mmi\Security\Acl;
use Psr\Container\ContainerInterface;

return [

    Acl::class => function (ContainerInterface $container) {
        //ustawienie acl
        if (null === ($acl = $container->get(Cache::class)->load($cacheKey = 'mmi-cms-acl'))) {
            $acl = \Cms\Model\Acl::setupAcl();
            $container->get(Cache::class)->save($acl, $cacheKey, 0);
        }
        $container->get(ActionHelper::class)->setAcl($acl);
        $container->get(View::class)->setAcl($acl);
        return $acl;
    }

];