<?php

use DI\Definition\Exception\InvalidDefinition;
use Mmi\Http\Request;
use Mmi\Navigation\Navigation;
use Mmi\Navigation\NavigationConfig;
use Psr\Container\ContainerInterface;

use function DI\factory;

return [

    Navigation::class => factory(function (ContainerInterface $container) {
        if (!$container->has(NavigationConfig::class)) {
            throw new InvalidDefinition('Navigation config implementing ' . NavigationConfig::class . ' cannot be injected. To fix this, add definition of ' . NavigationConfig::class . ' with suitable object in your application\'s DI configuration.');
        }
        return (new Navigation($container->get(NavigationConfig::class)))
            ->setup($container->get(Request::class));
    }),

];