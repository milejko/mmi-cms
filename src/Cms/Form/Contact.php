<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form;

use Mmi\Form\Element;

class Contact extends \Mmi\Form\Form
{

    public function init()
    {

        if (!$this->getOption('subjectId')) {
            $this->addElement((new Element\Select('cmsContactOptionId'))
                ->setLabel('Wybierz temat')
                ->setMultioptions(\Cms\Model\Contact::getMultioptions())
                ->addValidator(new \Mmi\Validator\Integer));
        }

        $auth = \App\Registry::$auth;

        $this->addElement((new Element\Text('email'))
            ->setLabel('Twój adres email')
            ->setValue($auth->getEmail())
            ->setRequired()
            ->addValidator(new \Mmi\Validator\EmailAddress));

        $this->addElement((new Element\Textarea('text'))
            ->setLabel('Wiadomość')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty)
            ->addFilterStripTags());

        //captcha dla niezalogowanych
        if (!($auth->getId() > 0)) {
            $this->addElement((new Element\Captcha('regCaptcha'))
                ->setLabel('Przepisz kod'));
        }

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('Wyślij'));
    }

    /**
     * Konwersja subjectId na cmsContactOptionId
     * @return boolean
     */
    public function beforeSave()
    {
        if ($this->getOption('subjectId') > 0) {
            $this->getElement('cmsContactOptionId')->setValue($this->getOption('subjectId'));
        }
        return true;
    }

}
