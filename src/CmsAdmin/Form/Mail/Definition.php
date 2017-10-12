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
            ->addValidator(new \Mmi\Validator\StringLength([6, 25]))
            ->addValidator(new \Mmi\Validator\RecordUnique([(new \Cms\Orm\CmsMailDefinitionQuery), 'name', $this->getRecord()->id])));

        //wybór połączenia
        $this->addElement((new Element\Select('cmsMailServerId'))
            ->setLabel('połącznie')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty)
            ->setMultioptions(\Cms\Model\Mail::getMultioptions()));

        //temat
        $this->addElement((new Element\Text('subject'))
            ->setLabel('Tytuł')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\StringLength([2, 240])));

        //treść
        $this->addElement((new Element\Textarea('message'))
            ->setLabel('treść')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty));

        //treść html
        $this->addElement((new Element\Checkbox('html'))
            ->setLabel('treść HTML')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty));

        //od
        $this->addElement((new Element\Text('fromName'))
            ->setLabel('wyświetlana nazwa (od kogo)')
            ->setDescription('np. Pomoc serwisu xyz.pl')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\StringLength([2, 240])));

        //odpowiedz na
        $this->addElement((new Element\Text('replyTo'))
            ->setLabel('odpowiedz na')
            ->setDescription('jeśli inny niż z którego wysłano wiadomość')
            ->setRequired(false)
            ->addValidator(new \Mmi\Validator\StringLength([2, 240])));

        //aktywny
        $this->addElement((new Element\Checkbox('active'))
            ->setLabel('aktywny')
            ->setChecked()
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty));

        //submit
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('zapisz mail')
            ->setIgnore());
    }

}
