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

/**
 * Formularz serwerów mailowych
 */
class Server extends \Cms\Form\Form
{

    /**
     * Konfiguracja formularza
     */
    public function init()
    {

        //adres
        $this->addElement((new Element\Text('address'))
            ->setLabel('form.mail.server.address.label'));

        //ssl
        $this->addElement((new Element\Select('ssl'))
            ->setLabel('form.mail.server.ssl.label')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty)
            ->setMultioptions(['plain' => 'plain', 'tls' => 'tls', 'ssl' => 'ssl']));

        //port
        $this->addElement((new Element\Text('port'))
            ->setLabel('form.mail.server.port.label')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\Integer([true]))
            ->setValue(25)
            ->setDescription('Plain: 25, SSL: 465, TLS: 587'));

        //użytkownik
        $this->addElement((new Element\Text('username'))
            ->setLabel('form.mail.server.username.label'));

        //hasło
        $this->addElement((new Element\Text('password'))
            ->setLabel('form.mail.server.password.label'));

        //od
        $this->addElement((new Element\Text('from'))
            ->setLabel('form.mail.server.from.label'));

        //submit
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('form.mail.server.submit.label'));
    }

}
