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
use Cms\Form\Form;
use Cms\Orm\CmsAuthQuery;
use Mmi\Validator;
use Mmi\Filter;
use Mmi\App\App;
use Mmi\Security\AclInterface;
use Mmi\Security\AuthProviderInterface;

/**
 * Formularz dodawania i edycji użytkowników CMS
 * @method \Cms\Orm\CmsAuthRecord getRecord()
 */
class Auth extends Form
{
    public function init()
    {
        //nazwa użytkownika
        $this->addElement((new Element\Text('username'))
            ->setLabel('form.auth.username.label')
            ->setRequired()
            ->addFilter(new Filter\StringTrim())
            ->addValidator(new Validator\NotEmpty())
            ->addValidator(new Validator\RecordUnique([new CmsAuthQuery(), 'username', $this->getRecord()->id])));

        //imię i nazwisko użytkownika
        $this->addElement((new Element\Text('name'))
            ->setLabel('form.auth.name.label')
            ->addFilter(new Filter\StringTrim()));

        //email
        $this->addElement((new Element\Text('email'))
            ->setLabel('form.auth.email.label')
            ->setRequired()
            ->addFilter(new Filter\StringTrim())
            ->addValidator(new Validator\EmailAddress())
            ->addValidator(new Validator\RecordUnique([new CmsAuthQuery(), 'email', $this->getRecord()->id])));

        //role
        $this->addElement((new Element\MultiCheckbox('cmsRoles'))
            ->setLabel('form.auth.cmsRoles.label')
            ->setDescription('form.auth.cmsRoles.description')
            ->setMultioptions($this->getRolesMultioptions())
            ->setValue($this->getRolesValue())
            ->addValidator(new Validator\NotEmpty(['form.auth.cmsRoles.validator'])));

        //aktywny
        $this->addElement((new Element\Checkbox('active'))
            ->setLabel('form.auth.active.label'));

        //zmiana hasła
        $this->addElement((new Element\Text('changePassword'))
            ->setLabel('form.auth.changePassword.label')
            ->setDescription('form.auth.changePassword.description')
            ->addValidator(new Validator\StringLength([4, 128])));

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('form.auth.submit.label'));
    }

    /**
     * Przed zapisem - kalkulacja hasha hasła
     * @return boolean
     */
    public function beforeSave()
    {
        $this->getRecord()->roles = implode(',', $this->getElement('cmsRoles')->getValue());
        $changedPassword = $this->getElement('changePassword')->getValue();
        if ('' == $changedPassword) {
            return true;
        }
        $this->getRecord()->password = App::$di->get(AuthProviderInterface::class)->getSaltedPasswordHash($changedPassword);
        return true;
    }

    /**
     * Po zapisie nadawanie uprawnień
     * @return boolean
     */
    public function afterSave()
    {
        //usunięcie języka z sesji
        $session = new \Mmi\Session\SessionSpace('cms-language');
        $session->unsetAll();
        return true;
    }

    private function getRolesMultioptions(): array
    {
        $multioptions = [];
        foreach (App::$di->get(AclInterface::class)->getRoles() as $roleName) {
            $multioptions[$roleName] = $roleName;
        }
        return $multioptions;
    }

    private function getRolesValue(): array
    {
        return explode(',', (string) $this->getRecord()->roles);
    }
}
