<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Form\Element,
    Cms\Orm\CmsAuthQuery,
    Mmi\Validator,
    Mmi\Filter;

/**
 * Formularz dodawania i edycji użytkowników CMS
 * @method \Cms\Orm\CmsAuthRecord getRecord()
 */
class Auth extends \Cms\Form\Form
{

    public function init()
    {

        //nazwa użytkownika
        $this->addElement((new Element\Text('username'))
            ->setLabel('nazwa użytkownika (login)')
            ->setRequired()
            ->addFilter(new Filter\StringTrim)
            ->addValidator(new Validator\NotEmpty)
            ->addValidator(new Validator\RecordUnique([new CmsAuthQuery, 'username', $this->getRecord()->id])));

        //imię i nazwisko użytkownika
        $this->addElement((new Element\Text('name'))
            ->setLabel('pełna nazwa użytkownika (opcjonalna)')
            ->addFilter(new Filter\StringTrim));

        //email
        $this->addElement((new Element\Text('email'))
            ->setLabel('adres e-mail')
            ->setRequired()
            ->addFilter(new Filter\StringTrim)
            ->addValidator(new Validator\EmailAddress)
            ->addValidator(new Validator\RecordUnique([new CmsAuthQuery, 'email', $this->getRecord()->id])));

        //role
        $this->addElement((new Element\MultiCheckbox('cmsRoles'))
            ->setLabel('role')
            ->setDescription('Grupa uprawnień')
            ->setMultioptions((new \Cms\Orm\CmsRoleQuery)->findPairs('id', 'name'))
            ->setValue(\Cms\Orm\CmsAuthRoleQuery::byAuthId($this->_record->id)->findPairs('cms_role_id', 'cms_role_id'))
            ->addValidator(new Validator\NotEmpty(['Wymagane jest wybranie roli'])));

        $languages = [];
        foreach (\App\Registry::$config->languages as $language) {
            $languages[$language] = $language;
        }

        if (!empty($languages)) {
            $this->addElement((new Element\Select('lang'))
                ->setLabel('język')
                ->setMultioptions($languages)
                ->setDescription('Preferowany przez użytkownika język interfejsu'));
        }

        //aktywny
        $this->addElement((new Element\Checkbox('active'))
            ->setLabel('Aktywny'));

        //zmiana hasła
        $this->addElement((new Element\Text('changePassword'))
            ->setLabel('zmiana hasła')
            ->setDescription('Jeśli nie chcesz zmienić hasła lub używać domenowego, nie wypełniaj tego pola')
            ->addValidator(new Validator\StringLength([4, 128])));

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('zapisz użytkownika'));
    }

    /**
     * Przed zapisem - kalkulacja hasha hasła
     * @return boolean
     */
    public function beforeSave()
    {
        if ('' !== $password = $this->getElement('changePassword')->getValue()) {
            $this->getRecord()->password = \Cms\Model\Auth::getSaltedPasswordHash($password);
        }
        return true;
    }

    /**
     * Po zapisie nadawanie uprawnień
     * @return boolean
     */
    public function afterSave()
    {
        //nadawanie uprawnień
        \Cms\Model\Role::grant($this->getRecord()->id, $this->getElement('cmsRoles')->getValue());
        return true;
    }

}
