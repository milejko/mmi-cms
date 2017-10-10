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
 * Klasa formularza szablonów maili
 */
class Definition extends \Cms\Form\Form
{

    public function init()
    {

        //nazwa
        $this->addElement((new Element\Text('name'))
            ->setLabel('unikalna nazwa')
            ->setRequired()
            ->addValidatorStringLength(6, 25)
            ->addValidatorRecordUnique((new \Cms\Orm\CmsMailDefinitionQuery), 'name', $this->getRecord()->id));

        //wybór połączenia
        $this->addElement((new Element\Select('cmsMailServerId'))
            ->setLabel('połącznie')
            ->setRequired()
            ->addValidatorNotEmpty()
            ->setMultioptions(\Cms\Model\Mail::getMultioptions()));

        //temat
        $this->addElement((new Element\Text('subject'))
            ->setLabel('Tytuł')
            ->setRequired()
            ->addValidatorStringLength(2, 240));

        //treść
        $this->addElement((new Element\Textarea('message'))
            ->setLabel('treść')
            ->setRequired()
            ->addValidatorNotEmpty());

        //treść html
        $this->addElement((new Element\Checkbox('html'))
            ->setLabel('treść HTML')
            ->setRequired()
            ->addValidatorNotEmpty());

        //od
        $this->addElement((new Element\Text('fromName'))
            ->setLabel('wyświetlana nazwa (od kogo)')
            ->setDescription('np. Pomoc serwisu xyz.pl')
            ->setRequired()
            ->addValidatorStringLength(2, 240));

        //odpowiedz na
        $this->addElement((new Element\Text('replyTo'))
            ->setLabel('odpowiedz na')
            ->setDescription('jeśli inny niż z którego wysłano wiadomość')
            ->setRequired(false)
            ->addValidatorStringLength(2, 240));

        //aktywny
        $this->addElement((new Element\Checkbox('active'))
            ->setLabel('aktywny')
            ->setChecked()
            ->setRequired()
            ->addValidatorNotEmpty());

        //submit
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('zapisz mail')
            ->setIgnore());
    }

}
