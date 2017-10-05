<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

/**
 * Kontroler łączący różne instancje CMS
 */
class ConnectorController extends \Mmi\Mvc\Controller
{

    //maksymalny rozmiar obsługiwanego pliku
    CONST MAX_FILE_SIZE = '32000000';

    public function init()
    {
        $this->getResponse()->setTypeJson();
    }

    public function importFileAction()
    {
        $session = new \Mmi\Session\SessionSpace(\CmsAdmin\Form\ConnectorImportContentForm::SESSION_SPACE);
        //(new \Cms\Model\ConnectorModel)->getInstanceHash();
        return 'download-' . $this->name . $session->url;
    }

    public function exportFileAction()
    {
        //błędna wersja CMS
        if ((new Model\ConnectorModel)->getInstanceHash() != $this->instanceHash) {
            throw new \Mmi\Mvc\MvcNotFoundException('Version mismatch');
        }
        if (null === $file = (new Orm\CmsFileQuery)->whereName()->equals($this->name)
            ->findFirst()) {
            throw new \Mmi\Mvc\MvcNotFoundException('File not found');
        }
        if ($file->size > self::MAX_FILE_SIZE) {
            throw new \Mmi\Mvc\MvcForbiddenException('File to large');
        }
        return file_get_contents($file->getRealPath());
    }

    /**
     * Eksporter zawartości
     * @return json
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
     * @return json
     */
    public function exportFileObjectAction()
    {
        //autoryzacja
        $this->_authenticate();
        //json
        return json_encode((new Model\ConnectorModel)->getFileObjects());
    }

    public function exportFileMetaAction()
    {
        //autoryzacja
        $this->_authenticate();
        return json_encode((new Model\ConnectorModel)->getFileMeta($this->getPost()->fileObjects));
    }

    /**
     * Autoryzacja zapytania
     * @throws \Mmi\Mvc\MvcNotFoundException
     * @throws \Mmi\Mvc\MvcForbiddenException
     */
    private function _authenticate()
    {
        //błędna wersja CMS
        if ((new Model\ConnectorModel)->getInstanceHash() != $this->getPost()->instanceHash) {
            throw new \Mmi\Mvc\MvcNotFoundException('Version mismatch');
        }
        //błędne credentiale
        if (false === \App\Registry::$auth->setIdentity($this->getPost()->identity)
                ->setCredential($this->getPost()->credential)
                ->authenticate()) {
            throw new \Mmi\Mvc\MvcForbiddenException('Transaction forbidden');
        }
    }

}
