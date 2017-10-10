<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Form\Element;

class Text extends \Mmi\Form\Form
{

    public function init()
    {

        $this->addElement((new Element\Text('key'))
            ->setLabel('klucz')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty()));

        $this->addElement((new Element\Textarea('content'))
            ->setLabel('zawartość'));

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('zapisz tekst'));
    }

}
