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
 * Helper miniatur
 */
class Thumb extends \Mmi\Mvc\ViewHelper\HelperAbstract
{

    /**
     * Metoda główna, generuje miniaturę
     * @param \Cms\Orm\CmsFileRecord $file instancja pliku
     * @param string $type skala
     * @param string $value
     * @return string
     */
    public function thumb(\Cms\Orm\CmsFileRecord $file, $type = null, $value = null)
    {
        return $file->getUrl($type, $value);
    }

}
