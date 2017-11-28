<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Mvc\ViewHelper;

/**
 * Helper miniatur dla plików binarnych base64
 */
class ThumbBinary extends \Mmi\Mvc\ViewHelper\HelperAbstract
{

    /**
     * Metoda główna, generuje miniaturę
     * @param string $binary instancja pliku
     * @param string $type skala
     * @param string $value
     * @param boolean $https null - bez zmian
     * @return string
     */
    public function thumbBinary($binary, $type = null, $value = null, $https = null)
    {
        $fileName = md5($binary) . '.png';
        $fileSystemModel = new \Cms\Model\FileSystemModel($fileName);
        //binarium już zapisane
        if (file_exists($fileSystemModel->getRealPath())) {
            //pobranie ścieżki thumba
            return $this->_getThumb($fileName, $type, $value, $https);
        }
        try {
            //tworznenie katalogu
            mkdir(dirname($fileSystemModel->getRealPath()), 0777, true);
        } catch (\Exception $e) {
            //już istnieje
        }
        file_put_contents($fileSystemModel->getRealPath(), $this->_parseBaseBinary($binary));
        //pobranie ścieżki thumba
        return $this->_getThumb($fileName, $type, $value, $https);
    }

    /**
     * 
     * @param type $fileName
     * @param type $type
     * @param type $value
     * @param type $https
     * @return type
     */
    private function _getThumb($fileName, $type = null, $value = null, $https = null)
    {
        //ścieżka CDN
        $cdnPath = rtrim(\Mmi\App\FrontController::getInstance()->getView()->cdn ? \Mmi\App\FrontController::getInstance()->getView()->cdn : \Mmi\App\FrontController::getInstance()->getView()->url([], true, $https), '/');
        return $cdnPath . (new \Cms\Model\FileSystemModel($fileName))->getPublicPath($type, $value, $https);
    }

    private function _parseBaseBinary($baseBinary)
    {
        try {
            return base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $baseBinary));
        } catch (\Exception $e) {
            throw new \Cms\Exception\FileException('Unable to decode binary file');
        }
    }

}
