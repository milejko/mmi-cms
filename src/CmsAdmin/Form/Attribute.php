<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

/**
 * Formularz atrybutów
 */
class Attribute extends \Mmi\Form\Form
{

    public function init()
    {

        //nazwa
        $this->addElementText('name')
            ->setLabel('nazwa')
            ->setRequired()
            ->addFilterStringTrim()
            ->addValidatorStringLength(2, 128);

        //klucz pola
        $this->addElementText('key')
            ->setLabel('klucz')
            ->addFilterAscii()
            ->setRequired()
            ->addValidatorAlnum('klucz może zawierać wyłącznie litery i cyfry')
            ->addValidatorStringLength(2, 64)
            ->addValidatorRecordUnique(new \Cms\Orm\CmsAttributeQuery, 'key', $this->getRecord()->id);

        //opis
        $this->addElementText('description')
            ->setLabel('opis')
            ->addFilterStringTrim();

        //pole formularza
        $this->addElementSelect('cmsAttributeTypeId')
            ->setLabel('pole formularza')
            ->setRequired()
            ->addValidatorNotEmpty()
            ->setMultioptions((new \Cms\Orm\CmsAttributeTypeQuery)
                ->orderAscName()
                ->findPairs('id', 'name'));

        //opcje pola formularz
        $this->addElementTextarea('fieldOptions')
            ->setLabel('opcje pola');

        //zapis
        $this->addElementSubmit('submit')
            ->setLabel('zapisz atrybut');
    }

}
