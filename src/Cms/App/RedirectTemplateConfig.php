<?php

/**
 * Multiportals CMS instance (content repository)
 *
 * @copyright Copyright (c) 2021 Nowa Era (http://nowaera.pl) All rights reserved.
 * @license   Proprietary and confidential
 */

declare(strict_types=1);

namespace Cms\App;

use Cms\RedirectTemplateController;

class RedirectTemplateConfig extends CmsTemplateConfig
{
    public const KEY = 'redirect';

    public function __construct()
    {
        $this
            ->setName('template.category.index.redirect.label')
            ->setKey(self::KEY)
            ->setControllerClassName(RedirectTemplateController::class);
    }
}
