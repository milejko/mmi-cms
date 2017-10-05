<?php

namespace CmsAdmin\Form;

class ConnectorImportFilesForm extends \Mmi\Form\Form
{

    CONST SESSION_SPACE = 'connector-data';

    public function init()
    {
        $session = new \Mmi\Session\SessionSpace(self::SESSION_SPACE);
        $query = $session->url . '/?' . http_build_query([
                'module' => 'cms',
                'controller' => 'connector',
                'action' => 'exportFileObject'
        ]);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query([
                    'identity' => $session->identity,
                    'credential' => $session->credential,
                    'instanceHash' => (new \Cms\Model\ConnectorModel)->getInstanceHash()
                ])
            ]
        ]);
        try {
            $data = json_decode(file_get_contents($query, false, $context), true);
        } catch (\Exception $e) {
            $data = [];
        }
        $this->addElementMultiCheckbox('fileObjects')
            ->setLabel('klasy plików')
            ->setMultioptions($data)
            ->setValue($data);

        $this->addElementSubmit('submit')
            ->setLabel('importuj');
    }

    public function afterSave()
    {
        $session = new \Mmi\Session\SessionSpace(self::SESSION_SPACE);
        $query = $session->url . '/?' . http_build_query([
                'module' => 'cms',
                'controller' => 'connector',
                'action' => 'exportFileMeta'
        ]);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query([
                    'fileObjects' => $this->getElement('fileObjects')->getValue(),
                    'identity' => $session->identity,
                    'credential' => $session->credential,
                    'instanceHash' => (new \Cms\Model\ConnectorModel)->getInstanceHash()
                ])
            ]
        ]);
        try {
            $data = json_decode(file_get_contents($query, false, $context), true);
        } catch (\Exception $e) {
            $this->getElement('fileObjects')->addError('Brak plików');
            return false;
        }
        $this->setOption('data', $data);
        return true;
    }

}
