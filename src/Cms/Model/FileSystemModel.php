<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Mmi\App\App;
use Psr\Log\LoggerInterface;

/**
 * Model
 */
class FileSystemModel
{

    /**
     * Nazwa pliku (MD5 z rozszerzeniem)
     * @var string
     */
    private $_name;

    /**
     * Konstruktor
     * @param string $fileName
     * @throws \Mmi\App\KernelException
     */
    public function __construct($fileName)
    {
        if (strlen($fileName) < 4) {
            throw new \Mmi\App\KernelException('File name invalid');
        }
        $this->logger = App::$di->get(LoggerInterface::class);
        $this->_name = $fileName;
    }

    /**
     * Pobiera prawdziwą ścieżkę zasobu
     * @return string
     */
    public function getRealPath()
    {
        //brak prawidłowej nazwy pliku
        if (strlen($this->_name) < 4) {
            return;
        }
        //ścieżka na dysku
        return BASE_PATH . '/var/data/' . $this->_name[0] . '/' . $this->_name[1] . '/' . $this->_name[2] . '/' . $this->_name[3] . '/' . $this->_name;
    }

    /**
     * Pobiera ścieżkę publiczną
     * @param string $scaleType
     * @param string $scale
     * @return string
     */
    public function getPublicPath($scaleType = 'default', $scale = null)
    {
        //rozszerzenie
        $extension = strtolower(substr($this->_name, strrpos($this->_name, '.') + 1));
        //obliczanie
        $hash = md5($scaleType . $scale . $this->_name . App::$di->get('cms.auth.salt'));
        $filePath = '/data/' . trim($scaleType . '-' . $scale, '-x') . '/' . $this->_name . '-' . $hash;
        //override extension only if thumb and supported extension
        if ('download' != $scaleType && in_array($extension, ['bmp', 'jpeg', 'jfif', 'jif', 'jpg', 'png'])) {
            $extension = 'webp';
        }
        if ('webp' != $extension && 'download' != $scaleType) {
            return;
        }
        return $filePath . '.' . $extension;
    }

    public function unlink()
    {
        //usuwa plik
        if (file_exists($this->getRealPath()) && is_writable($this->getRealPath())) {
            unlink($this->getRealPath());
        }
        //usuwa miniatury
        foreach (glob(BASE_PATH . '/web/data/*') as $thumbDir) {
            //@TODO: remove thumbs
        }
    }

}
