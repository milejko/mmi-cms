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

class Option extends \Cms\Form\Form
{

    public function init()
    {

        $this->addElement((new Element\Text('name'))
            ->setLabel('nazwa'));

        $this->addElement((new Element\Text('sendTo'))
            ->setLabel('prześlij na email')
            ->setDescription('Wysyła kopię wiadomości od użytkownika bezpośrednio na podane adres\'y e-mail oddzielone ");"')
            ->addValidator(new \Mmi\Validator\EmailAddressList([])));

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('dodaj/zmień temat'));
    }

}
