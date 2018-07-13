<?php

namespace Cms\Console;

use Cms\Model\FileSystemModel;
use Cms\Orm\CmsFileQuery;
use Mmi\Console\CommandAbstract;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FilesystemMigratorCommand extends CommandAbstract
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

    public function configure()
    {
        $this->setName('cms:file:migrator');
        $this->setDescription('Filesystem migrator');
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ((new CmsFileQuery)->find() as $fileRecord) {
            $this->_found++;
            $fsm = new FileSystemModel($fileRecord->name);
            if (file_exists($fsm->getRealPath())) {
                continue;
            }
            //sprawdzanie istnienia pliku w starej ścieżce
            if (!file_exists($oldPath = BASE_PATH . '/var/data/' . $fileRecord->name[0] . '/' . $fileRecord->name[1] . '/' . $fileRecord->name[2] . '/' . $fileRecord->name[3] . '/' . $fileRecord->name)) {
                continue;
            }
            $this->_count++;
            $this->_size += $fileRecord->size;
            //próba tworzenia katalogów
            try {
                mkdir(dirname($fsm->getRealPath()), 0777, true);
            } catch (\Mmi\App\KernelException $e) {
                //nic
            }
            //przeniesienie pliku
            rename($oldPath, $fsm->getRealPath());
            //raport
            $this->_reportLine($fileRecord->name);
        }
        echo "\n";
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