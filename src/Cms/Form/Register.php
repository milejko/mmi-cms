<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form;

use Cms\Orm\CmsAuthQuery,
    Mmi\Form\Element,
    Mmi\Validator;

/**
 * Formularz rejestracji
 * @method \Cms\Orm\CmsAuthRecord getRecord()
 */
class Register extends \Mmi\Form\Form
{

    public function init()
    {

        //nazwa użytkownika
        $this->addElement((new Element\Text('username'))
            ->setLabel('form.register.username.label')
            ->setRequired()
            ->addValidator(new Validator\Alnum)
            ->addValidator(new Validator\RecordUnique([new CmsAuthQuery, 'username']))
            ->addValidator(new Validator\StringLength([4, 25]))
            ->addFilter(new \Mmi\Filter\Lowercase));

        //email
        $this->addElement((new Element\Text('email'))
            ->setLabel('form.register.email.label')
            ->setRequired()
            ->addValidator(new Validator\EmailAddress)
            ->addValidator(new Validator\RecordUnique([new CmsAuthQuery, 'email']))
            ->addValidator(new Validator\StringLength([4, 150]))
            ->addFilter(new \Mmi\Filter\Lowercase));

        //password
        $this->addElement((new Element\Password('password'))
            ->setLabel('form.register.password.label')
            ->setRequired()
            ->addValidator(new Validator\StringLength([4, 64])));

        //potwierdzenie
        $this->addElement((new Element\Password('confirmPassword'))
            ->setLabel('form.register.confirmPassword.label'));

        //regulamin
        $this->addElement((new Element\Checkbox('regulations'))
            ->setLabel('form.register.regulations.label')
            ->addValidator(new Validator\NotEmpty)
            ->setRequired());

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('form.register.submit.label'));
    }

    /**
     * Sprawdzanie zgodności haseł
     * @return boolean
     */
    public function beforeSave()
    {
        if ($this->getElement('password')->getValue() != $this->getElement('confirmPassword')->getValue()) {
            $this->getElement('confirmPassword')->addError('form.register.confirmPassword.error');
            return false;
        }
        //opcja zmiany (w tym przypadku ustawienia nowego) hasłą
        $this->getRecord()->password = \Cms\Model\Auth::getSaltedPasswordHash($this->getElement('password')->getValue());
        //domyślny język
        $this->getRecord()->lang = \App\Registry::$translate->getLocale();
        return true;
    }

}
