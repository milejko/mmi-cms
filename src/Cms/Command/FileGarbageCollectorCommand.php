<?php

namespace Cms\Command;

use Mmi\Command\CommandAbstract;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FileGarbageCollectorCommand extends CommandAbstract
{

    /**
     * Rormiar plików niepowiązanych
     * @var integer
     */
    protected $_size = 0;

    /**
     * Ilość plików niepowiązanych
     * @var integer
     */
    protected $_count = 0;

    /**
     * Ilość plików odnalezionych
     * @var integer
     */
    protected $_found = 0;

    /**
     * Execute
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        //dane (tylko podkatalogi jednoznakowe)
        $this->_scanDir(BASE_PATH . '/var/data', 1);
        //miniatury (podkatalogi do 12 znaków)
        $this->_scanDir(BASE_PATH . '/web/data', 12);
        $output->writeln('Garbage collection completed.');
        return 0;
    }

    /**
     * Skanuje katalog (rekurencja)
     * @param string $directory
     * @param integer $maxDirLength
     */
    protected function _scanDir($directory, $maxDirLength)
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
            if (strlen($object) > $maxDirLength) {
                continue;
            }
            $this->_scanDir($directory . '/' . $object, $maxDirLength);
        }
    }

    /**
     * Sprawdza istnienie pliku
     * @param string $directory
     * @param string $file
     */
    protected function _checkForFile($directory, $file)
    {
        //szukamy tylko plików w przedziale 33 - 37 znaków
        if (strlen($file) < 33 || strlen($file) > 37) {
            echo $file . "\n";
            return;
        }
        //brak pliku w plikach CMS
        if (null === $fr = (new \Cms\Orm\CmsFileQuery)
                ->whereName()->equals($file)
                ->findFirst()
        ) {
            $this->_size += filesize($directory . '/' . $file);
            $this->_count++;
            unlink($directory . '/' . $file);
            return $this->_reportLine($directory . '/' . $file);
        }
        $this->_found++;
    }

    /**
     * Linia raportująca
     */
    protected function _reportLine($fileName)
    {
        echo $fileName . ' - ' . round($this->_size / 1024 / 1024,
                2) . 'MB in ' . $this->_count . ' files - found: ' . $this->_found . "\n";
    }

}