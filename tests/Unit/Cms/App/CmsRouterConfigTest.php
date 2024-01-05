<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Unit\Cms\App;

use Cms\App\CmsRouterConfig;
use PHPUnit\Framework\TestCase;

class CmsRouterConfigTest extends TestCase
{
    public function testIfRouterContainsValidRoutes(): void
    {
        $routerConfig = new CmsRouterConfig();
        $routes = $routerConfig->getRoutes();
        self::assertCount(17, $routes);
        self::assertArrayHasKey('cms-category-admin-preview', $routes);
        self::assertArrayHasKey('cms-admin-module', $routes);
        self::assertArrayHasKey('cms-admin-module-controller', $routes);
        self::assertArrayHasKey('cms-admin-module-controller-action', $routes);
        self::assertArrayHasKey('cms-file-default-thumb', $routes);
        self::assertArrayHasKey('cms-file-thumb', $routes);
        self::assertArrayHasKey('cms-file-download', $routes);
        self::assertArrayHasKey('cms-api', $routes);
        self::assertArrayHasKey('cms-contents-preview-api', $routes);
        self::assertArrayHasKey('cms-contents-published-preview-api', $routes);
        self::assertArrayHasKey('cms-contents-api', $routes);
        self::assertArrayHasKey('cms-contents-scopes-api', $routes);
        self::assertArrayHasKey('cms-contents-structure-scopes-api', $routes);
        self::assertArrayHasKey('cms-api-config', $routes);
        self::assertArrayHasKey('cms-category-home', $routes);
        self::assertArrayHasKey('cms-category-dispatch', $routes);
    }
}
