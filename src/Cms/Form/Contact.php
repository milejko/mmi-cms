<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form;

use Mmi\App\App;
use Mmi\Form\Element,
    Mmi\Validator,
    Mmi\Filter;
use Mmi\Security\Auth;

class Contact extends \Mmi\Form\Form
{

    public function init()
    {

        if (!$this->getOption('subjectId')) {
            $this->addElement((new Element\Select('cmsContactOptionId'))
                ->setLabel('form.contact.cmsContactOptionId.label')
                ->setMultioptions(\Cms\Model\Contact::getMultioptions())
                ->addValidator(new Validator\Integer));
        }

        $auth = App::$di->get(Auth::class);

        $this->addElement((new Element\Text('email'))
            ->setLabel('form.contact.email.label')
            ->setValue($auth->getEmail())
            ->setRequired()
            ->addValidator(new Validator\EmailAddress));

        $this->addElement((new Element\Textarea('text'))
            ->setLabel('form.contact.text.label')
            ->setRequired()
            ->addValidator(new Validator\NotEmpty)
            ->addFilter(new Filter\StripTags));

        //captcha dla niezalogowanych
        if (!($auth->getId() > 0)) {
            $this->addElement((new Element\Captcha('regCaptcha'))
                ->setLabel('form.contact.regCaptcha.label'));
        }

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('form.contact.submit.label'));
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
