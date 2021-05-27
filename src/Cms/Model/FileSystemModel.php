<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Mmi\App\FrontController;
use App\Registry;

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
        list($name, $extension) = explode('.', $this->_name);
        //override extension only if thumb and supported extension
        if (!in_array(strtolower($extension), ['jpg', 'png', 'jpeg', 'jfif', 'jif', 'bmp'])) {
            //copy
            return '/data/copy/'  . md5($name . Registry::$config->salt) . '-' . $this->_name;
        }
        //obliczanie hasha
        return '/data/' . trim($scaleType . '-' . $scale, '-x') . '/' . $this->_name . '-' . md5($scaleType . $scale . $name . Registry::$config->salt) . '.webp';
    }

    public function unlink()
    {
        //usuwa plik
        if (file_exists($this->getRealPath()) && is_writable($this->getRealPath())) {
            unlink($this->getRealPath());
        }
        //usuwa miniatury
        $this->_unlink(BASE_PATH . '/web/data/' . $this->_name[0] . '/' . $this->_name[1] . '/' . $this->_name[2] . '/' . $this->_name[3], $this->_name);
    }

    /**
     * Usuwa pliki ze ścieżki o danej nazwie
     * @param string $path ścieżka
     * @param string $name nazwa pliku
     */
    protected function _unlink($path, $name)
    {
        //pętla po wszystkich plikach
        foreach (glob($path . '/*') as $file) {
            if (is_dir($file)) {
                //rekurencyjnie schodzi katalog niżej
                $this->_unlink($file, $name);
                continue;
            }
            //kasowanie pliku jeśli nazwa jest zgodna z wzorcem
            if (basename($file) == $name) {
                unlink($file);
            }
        }
    }

}
