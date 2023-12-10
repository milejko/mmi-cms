<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Mvc\ViewHelper;

use Cms\Orm\CmsFileRecord;
use Mmi\Mvc\ViewHelper\HelperAbstract;

/**
 * Helper miniatur
 */
class Thumb extends HelperAbstract
{
    /**
     * Metoda główna generuje miniaturę
     * @param CmsFileRecord|null $file instancja pliku
     * @param string|null $type skala
     * @param int|string|null $value
     * @return string
     */
    public function thumb(?CmsFileRecord $file, string $type = 'default', string $value = ''): string
    {
        return $file instanceof CmsFileRecord ? $file->getThumbUrl($type, $value) : '/resource/cmsAdmin/images/no-file.png';
    }
}
