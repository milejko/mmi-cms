#!/usr/bin/env php
<?php
/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Command;

//nie ma tu jeszcze autoloadera ładowanie CliAbstract
require_once 'CommandAbstract.php';

/**
 * Usuwa pliki bez powiązań w strukturze
 */
class RebuildThumbnails extends \Mmi\Command\CommandAbstract
{

    CONST MAX_DIRECTORY_LENGTH = 12;

    public function run()
    {
        //skanowanie katalogu miniatur
        $this->_scanDir(BASE_PATH . '/web/data');
        echo "\n";
    }

    /**
     * Skanuje katalog (rekurencja)
     * @param string $directory
     */
    protected function _scanDir($directory)
    {
        //iteracja po katalogu
        foreach (new \DirectoryIterator($directory) as $object) {
            //katalog główny
            if ($object == '.' || $object == '..') {
                continue;
            }
            //obiekt jest plikiem
            if (is_file($directory . '/' . $object)) {
                $this->_checkForFile($directory, $object);
                continue;
            }
            //tylko jednoznakowe katalogi będą parsowane
            if (strlen($object) > self::MAX_DIRECTORY_LENGTH) {
                continue;
            }
            $this->_scanDir($directory . '/' . $object);
        }
    }

    /**
     * Sprawdza istnienie pliku
     * @param string $directory
     * @param string $file
     */
    protected function _checkForFile($directory, $file)
    {
        $realPath = realpath($directory . '/' . $file);
        //szukamy tylko plików o długości 33 znaków+
        if (strlen($file) < 33) {
            return $this->_reportLine($realPath, 'ommited');
        }
        if (null === $fileRecord = (new \Cms\Orm\CmsFileQuery)
            ->whereName()->equals($file)
            ->findFirst()
        ) {
            return $this->_reportLine($realPath, 'not found in cms file');
        }
        $matches = [];
        //parsowanie katalogu miniatur
        if (preg_match('/web\/data\/([a-f0-9]\/){4}([a-z]+)\/([0-9]+x?([0-9]+)?)/i', $directory, $matches)) {
            //usuwanie pliku
            unlink($realPath);
            //regeneracja thumba
            return $this->_reportLine($fileRecord->getUrl($matches[2], $matches[3]), 'regenerated');
        }
        return $this->_reportLine($realPath, 'not an image');
    }

    /**
     * Linia raportująca
     */
    protected function _reportLine($fileName, $message)
    {
        echo $fileName . ' ' . $message . "\n";
        ob_flush();
        flush();
    }

}

//nowy obiekt renderujący 
new RebuildThumbnails(isset($argv[1]) ? $argv[1] : null);
