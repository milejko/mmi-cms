<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Contact;

class Option extends \Mmi\Form\Form
{

    public function init()
    {

        $this->addElementText('name')
            ->setLabel('nazwa');

        $this->addElementText('sendTo')
            ->setLabel('prześlij na email')
            ->setDescription('Wysyła kopię wiadomości od użytkownika bezpośrednio na podane adres\'y e-mail oddzielone ";"')
            ->addValidatorEmailAddressList();

        $this->addElementSubmit('submit')
            ->setLabel('dodaj/zmień temat');
    }

}
