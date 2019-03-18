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
            ->setLabel('form.mail.definition.name.label')
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
            ->setLabel('form.mail.definition.subject.label')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\StringLength([2, 240])));

        //treść
        $this->addElement((new Element\Textarea('message'))
            ->setLabel('form.mail.definition.message.label')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty));

        //treść html
        $this->addElement((new Element\Checkbox('html'))
            ->setLabel('form.mail.definition.html.label')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty));

        //od
        $this->addElement((new Element\Text('fromName'))
            ->setLabel('form.mail.definition.fromName.label')
            ->setDescription('form.mail.definition.fromName.description')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\StringLength([2, 240])));

        //odpowiedz na
        $this->addElement((new Element\Text('replyTo'))
            ->setLabel('form.mail.definition.replyTo.label')
            ->setDescription('form.mail.definition.replyTo.description')
            ->setRequired(false)
            ->addValidator(new \Mmi\Validator\StringLength([2, 240])));

        //aktywny
        $this->addElement((new Element\Checkbox('active'))
            ->setLabel('form.mail.definition.active.label')
            ->setChecked()
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty));

        //submit
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('form.mail.definition.submit.label')
            ->setIgnore());
    }

}
