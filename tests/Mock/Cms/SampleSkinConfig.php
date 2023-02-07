<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Mock\Cms;

use Cms\App\CmsSectionConfig;
use Cms\App\CmsSkinConfig;
use Cms\App\CmsTemplateConfig;
use Cms\App\CmsWidgetConfig;

class SampleSkinConfig extends CmsSkinConfig
{
    public function __construct()
    {
        $this
            ->setKey('sample')
            ->addTemplate((new CmsTemplateConfig())
                ->setAllowedOnRoot()
                ->setControllerClassName(SampleTplController::class)
                ->setCompatibleChildrenKeys(['sampletpl'])
                ->addSection((new CmsSectionConfig)
                        ->setKey('main')
                        ->setName('main section')
                        ->addWidget((new CmsWidgetConfig)
                                ->setCacheLifeTime(30)
                                ->setControllerClassName(SampleWidgetController::class)
                                ->setKey('samplewidget')
                                ->setName('sample-number-one'))
                        ->addWidget((new CmsWidgetConfig)
                                ->setCacheLifeTime(30)
                                ->setControllerClassName(SampleWidgetController::class)
                                ->setKey('anothersamplewidget')
                                ->setName('sample-number-two')))
                ->setKey('sampletpl')
                ->setName('Sample template'))
            ->addTemplate((new CmsTemplateConfig())
                ->setAllowedOnRoot()
                ->setControllerClassName(SampleTplInvalidController::class)
                ->setCompatibleChildrenKeys([])
                ->setKey('invalidsampletpl')
                ->setName('Invalid template'))
            ->setName('Sample Skin')
            ->setPreviewUrl('https://somesamplefrontenddomain.com/preview')
            ->setAttributes(['sample-attribute' => 'value']);
    }
}
