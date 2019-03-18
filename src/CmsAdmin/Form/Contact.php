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
    Mmi\Validator;

/**
 * Formularz odpowiedzi na kontakt
 * @method \Cms\Orm\CmsContactRecord getRecord()
 */
class Contact extends \Cms\Form\Form
{

    public function init()
    {

        //identyfikator tematu
        if (!$this->getOption('subjectId')) {
            $this->addElement((new Element\Select('cmsContactOptionId'))
                ->setDisabled()
                ->setIgnore()
                ->setValue($this->getOption('subjectId'))
                ->setMultioptions(\Cms\Model\Contact::getMultioptions())
                ->setLabel('form.contact.cmsContactOptionId.label'));
        }

        //mail
        $this->addElement((new Element\Text('email'))
            ->setDisabled()
            ->setLabel('form.contact.email.label')
            ->setValue(\App\Registry::$auth->getEmail())
            ->addValidator(new Validator\EmailAddress));

        //tresc zapytania
        $this->addElement((new Element\Textarea('text'))
            ->setDisabled()
            ->setLabel('form.contact.text.label'));

        //odpowiedz na zgloszenie
        $this->addElement((new Element\Textarea('reply'))
            ->setRequired()
            ->addValidator(new Validator\NotEmpty)
            ->setLabel('form.contact.reply.label'));

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('form.contact.submit.label'));
    }

    /**
     * Ustawienie opcji przed zapisem
     * @return boolean
     */
    public function beforeSave()
    {
        $this->getRecord()->active = 0;
        $this->getRecord()->cmsAuthIdReply = \App\Registry::$auth->getId();
        return true;
    }

    /**
     * Po zapisie wysyłka maila
     * @return boolean
     */
    public function afterSave()
    {
        \Cms\Model\Mail::pushEmail('contact_reply', $this->getRecord()->email, [
            'id' => $this->getRecord()->id,
            'text' => $this->getRecord()->text,
            'replyText' => $this->getElement('reply')->getValue()
        ]);
        return true;
    }

}
