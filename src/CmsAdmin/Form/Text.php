<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

class Text extends \Mmi\Form\Form
{

    public function init()
    {

        $this->addElementText('key')
            ->setLabel('klucz')
            ->setRequired()
            ->addValidatorNotEmpty();

        $this->addElementTextarea('content')
            ->setLabel('zawartość');

        $this->addElementSubmit('submit')
            ->setLabel('zapisz tekst');
    }

}
