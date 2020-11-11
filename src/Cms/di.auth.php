<?php

use DI\Definition\Exception\InvalidDefinition;
use Mmi\Mvc\ActionHelper;
use Mmi\Mvc\View;
use Mmi\Security\Auth;
use Mmi\Security\AuthInterface;
use Mmi\Security\AuthProviderInterface;
use Psr\Container\ContainerInterface;

return [
    AuthInterface::class => function (ContainerInterface $container) {
        //auth provider not found
        if (!$container->has(AuthProviderInterface::class)) {
            throw new InvalidDefinition('Auth model implementing ' . AuthProviderInterface::class . ' cannot be injected. To fix this, add definition of ' . AuthProviderInterface::class . ' with suitable object (such as an instance of \Cms\Security\AuthProvider) in your application\'s DI configuration.');
        }
        //configure authorization
        $auth = new Auth($container->get(AuthProviderInterface::class));
        //provide other objects with $auth class
        $container->get(ActionHelper::class)->setAuth($auth);
        $container->get(View::class)->setAuth($auth);
        return $auth;
    }
];