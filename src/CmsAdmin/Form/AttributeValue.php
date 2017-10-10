<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Form\Element,
    Mmi\Validator,
    Mmi\Filter;

/**
 * Formularz wartości atrybutu
 */
class AttributeValue extends \Cms\Form\Form
{

    public function init()
    {

        //wartość
        $this->addElement((new Element\Text('value'))
            ->setLabel('wartość')
            ->setRequired()
            ->addFilter(new Filter\StringTrim)
            ->addValidator(new Validator\StringLength([1, 1024])));

        //labelka
        $this->addElement((new Element\Text('label'))
            ->setLabel('etykieta')
            ->addFilter(new Filter\StringTrim)
            ->addFilter(new Filter\EmptyToNull)
            ->addValidator(new Validator\StringLength([1, 64])));

        //zapis
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('zapisz wartość'));
    }

    /**
     * Przed zapisem
     * @return boolean
     */
    public function beforeSave()
    {
        //labelka jest podana - nic do zrobioenia
        if ($this->getElement('label')->getValue()) {
            return true;
        }
        //podstawianie wartości za labelkę
        $this->getRecord()->label = mb_substr($this->getElement('value')->getValue(), 0, 64);
        return true;
    }

}
