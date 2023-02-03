<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Mock\Cms;

use Cms\App\CmsSkinConfig;
use Cms\App\CmsTemplateConfig;

class SampleSkinConfigMock extends CmsSkinConfig
{
    public function __construct()
    {
        $this
            ->setKey('sample')
            ->addTemplate((new CmsTemplateConfig())
                ->setAllowedOnRoot()
                ->setControllerClassName(SampleTplControllerClassMock::class)
                ->setCompatibleChildrenKeys(['sampletpl'])
                ->setKey('sampletpl')
                ->setName('Sample template'))
            ->addTemplate((new CmsTemplateConfig())
                ->setAllowedOnRoot()
                ->setControllerClassName(SampleTplInvalidControllerClassMock::class)
                ->setCompatibleChildrenKeys([])
                ->setKey('invalidsampletpl')
                ->setName('Invalid template'))
            ->setName('Sample Skin')
            ->setPreviewUrl('https://somesamplefrontenddomain.com/preview')
            ->setAttributes(['sample-attribute' => 'value']);
    }
}
