<?php

namespace CmsAdmin\Form;

class ConnectorImportContentForm extends \Mmi\Form\Form
{

    CONST SESSION_SPACE = 'connector-data';

    public function init()
    {
        $this->addElementText('url')
            ->addValidatorRegex('/^https?:\/\/[a-z0-9\.\/]+$/', 'link nie jest poprawnym adresem HTTPS')
            ->setDescription('wymagany HTTPS')
            ->setRequired()
            ->setLabel('adres źródłowego CMS');

        $this->addElementText('identity')
            ->setLabel('login administratora źródłowego CMS')
            ->setRequired();

        $this->addElementPassword('credential')
            ->setLabel('hasło administratora źródłowego CMS')
            ->setRequired();

        $this->addElementCheckbox('acl')
            ->setLabel('role i ich uprawnienia');

        $this->addElementCheckbox('content')
            ->setLabel('treści cms (bez plików)');

        $this->addElementCheckbox('file')
            ->setLabel('pliki');

        $this->addElementSubmit('submit')
            ->setLabel('importuj');
    }

    public function afterSave()
    {
        $session = new \Mmi\Session\SessionSpace(self::SESSION_SPACE);
        $session->identity = $this->getElement('identity')->getValue();
        $session->credential = $this->getElement('credential')->getValue();
        $session->url = $this->getElement('url')->getValue();
        $query = $session->url . '/?' . http_build_query([
                'module' => 'cms',
                'controller' => 'connector',
                'action' => 'exportContent'
        ]);

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query([
                    'acl' => $this->getElement('acl')->getValue(),
                    'content' => $this->getElement('content')->getValue(),
                    'identity' => $session->identity,
                    'credential' => $session->credential,
                    'instanceHash' => (new \Cms\Model\ConnectorModel)->getInstanceHash()
                ])
            ]
        ]);

        try {
            $data = file_get_contents($query, false, $context);
        } catch (\Exception $e) {
            $this->getElement('url')->addError('Połączenie z CMS niemożliwe');
            return false;
        }
        if (!(new \Cms\Model\ConnectorModel)->importData($data)) {
            $this->getElement('url')->addError('Dane są nieprawidłowe');
            return false;
        }
        return true;
    }

}
