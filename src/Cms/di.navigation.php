<?php

use DI\Definition\Exception\InvalidDefinition;
use Mmi\Cache\CacheInterface;
use Mmi\Http\Request;
use Mmi\Navigation\Navigation;
use Mmi\Navigation\NavigationConfig;
use Psr\Container\ContainerInterface;

use function DI\env;
use function DI\factory;

return [

    'cms.navigation.categories.enabled' => env('CMS_NAVIGATION_CATEGORIES_ENABLED', true),

    Navigation::class => factory(function (ContainerInterface $container) {
        if (!$container->has(NavigationConfig::class)) {
            throw new InvalidDefinition('Navigation config implementing ' . NavigationConfig::class . ' cannot be injected. To fix this, add definition of ' . NavigationConfig::class . ' with suitable object in your application\'s DI configuration.');
        }
        $request = $container->get(Request::class);
        if (null === ($navigation = $container->get(CacheInterface::class)->load($cacheKey = 'mmi-cms-navigation-' . $request->lang))) {
            $container->get('cms.navigation.categories.enabled') && (new \Cms\Model\Navigation)->decorateConfiguration($container->get(NavigationConfig::class));
            $navigation = new Navigation($container->get(NavigationConfig::class));
            //zapis do cache
            $container->get(CacheInterface::class)->save($navigation, $cacheKey, 0);
        }
        $navigation->setup($request);
        return $navigation;
    }),

];