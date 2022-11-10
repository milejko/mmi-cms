<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Mvc\ViewHelper;

/**
 * Helper miniatur dla posterów
 */
class ThumbPoster extends \Mmi\Mvc\ViewHelper\HelperAbstract
{
    /**
     * Metoda główna, generuje miniaturę
     * @param \Cms\Orm\CmsFileRecord $file instancja pliku
     * @param string $type skala
     * @param string $value
     * @param boolean $https null - bez zmian
     * @return string
     */
    public function thumbPoster(\Cms\Orm\CmsFileRecord $file, $type = 'default', $value = null, $https = null)
    {
        return $file->getPosterUrl($type, $value, $https);
    }
}
