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

/**
 * Formularz odpowiedzi na kontakt
 * @method \Cms\Orm\CmsContactRecord getRecord()
 */
class Contact extends \Mmi\Form\Form
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
                ->setLabel('temat zapytania'));
        }

        //mail
        $this->addElement((new Element\Text('email'))
            ->setDisabled()
            ->setLabel('email')
            ->setValue(\App\Registry::$auth->getEmail())
            ->addValidatorEmailAddress());

        //tresc zapytania
        $this->addElement((new Element\Textarea('text'))
            ->setDisabled()
            ->setLabel('treść zapytania'));

        //odpowiedz na zgloszenie
        $this->addElement((new Element\Textarea('reply'))
            ->setRequired()
            ->addValidatorNotEmpty()
            ->setLabel('odpowiedź'));

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('odpowiedz'));
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
