<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Contact;

use Cms\Form\Element;

/**
 * Formularz opcji kontaktu
 */
class Option extends \Cms\Form\Form
{

    /**
     * Konfiguracja formularza
     */
    public function init()
    {

        //nazwa
        $this->addElement((new Element\Text('name'))
            ->setLabel('form.contact.option.name.label'));

        //wyślij wiadomość
        $this->addElement((new Element\Text('sendTo'))
            ->setLabel('form.contact.option.sendTo.label')
            ->setDescription('form.contact.option.sendTo.description')
            ->addValidator(new \Mmi\Validator\EmailAddressList([])));

        //submit
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('form.contact.option.submit.label'));
    }

}
