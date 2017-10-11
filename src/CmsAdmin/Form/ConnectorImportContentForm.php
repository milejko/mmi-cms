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
 * Pierwszy krok importu danych
 */
class ConnectorImportContentForm extends \Cms\Form\Form
{

    /**
     * Budowa formularza
     */
    public function init()
    {
        $this->addElement((new Element\Text('url'))
            ->addValidator(new \Mmi\Validator\Regex(['/^https?:\/\/[a-z0-9\.\/]+$/', 'link nie jest poprawnym adresem HTTPS']))
            ->setDescription('wymagany HTTPS')
            ->setRequired()
            ->setLabel('adres źródłowego CMS'));

        $this->addElement((new Element\Text('identity'))
            ->setLabel('login administratora źródłowego CMS')
            ->setRequired());

        $this->addElement((new Element\Password('credential'))
            ->setLabel('hasło administratora źródłowego CMS')
            ->setRequired());

        $this->addElement((new Element\Checkbox('acl'))
            ->setLabel('role i ich uprawnienia'));

        $this->addElement((new Element\Checkbox('content'))
            ->setLabel('treści cms (bez plików)'));

        $this->addElement((new Element\Checkbox('file'))
            ->setLabel('pliki'));

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('importuj'));
    }

    /**
     * Import danych
     * @return boolean
     */
    public function afterSave()
    {
        //model konektora
        $connector = new \Cms\Model\ConnectorModel;
        //zapis danych z pierwszego kroku do sesji
        $session = new \Mmi\Session\SessionSpace(\Cms\Model\ConnectorModel::SESSION_SPACE);
        //dane autoryzacyjne
        $session->identity = $this->getElement('identity')->getValue();
        $session->credential = $this->getElement('credential')->getValue();
        //adres zdalnego CMS
        $session->url = $this->getElement('url')->getValue();
        try {
            //pobranie danych
            $data = $connector->getData($session->url, 'exportContent', ['acl' => $this->getElement('acl')->isChecked(),
                'content' => $this->getElement('content')->isChecked()
                ], $session->identity, $session->credential);
        } catch (\Cms\Exception\ConnectorException $e) {
            //dane nie mogą być pobrane
            $this->getElement('url')->addError('Połączenie z CMS niemożliwe');
            return false;
        }
        //próba importu
        if (!$connector->importData($data)) {
            //dane są puste, lub nieprawidłowe
            $this->getElement('url')->addError('Dane są nieprawidłowe');
            return false;
        }
        return true;
    }

}
