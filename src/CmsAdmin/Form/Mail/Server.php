<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Mail;

use Cms\Form\Element;

class Server extends \Cms\Form\Form
{

    public function init()
    {

        $this->addElement((new Element\Text('address'))
            ->setLabel('Adres serwera SMTP'));

        $this->addElement((new Element\Select('ssl'))
            ->setLabel('Rodzaj połączenia')
            ->setRequired()
            ->addValidatorNotEmpty()
            ->setMultioptions(['plain' => 'plain', 'tls' => 'tls', 'ssl' => 'ssl']));

        $this->addElement((new Element\Text('port'))
            ->setLabel('Port')
            ->setRequired()
            ->addValidatorInteger(true)
            ->setValue(25)
            ->setDescription('Plain: 25, SSL: 465, TLS: 587'));

        $this->addElement((new Element\Text('username'))
            ->setLabel('Nazwa użytkownika'));

        $this->addElement((new Element\Text('password'))
            ->setLabel('Hasło użytkownika'));

        $this->addElement((new Element\Text('from'))
            ->setLabel('Domyślny adres od'));

        //submit
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('Zapisz'));
    }

}
