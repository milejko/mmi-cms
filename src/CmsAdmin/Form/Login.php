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
use Mmi\App\App;
use Mmi\Security\Auth;

class Login extends \Cms\Form\Form
{

    public function init()
    {

        $this->addElement((new Element\Text('username'))
            ->setLabel('form.login.username.label')
            ->setDescription('form.login.username.description')
            ->addFilter(new \Mmi\Filter\StringTrim));

        $this->addElement((new Element\Password('password'))
            ->setLabel('form.login.password.label')
            ->addValidator(new \Mmi\Validator\StringLength([4, 128])));

        $this->addElement((new Element\Submit('login'))
            ->setLabel('form.login.submit'));
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
        $auth = App::$di->get(Auth::class);
        $auth->setIdentity($this->getElement('username')->getValue());
        $auth->setCredential($this->getElement('password')->getValue());
        return $auth->authenticate();
    }

}
