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

/**
 * Formularz tekstów stałych
 */
class Text extends \Mmi\Form\Form
{
    
    /**
     * Konfiguracja formularza
     */
    public function init()
    {
        //klucz
        $this->addElement((new Element\Text('key'))
                ->setLabel('klucz')
                ->setRequired()
                ->addValidator(new \Mmi\Validator\NotEmpty));

        //zawartość
        $this->addElement((new Element\Textarea('content'))
                ->setLabel('zawartość'));

        //submit
        $this->addElement((new Element\Submit('submit'))
                ->setLabel('zapisz tekst'));
    }

}
