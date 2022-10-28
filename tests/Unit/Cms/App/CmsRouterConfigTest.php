<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\App;

use PHPUnit\Framework\TestCase;

/**
 * Klasa konfiguracji routera
 */
class CmsRouterConfigTest extends TestCase
{
    public function testIfRouterContainsValidRoutes(): void
    {
        $routerConfig = new CmsRouterConfig();
        $routes = $routerConfig->getRoutes();
        self::assertCount(17, $routes);
        /*self::assertArrayNotHasKey('cms-category-admin-preview', $routes);
        self::assertArrayNotHasKey('cms-admin-module', $routes);
        self::assertArrayNotHasKey('cms-admin-module-controller', $routes);
        self::assertArrayNotHasKey('cms-admin-module-controller-action', $routes);*/
    }
}
