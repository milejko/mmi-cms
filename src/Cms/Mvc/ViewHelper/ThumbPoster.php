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
        if (!$file->data->posterFileName) {
            return;
        }
        //ścieżka CDN
        $cdnPath = rtrim($this->view->cdn ? \Mmi\App\FrontController::getInstance()->getView()->cdn : $this->view->url([], true, $https), '/');
        //pobranie ścieżki z systemu plików
        return $cdnPath . (new \Cms\Model\FileSystemModel($file->data->posterFileName))->getPublicPath($type, $value, $https);
    }

}
