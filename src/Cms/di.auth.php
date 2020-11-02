<?php

use DI\Definition\Exception\InvalidDefinition;
use Mmi\Mvc\ActionHelper;
use Mmi\Mvc\View;
use Mmi\Security\Auth;
use Mmi\Security\AuthInterface;
use Psr\Container\ContainerInterface;

use function DI\env;

return [
    'cms.auth.salt'     => env('CMS_AUTH_SALT', 'some-default-salt-with-number-123'),

    Auth::class => function (ContainerInterface $container) {
        if (!$container->has(AuthInterface::class)) {
            throw new InvalidDefinition('Auth model implementing ' . AuthInterface::class . ' cannot be injected. To fix this, add definition of ' . AuthInterface::class . ' with suitable object (such as an instance of \Cms\Model\Auth) in your application\'s DI configuration.');
        }
        //configure authorization
        $auth = (new \Mmi\Security\Auth)
            ->setSalt($container->get('cms.auth.salt'))
            ->setModelName($container->get(AuthInterface::class));
        //configure other objects with $auth class
        $container->get(ActionHelper::class)->setAuth($auth);
        $container->get(View::class)->setAuth($auth);
        return $auth;
    }
];