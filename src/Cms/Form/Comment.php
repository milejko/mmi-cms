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

class Comment extends \Mmi\Form\Form
{

    public function init()
    {
        $this->_record->object = $this->getOption('object');
        $this->_record->objectId = $this->getOption('objectId');

        $this->addElement((new Element\Text('title'))
            ->setLabel('tytuł'));

        $this->addElement((new Element\Textarea('text'))
            ->setRequired()
            ->addValidatorNotEmpty()
            ->setLabel('komentarz'));

        if (!\App\Registry::$auth->hasIdentity()) {
            $this->addElement((new Element\Text('signature'))
                ->setLabel('podpis'));
        }

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('dodaj komentarz'));
    }

}
