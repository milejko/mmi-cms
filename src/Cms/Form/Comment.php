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

/**
 * Klasa formularza kontaktu
 */
class Comment extends \Mmi\Form\Form
{

    /**
     * Konfiguracja formularza
     */
    public function init()
    {
        //ustawienie obiektu
        $this->_record->object = $this->getOption('object');
        //ustawianie id obiektu
        $this->_record->objectId = $this->getOption('objectId');

        //tytuł
        $this->addElement((new Element\Text('title'))
            ->setLabel('tytuł'));

        //komentarz
        $this->addElement((new Element\Textarea('text'))
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty)
            ->setLabel('komentarz'));

        //podpis
        if (!\App\Registry::$auth->hasIdentity()) {
            $this->addElement((new Element\Text('signature'))
                ->setLabel('podpis'));
        }

        //submit
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('dodaj komentarz'));
    }

}
