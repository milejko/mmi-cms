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
        //text/plain
        $this->getResponse()->setTypePlain();
        //adres endpointu
        $endpoint = base64_decode($this->url) . '/?module=cms&controller=connector&name=' . $this->name . '&action=';
        try {
            //wczytanie danych
            $data = json_decode(file_get_contents($endpoint . 'exportFileMeta'), true);
        } catch (\Exception $e) {
            //zwrot pustego statusu
            return 'ERR';
        }
        //próba importu meta-danych
        if (null === $file = (new Model\ConnectorModel)->importFileMeta($data)) {
            //plik istnieje, lub próba nie udana
            return 'META ERROR';
        }
        try {
            //rekursywne tworzenie katalogów
            mkdir(dirname($file->getRealPath()), 0777, true);
            //próba pobrania i zapisu binarium
            file_put_contents($file->getRealPath(), file_get_contents($endpoint . 'exportFileBinary'));
        } catch (\Exception $e) {
            //zwrot pustego statusu
            return 'BIN ERROR';
        }
        return 'OK';
    }

    /**
     * Eksportuje binarium pliku
     * @return mixed
     * @throws \Mmi\Mvc\MvcNotFoundException
     * @throws \Mmi\Mvc\MvcForbiddenException
     */
    public function exportFileBinaryAction()
    {
        $this->getResponse()->setType('application/octet-stream')
            ->send();
        //wyszukiwanie pliku
        if (null === $file = (new Orm\CmsFileQuery)->whereName()->equals($this->name)
            ->findFirst()) {
            throw new \Mmi\Mvc\MvcNotFoundException('File not found');
        }
        //plik zbyt duży do transferu
        if ($file->size > self::MAX_FILE_SIZE) {
            throw new \Mmi\Mvc\MvcForbiddenException('File to large');
        }
        readfile($file->getRealPath());
        exit;
    }

    /**
     * Eksportuje meta pliku
     * @return mixed
     * @throws \Mmi\Mvc\MvcNotFoundException
     * @throws \Mmi\Mvc\MvcForbiddenException
     */
    public function exportFileMetaAction()
    {
        //wyszukiwanie pliku
        if (null === $file = (new Orm\CmsFileQuery)->whereName()->equals($this->name)
            ->findFirst()) {
            throw new \Mmi\Mvc\MvcNotFoundException('File not found');
        }
        //zwrot meta i pluginów
        return json_encode($file->toArray());
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
