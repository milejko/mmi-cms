<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Stat;

use Cms\Form\Element;

/**
 * Formularz nazw statystyk
 */
class Label extends \Cms\Form\Form
{

    /**
     * Konfiguracja formularza
     */
    public function init()
    {

        //klucz
        $this->addElement((new Element\Select('object'))
            ->setLabel('klucz')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty)
            ->setMultioptions(\Cms\Model\Stat::getUniqueObjects()));

        //nazwa
        $this->addElement((new Element\Text('label'))
            ->setLabel('nazwa statystyki')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty));

        //opis
        $this->addElement((new Element\Textarea('description'))
            ->setLabel('opis'));

        //submit
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('zapisz'));
    }

}
