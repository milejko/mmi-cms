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
        //plik źródłowy
        $inputFile = $this->getRealPath();
        $fileName = '/' . $this->_name[0] . '/' . $this->_name[1] . '/' . $this->_name[2] . '/' . $this->_name[3] . '/' . $scaleType . '/' . $scale . '/' . $this->_name;
        //inicjalizacja linku publicznego
        $publicUrl = '/data' . $fileName;
        //istnieje plik - wiadomość z bufora
        if (FrontController::getInstance()->getLocalCache()->load($cacheKey = 'cms-file-' . md5($fileName))) {
            return $publicUrl;
        }
        //brak pliku źródłowego
        if (!file_exists($inputFile)) {
            FrontController::getInstance()->getLogger()->warning('CMS file not found: ' . $fileName);
            return;
        }
        //istnieje plik - zwrot ścieżki publicznej
        if (file_exists($thumbPath = BASE_PATH . '/web/data' . $fileName)) {
            FrontController::getInstance()->getLocalCache()->save(true, $cacheKey);
            return $publicUrl;
        }
        //próba tworzenia katalogów
        try {
            mkdir(dirname($thumbPath), 0777, true);
        } catch (\Mmi\App\KernelException $e) {
            //nic
        }
        //wybrano skalowanie dla klasy obrazu
        if ($scaleType == 'default') {
            //kopiowanie pliku do web
            return $this->_copyFileToWeb($inputFile, $thumbPath, $publicUrl);
        }
        //uruchomienie skalera
        $this->_scaler($inputFile, $thumbPath, $scaleType, $scale);
        return $publicUrl;
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
     * Makes the thumb and return its address
     *
     * @param string $inputFile
     * @param string $outputFile
     * @param string $scaleType
     * @param string $scale
     * @return string
     */
    protected function _scaler($inputFile, $outputFile, $scaleType, $scale)
    {
        switch ($scaleType) {
            //skalowanie proporcjonalne do maksymalnego rozmiaru
            case 'scale':
                $v = explode('x', $scale);
                if (count($v) == 1 && is_numeric($v[0]) && intval($v[0]) > 0) {
                    $imgRes = \Mmi\Image\Image::scale($inputFile, $v[0]);
                } elseif (count($v) == 2 && is_numeric($v[0]) && intval($v[0]) > 0 && is_numeric($v[1]) && intval($v[1]) > 0) {
                    $imgRes = \Mmi\Image\Image::scale($inputFile, $v[0], $v[1]);
                }
                break;
            //skalowanie do maksymalnego X
            case 'scalex':
                $imgRes = \Mmi\Image\Image::scalex($inputFile, intval($scale));
                break;
            //skalowanie do maksymalnego Y
            case 'scaley':
                $imgRes = \Mmi\Image\Image::scaley($inputFile, intval($scale));
                break;
            //skalowanie z obcięciem
            case 'scalecrop':
                $v = explode('x', $scale);
                if (is_numeric($v[0]) && intval($v[0]) > 0 && is_numeric($v[1]) && intval($v[1]) > 0) {
                    $imgRes = \Mmi\Image\Image::scaleCrop($inputFile, $v[0], $v[1]);
                }
                break;
        }
        //brak obrazu
        if (!isset($imgRes)) {
            FrontController::getInstance()->getLogger()->warning('Unable to resize CMS file: ' . $outputFile);
            return;
        }
        //plik istnieje
        if (!file_exists(dirname($outputFile))) {
            try {
                mkdir(dirname($outputFile), 0777, true);
            } catch (\Mmi\App\KernelException $e) {
                FrontController::getInstance()->getLogger()->warning('Unable to create directories: ' . $e->getMessage());
                return;
            }
        }
        //określanie typu wyjścia
        $mimeType = \Mmi\FileSystem::mimeType($inputFile);
        //GIF
        if ($mimeType == 'image/gif') {
            imagegif($imgRes, $outputFile);
            return;
        }
        //PNG
        if ($mimeType == 'image/png') {
            //nieprzeźroczysty
            if (!((imagecolorat($imgRes, 0, 0) & 0x7F000000) >> 24)) {
                //redukcja palety do 256 + dithering
                imagetruecolortopalette($imgRes, true, 256);
            }
            imagepng($imgRes, $outputFile, 9);
            return;
        }
        //progressive jpeg
        imageinterlace($imgRes, true);
        //domyślnie JPEG
        imagejpeg($imgRes, $outputFile, intval(\App\Registry::$config->thumbQuality));
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

    /**
     * Kopiuje plik do web (bez zmian)
     * @param $inputFile
     * @param $thumbPath
     * @param $publicPath
     */
    protected function _copyFileToWeb($inputFile, $thumbPath, $publicPath)
    {
        try {
            //próba skopiowania pliku
            copy($inputFile, $thumbPath);
        } catch (\Exception $e) {
            FrontController::getInstance()->getLogger()->warning('Unable to copy CMS file to web: ' . $publicPath);
            return;
        }
        return $publicPath;
    }

}
