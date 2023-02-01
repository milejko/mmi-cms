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
use Cms\App\CmsSkinConfig;
use Cms\App\CmsSkinsetConfig;
use Cms\App\CmsTemplateConfig;
use Mmi\App\AppTesting;
use Mmi\Http\Request;
use PHPUnit\Framework\TestCase;

class ApiControllerTest extends TestCase
{
    private function getFromContainer(): ApiController
    {
        return AppTesting::$di->get(ApiController::class);
    }

    private function loadSampleSkin(): void
    {
        /**
         * @var CmsSkinsetConfig $skinset
         */
        $skinset = AppTesting::$di->get(CmsSkinsetConfig::class);
        $sampleSkin = (new CmsSkinConfig)
            ->setKey('sample')
            ->addTemplate((new CmsTemplateConfig)
                ->setAllowedOnRoot()
                ->setKey('sampletpl')
                ->setName('Sample template')
            )
            ->setName('Sample Skin')
            ->setPreviewUrl('https://somesamplefrontenddomain.com/preview')
            ->setAttributes(['sample-attribute' => 'value']);
        $skinset->addSkin($sampleSkin);
    }

    public function testIfCleanCMSGives200(): void
    {
        $apiController = $this->getFromContainer();
        $response = $apiController->indexAction(new Request());
        self::assertEquals(200, $response->getCode());
        self::assertEquals('{"scopes":[]}', $response->getContent());
        self::assertEquals('application/json', $response->getType());
    }

    public function testIfSampleIsListenOnAScopeList(): void
    {
        $this->loadSampleSkin();
    }

    public function testIfInexistentScopeGives404(): void
    {
        $request = new Request(['scope' => 'inexistent']);
        $apiController = $this->getFromContainer();
        $response = $apiController->configAction($request);
        self::assertEquals(404, $response->getCode());
        self::assertEquals('{"message":"Skin not found"}', $response->getContent());
        self::assertEquals('application/json', $response->getType());
    }

    public function testIf

}
