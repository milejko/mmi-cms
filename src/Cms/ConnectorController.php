<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

/**
 * Kontroler łączący instancje CMS
 */
class ConnectorController extends \Mmi\Mvc\Controller
{

    //maksymalny rozmiar obsługiwanego pliku
    CONST MAX_FILE_SIZE = '32000000';

    /**
     * Inicjalizacja
     */
    public function init()
    {
        //ustawia typ json
        $this->getResponse()->setTypeJson();
    }

    /**
     * Importuje plik na podstawie nazwy
     */
    public function importFileAction()
    {
        //@TODO: import meta + dane osobno
        echo file_get_contents(base64_decode($this->url) . '/?module=cms&controller=connector&action=exportFile&name=' . $this->name);
        return '';
    }

    /**
     * Eksportuje binarium pliku
     * @return mixed
     * @throws \Mmi\Mvc\MvcNotFoundException
     * @throws \Mmi\Mvc\MvcForbiddenException
     */
    public function exportFileAction()
    {
        //wyszukiwanie pliku
        if (null === $file = (new Orm\CmsFileQuery)->whereName()->equals($this->name)
            ->findFirst()) {
            throw new \Mmi\Mvc\MvcNotFoundException('File not found');
        }
        //plik zbyt duży do transferu
        if ($file->size > self::MAX_FILE_SIZE) {
            throw new \Mmi\Mvc\MvcForbiddenException('File to large');
        }
        //zwrot meta i pluginów
        return json_encode(['meta' => $file->toArray(), 'data' => base64_encode(file_get_contents($file->getRealPath()))]);
    }

    /**
     * Eksporter zawartości
     * @return string json
     */
    public function exportContentAction()
    {
        //autoryzacja
        $this->_authenticate();
        //json
        return json_encode((new Model\ConnectorModel)->getExportData((bool) $this->getPost()->acl, (bool) $this->getPost()->content));
    }

    /**
     * Eksporter listy plików
     * @return string json
     */
    public function exportFileObjectAction()
    {
        //autoryzacja
        $this->_authenticate();
        //json
        return json_encode((new Model\ConnectorModel)->getFileObjects());
    }

    /**
     * Eksporter meta danych plików (z wybranych obiektów)
     * @return string json
     */
    public function exportFileListAction()
    {
        //autoryzacja
        $this->_authenticate();
        //json z plikami
        return json_encode((new Model\ConnectorModel)->getFileList($this->getPost()->fileObjects));
    }

    /**
     * Autoryzacja zapytania
     * @throws \Mmi\Mvc\MvcNotFoundException
     * @throws \Mmi\Mvc\MvcForbiddenException
     */
    private function _authenticate()
    {
        //sprawdzenie odcisku wersji CMS
        if ((new Model\ConnectorModel)->getInstanceHash() != $this->getPost()->instanceHash) {
            //not found
            throw new \Mmi\Mvc\MvcNotFoundException('Version mismatch');
        }
        //próba autoryzacji credentialami
        if (false === \App\Registry::$auth->setIdentity($this->getPost()->identity)
                ->setCredential($this->getPost()->credential)
                ->authenticate()) {
            //forbidden
            throw new \Mmi\Mvc\MvcForbiddenException('Transaction forbidden');
        }
    }

}
