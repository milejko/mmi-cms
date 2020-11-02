<?php

use CmsAdmin\App\CmsNavigationConfig;
use CmsAdmin\Mvc\ViewHelper\AdminNavigation;
use DI\Definition\Exception\InvalidDefinition;
use Mmi\Cache\PrivateCache;
use Mmi\Http\Request;
use Mmi\Navigation\Navigation;
use Mmi\Navigation\NavigationConfig;
use Psr\Container\ContainerInterface;

return [

    Navigation::class => function (ContainerInterface $container) {
        if (!$container->has(NavigationConfig::class)) {
            throw new InvalidDefinition('Navigation config implementing ' . NavigationConfig::class . ' cannot be injected. To fix this, add definition of ' . NavigationConfig::class . ' with suitable object in your application\'s DI configuration.');
        }
        //print_r($container->get(NavigationConfig::class));exit;
        $request = $container->get(Request::class);
        if (null === ($navigation = $container->get(PrivateCache::class)->load($cacheKey = 'mmi-cms-navigation-' . $request->lang))) {
            (new \Cms\Model\Navigation)->decorateConfiguration($container->get(NavigationConfig::class));
            $navigation = new Navigation($container->get(NavigationConfig::class));
            //zapis do cache
            $container->get(PrivateCache::class)->save($navigation, $cacheKey, 0);
        }
        $navigation->setup($request);
        return $navigation;
    }

];