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
use Mmi\Http\Response;
use PHPUnit\Framework\TestCase;

class ApiControllerTest extends TestCase
{
    private function getFromContainer(): ApiController
    {
        return AppTesting::$di->get(ApiController::class);
    }

    public function testSomeTest(): void
    {
        $request = new Request();
        $request->scope = 'test';

        $apiController = $this->getFromContainer();
        $response = $apiController->configAction($request);
        self::assertInstanceOf(Response::class, $response);
        self::assertEquals(404, $response->getCode());
        self::assertEquals('{"message":"Skin not found"}', $response->getContent());
        self::assertEquals('application/json', $response->getType());
    }
}