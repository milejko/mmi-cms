<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Form\Element;

/**
 * Drugi krok - lista obiektów plików do importu
 */
class ConnectorImportFilesForm extends \Mmi\Form\Form
{

    /**
     * Budowanie formularza
     */
    public function init()
    {
        //wczytywanie sesji
        $session = new \Mmi\Session\SessionSpace(\Cms\Model\ConnectorModel::SESSION_SPACE);
        try {
            $data = (new \Cms\Model\ConnectorModel)->getData($session->url, 'exportFileObject', [], $session->identity, $session->credential);
        } catch (\Cms\Exception\ConnectorException $e) {
            //rollback - puste dane
            $data = [];
        }

        //obiekty do przesłania
        if (!empty($data)) {
            //lista obiektów
            $this->addElement((new Element\MultiCheckbox('fileObjects'))
                ->setLabel('klasy plików')
                ->setMultioptions($data)
                ->setValue($data));

            //submit
            $this->addElement((new Element\Submit('submit'))
                ->setLabel('importuj'));
        }
    }

    /**
     * Pobranie listy plików (wraz z meta)
     * @return boolean
     */
    public function afterSave()
    {
        //brak filtra
        if (!$this->getElement('fileObjects')) {
            return false;
        }
        //wczytywanie sesji
        $session = new \Mmi\Session\SessionSpace(\Cms\Model\ConnectorModel::SESSION_SPACE);
        try {
            //pobranie plików z connectora
            $remoteFiles = (new \Cms\Model\ConnectorModel)->getData($session->url, 'exportFileList', ['fileObjects' => $this->getElement('fileObjects')->getValue()], $session->identity, $session->credential);
        } catch (\Cms\Exception\ConnectorException $e) {
            //brak plików
            $this->getElement('fileObjects')->addError('Brak plików');
            return false;
        }
        $files = [];
        foreach ($remoteFiles as $name => $userName) {
            if (null !== (new \Cms\Orm\CmsFileQuery)->whereName()->equals($name)->findFirst()) {
                continue;
            }
            $files[$name] = $userName;
        }
        //ustawienie danych na elementy pobrane z connectora
        $this->setOption('data', $files);
        return true;
    }

}
