<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Form\Element;

class Login extends \Mmi\Form\Form
{

    public function init()
    {

        $this->addElement((new Element\Text('username'))
            ->setLabel('nazwa użytkownika')
            ->setDescription('wpisz swój unikalny identyfikator')
            ->addFilterStringTrim());

        $this->addElement((new Element\Password('password'))
            ->setLabel('hasło')
            ->addValidator(new \Mmi\Validator\StringLength(4, 128)));

        $this->addElement((new Element\Submit('login'))
            ->setLabel('zaloguj się'));
    }

    /**
     * Logowanie
     * @return boolean
     */
    public function beforeSave()
    {
        //brak loginu lub hasła
        if (!$this->getElement('username')->getValue() || !$this->getElement('password')->getValue()) {
            return false;
        }
        //autoryzacja
        $auth = \App\Registry::$auth;
        \App\Registry::$auth->setIdentity($this->getElement('username')->getValue());
        \App\Registry::$auth->setCredential($this->getElement('password')->getValue());
        return \App\Registry::$auth->authenticate();
    }

}
