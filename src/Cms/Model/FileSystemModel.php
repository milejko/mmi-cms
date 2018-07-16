<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsFileQuery;
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
     * Ścieżka względna do zasobu
     * @var string
     */
    private $_path;

    /**
     * Konstruktor
     * @param string $fileName
     * @throws \Mmi\App\KernelException
     */
    public function __construct($fileName, $object = null, $mimeType = null)
    {
        //niepoprawna nazwa (np null)
        if (strlen($fileName) < 4) {
            throw new \Mmi\App\KernelException('File name invalid');
        }
        //ustalanie nazwy pliku
        $this->_name = $fileName;
        //obiekt i typ podane explicit
        if ($object && $mimeType) {
            //ustalanie ścieżki
            $this->_path = self::calculatePath($fileName, $object, $mimeType);
            return;
        }
        //próba wczytania ścieżki z bufora
        if (null !== $this->_path = FrontController::getInstance()->getLocalCache()->load($cacheKey = 'cms-file-path-' . $fileName)) {
            return;
        }
        //wyszukiwanie rekordu przypisanego do podanej nazwy
        if (null === $fileRecord = (new CmsFileQuery)->whereName()->equals($fileName)->findFirst()) {
            //zapis pustej ścieżki do bufora
            FrontController::getInstance()->getLocalCache()->save('', $cacheKey);
            throw new \Mmi\App\KernelException('File meta not found' . $fileName);
        }
        //ustalanie ścieżki
        $this->_path = self::calculatePath($fileName, $fileRecord->object, $fileRecord->mimeType);
        //zapis informacji o ścieżce w buforze
        FrontController::getInstance()->getLocalCache()->save($this->_path, $cacheKey, 0);
    }

    /**
     * Kalkulacja ścieżki
     * @param string $fileName
     * @param string $object
     * @param string $mimeType
     * @return string
     */
    public static function calculatePath($fileName, $object, $mimeType)
    {
        //ścieżka składa się z obiektu, mimetype i serii katalogów
        return strtolower($object) . str_replace('/', '', $mimeType) . '/' . $fileName[0] . $fileName[1] . '/' . $fileName[2] . $fileName[3];
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
        return BASE_PATH . '/var/data/' . $this->_path . '/' . $this->_name;
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
        $fileName = '/' . $this->_path . '/' . $scaleType . '/' . $scale . '/' . $this->_name;
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
        if (file_exists($thumbPath = BASE_PATH . '/web/data' . $fileName) && filemtime($thumbPath) > filemtime($inputFile)) {
            FrontController::getInstance()->getLocalCache()->save(true, $cacheKey);
            return $publicUrl;
        }
        //tworzenie katalogu
        $this->_createDirectory($thumbPath);
        //wybrano skalowanie dla klasy obrazu
        if ($scaleType != 'default') {
            //uruchomienie skalera
            $this->_scaler($inputFile, $thumbPath, $scaleType, $scale);
            return $publicUrl;
        }
        try {
            //próba skopiowania pliku
            copy($inputFile, $thumbPath);
        } catch (\Exception $e) {
            FrontController::getInstance()->getLogger()->warning('Unable to copy CMS file to web: ' . $fileName);
            return;
        }
        //zwrot ścieżki publicznej
        return $publicUrl;
    }

    /**
     * Przenoszenie pliku z obiektu na obiekt
     * @param $oldObject
     * @param $newObject
     */
    public function moveToObject($oldObject, $newObject)
    {
        //obliczanie nowego katalogu
        $newPath = str_replace(strtolower($oldObject), strtolower($newObject), $this->getRealPath());
        //tworzenie katalogu
        $this->_createDirectory($newPath);
        //przenoszenie pliku
        rename($this->getRealPath(), $newPath);
    }

    /**
     * Usuwane pliku
     */
    public function unlink()
    {
        //usuwa plik
        if (file_exists($this->getRealPath()) && is_writable($this->getRealPath())) {
            unlink($this->getRealPath());
        }
        //usuwa miniatury
        $this->_unlink(BASE_PATH . '/web/data/' . $this->_path, $this->_name);
    }

    /**
     * Tworzy thumby
     * @param string $inputFile
     * @param string $outputFile
     * @param string $scaleType
     * @param string $scale
     */
    protected function _scaler($inputFile, $outputFile, $scaleType, $scale)
    {
        switch ($scaleType) {
            //skalowanie domyślne
            case 'default':
                $imgRes = \Mmi\Image\Image::inputToResource($inputFile);
                break;
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
        //tworzenie katalogu jeśli nie istnieje
        if (!file_exists(dirname($outputFile)) && !$this->_createDirectory($outputFile)) {
            //utworzenie nieudane
            FrontController::getInstance()->getLogger()->warning('Unable to create directories: ' . $outputFile);
            return;
        }
        //określanie typu wyjścia
        $mimeType = \Mmi\FileSystem::mimeType($inputFile);
        //GIF
        if ($mimeType == 'image/gif') {
            imagealphablending($imgRes, false);
            imagesavealpha($imgRes, true);
            imagegif($imgRes, $outputFile);
            return;
        }
        //PNG (nieprzeźroczysty)
        if ($mimeType == 'image/png') {
            imagealphablending($imgRes, false);
            imagesavealpha($imgRes, true);
            imagepng($imgRes, $outputFile, 9);
            return;
        }
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
     * Tworzenie katalogu wraz z rodzicami
     * @param $path
     * @return bool
     */
    protected function _createDirectory($path) {
        //próba tworzenia katalogów
        try {
            mkdir(dirname($path), 0777, true);
            return true;
        } catch (\Mmi\App\KernelException $e) {
            return false;
        }
    }

}
