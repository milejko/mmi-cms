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
 * Formularz atrybutów
 */
class Attribute extends \Cms\Form\Form
{

    public function init()
    {

        //nazwa
        $this->addElement((new Element\Text('name'))
            ->setLabel('nazwa')
            ->setRequired()
            ->addFilterStringTrim()
            ->addValidatorStringLength(2, 128));

        //klucz pola
        $this->addElement((new Element\Text('key'))
            ->setLabel('klucz')
            ->addFilterAscii()
            ->setRequired()
            ->addValidatorAlnum('klucz może zawierać wyłącznie litery i cyfry')
            ->addValidatorStringLength(2, 64)
            ->addValidatorRecordUnique(new \Cms\Orm\CmsAttributeQuery, 'key', $this->getRecord()->id));

        //opis
        $this->addElement((new Element\Text('description'))
            ->setLabel('opis')
            ->addFilterStringTrim());

        //pole formularza
        $this->addElement((new Element\Select('cmsAttributeTypeId'))
            ->setLabel('pole formularza')
            ->setRequired()
            ->addValidatorNotEmpty()
            ->setMultioptions((new \Cms\Orm\CmsAttributeTypeQuery)
                ->orderAscName()
                ->findPairs('id', 'name')));

        //opcje pola formularz
        $this->addElement((new Element\Textarea('fieldOptions'))
            ->setLabel('opcje pola'));

        //zapis
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('zapisz atrybut'));
    }

}
