<?php

use Mmi\Mvc\ActionHelper;
use Mmi\Mvc\View;
use Mmi\Security\Acl;
use Mmi\Security\AclInterface;
use Psr\Container\ContainerInterface;

return [

    AclInterface::class => function (ContainerInterface $container) {
        $acl = new Acl();
        //basic 
        $acl->allow('admin', '')
            ->allow('guest', 'mmi')
            ->allow('guest', 'cms')
            ->allow('guest', 'cmsAdmin:index:login');
        $container->get(ActionHelper::class)->setAcl($acl);
        $container->get(View::class)->setAcl($acl);
        return $acl;
    }

];
