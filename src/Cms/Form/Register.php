<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form;

use Cms\Orm\CmsAuthQuery;

/**
 * Formularz rejestracji
 * @method \Cms\Orm\CmsAuthRecord getRecord()
 */
class Register extends \Cms\Form\Form
{

    public function init()
    {

        //nazwa użytkownika
        $this->addElementText('username')
            ->setLabel('nazwa użytkownika (nick)')
            ->setRequired()
            ->addValidatorAlnum()
            ->addValidatorRecordUnique(new CmsAuthQuery, 'username')
            ->addValidatorStringLength(4, 25)
            ->addFilterLowercase();

        //email
        $this->addElementText('email')
            ->setLabel('e-mail')
            ->setRequired()
            ->addValidatorEmailAddress()
            ->addValidatorRecordUnique(new CmsAuthQuery, 'email')
            ->addValidatorStringLength(4, 150)
            ->addFilterLowercase();

        //password
        $this->addElementPassword('password')
            ->setLabel('hasło')
            ->setRequired()
            ->addValidatorStringLength(4, 64);

        //potwierdzenie
        $this->addElementPassword('confirmPassword')
            ->setLabel('potwierdź hasło');

        //regulamin
        $this->addElementCheckbox('regulations')
            ->setLabel('Akceptuję regulamin')
            ->addValidatorNotEmpty()
            ->setRequired();

        $this->addElementSubmit('submit')
            ->setLabel('Zarejestruj');
    }

    /**
     * Sprawdzanie zgodności haseł
     * @return boolean
     */
    public function beforeSave()
    {
        if ($this->getElement('password')->getValue() != $this->getElement('confirmPassword')->getValue()) {
            $this->getElement('confirmPassword')->addError('Hasła niezgodne');
            return false;
        }
        //opcja zmiany (w tym przypadku ustawienia nowego) hasłą
        $this->getRecord()->password = \Cms\Model\Auth::getSaltedPasswordHash($this->getElement('password')->getValue());
        //domyślny język
        $this->getRecord()->lang = 'pl';
        return true;
    }

}
