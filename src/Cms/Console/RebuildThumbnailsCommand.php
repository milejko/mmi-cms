<?php

namespace Cms\Console;

use Mmi\Console\CommandAbstract;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RebuildThumbnailsCommand extends CommandAbstract
{

    CONST MAX_DIRECTORY_LENGTH = 12;

    public function configure()
    {
        $this->setName('cms:thumbnails:rebuild');
        $this->setDescription('Rebuild thumbnails');
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
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