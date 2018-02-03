<?php

namespace Cms\Console;

use Mmi\Console\CommandAbstract;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PosterMigratorCommand extends CommandAbstract
{

    public function configure()
    {
        $this->setName('cms:file:poster-migrator');
        $this->setDescription('Files poster migrator');
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ((new \Cms\Orm\CmsFileQuery)->whereData()->like('%poster%')
            ->find() as $file) {
            if (null !== $fileName = $this->_savePoster($file)) {
                echo $this->_savePoster($file) . "\n";
            }
            unset($file->data->poster);
            $file->save();
        }
    }

    private function _savePoster(\Cms\Orm\CmsFileRecord $file)
    {
        //brak postera
        if (!$file->data->poster) {
            return;
        }
        //brak danych
        if (!\preg_match('/^data:(image\/[a-z]+);base64,(.*)/i', $file->data->poster, $match)) {
            return;
        }
        //nazwa postera
        $posterFileName = substr($file->name, 0, strpos($file->name, '.')) . '-' . $file->id . '.' . \Mmi\Http\ResponseTypes::getExtensionByType($match[1]);
        //próba utworzenia katalogu
        try {
            //tworzenie katalogu
            mkdir(dirname($file->getRealPath()), 0777, true);
        } catch (\Exception $e) {
            //nic
        }
        //zapis
        file_put_contents(str_replace($file->name, $posterFileName, $file->getRealPath()), $this->_parseBaseBinary($file->data->poster));
        //zapis do rekordu
        $file->data->posterFileName = $posterFileName;
        return $posterFileName;
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
