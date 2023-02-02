<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Unit\Cms;

use Cms\ApiController;
use Mmi\App\AppTesting;
use Mmi\Http\Request;
use PHPUnit\Framework\TestCase;

class ApiControllerTest extends TestCase
{
    private static ApiController $apiController;

    public static function setUpBeforeClass(): void
    {
        self::$apiController = AppTesting::$di->get(ApiController::class);
    }

    public function testIfInexistentScopeGives404(): void
    {
        $response = self::$apiController->configAction(new Request(['scope' => 'inexistent']));
        self::assertEquals(404, $response->getCode());
        self::assertEquals('application/json', $response->getType());
        self::assertEquals('{"message":"Skin not found"}', $response->getContent());
    }

    public function testIfSampleSkinIsListedWithProperLinks(): void
    {
        $response = self::$apiController->indexAction(new Request());
        self::assertEquals(200, $response->getCode());
        self::assertEquals('application/json', $response->getType());
        self::assertEquals('{"scopes":[{"key":"sample","name":"Sample Skin","_links":[{"href":"\/api\/sample","rel":"config"},{"href":"\/api\/sample\/contents","rel":"contents"}]}]}', $response->getContent());
    }

    public function testIfSampleConfigIsVisible(): void
    {
        $response = self::$apiController->configAction(new Request(['scope' => 'sample']));
        self::assertEquals(200, $response->getCode());
        self::assertEquals('application/json', $response->getType());
        self::assertEquals('{"key":"sample","attributes":{"sample-attribute":"value"},"templates":["sampletpl"],"_links":[{"href":"\/api\/sample\/contents","rel":"contents"}]}', $response->getContent());
    }

    public function testIfMissingScopeGivesRedirect(): void
    {
        $response = self::$apiController->getContentsAction(new Request());
        self::assertEquals(301, $response->getCode());
        self::assertEquals('application/json', $response->getType());
        self::assertEquals('{"_links":[{"href":"\/api","rel":"external","method":"REDIRECT"}]}', $response->getContent());
    }

    public function testIfInvalidScopeGivesRedirect(): void
    {
        $response = self::$apiController->getContentsAction(new Request(['scope' => 'inexistent']));
        self::assertEquals(404, $response->getCode());
        self::assertEquals('application/json', $response->getType());
        self::assertEquals('{"message":"Skin not found"}', $response->getContent());
    }
}
