<?php

/**
 * Multiportals CMS instance (content repository)
 *
 * @copyright Copyright (c) 2021 Nowa Era (http://nowaera.pl) All rights reserved.
 * @license   Proprietary and confidential
 */

declare(strict_types=1);

namespace Cms\App;

use Cms\FolderTemplateController;

class FolderTemplateConfig extends CmsTemplateConfig
{
    public const KEY = 'folder';

    public function __construct()
    {
        $this
            ->setName('template.category.index.folder.label')
            ->setNestingEnabled(true)
            ->setCompatibleNestingLevels([0,1,2,3,4,5,6,7,8,9,10])
            ->setKey(self::KEY)
            ->setControllerClassName(FolderTemplateController::class);
    }
}
