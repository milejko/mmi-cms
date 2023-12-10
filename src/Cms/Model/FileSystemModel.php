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
use Mmi\App\KernelException;

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

    public const THUMB_EXTENSION = '.webp';
    public const PATH_SEPARATOR = '/';
    public const DATA_PATH = 'data';
    public const DOWNLOAD_PATH = 'download';
    private const ALLOWED_SCALER_METHOD = ['scale', 'scalex', 'scaley', 'scalecrop'];

    public function __construct(string $fileName)
    {
        if (strlen($fileName) < 4) {
            throw new KernelException('File name invalid');
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
    public function getThumbPath(string $scaleType = 'default', string $scale = ''): string
    {
        if (!in_array($scaleType, self::ALLOWED_SCALER_METHOD)) {
            throw new KernelException('Invalid scaler method: ' . $scaleType);
        }
        return self::PATH_SEPARATOR .
            self::DATA_PATH .
            self::PATH_SEPARATOR .
            $scaleType . ($scale ? self::PATH_SEPARATOR . $scale : '') .
            self::PATH_SEPARATOR .
            $this->_name[0] .
            $this->_name[1] .
            self::PATH_SEPARATOR .
            $this->_name[2] .
            $this->_name[3] .
            self::PATH_SEPARATOR .
            md5($scaleType . $scale . $this->_name . App::$di->get('cms.auth.salt')) .
            $this->_name .
            self::THUMB_EXTENSION;
    }

    public function getDownloadPath(string $targetName): string
    {
        return self::PATH_SEPARATOR .
            self::DATA_PATH .
            self::PATH_SEPARATOR .
            self::DOWNLOAD_PATH .
            self::PATH_SEPARATOR .
            $this->_name[0] .
            $this->_name[1] .
            self::PATH_SEPARATOR .
            $this->_name[2] .
            $this->_name[3] .
            self::PATH_SEPARATOR .
            md5($targetName . $this->_name . App::$di->get('cms.auth.salt')) .
            $this->_name .
            self::PATH_SEPARATOR .
            $targetName;
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
