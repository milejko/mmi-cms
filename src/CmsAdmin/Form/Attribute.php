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
 * Formularz atrybutów
 */
class Attribute extends \Cms\Form\Form
{

    public function init()
    {

        //nazwa
        $this->addElement((new Element\Text('name'))
            ->setLabel('form.attribute.name.label')
            ->setRequired()
            ->addFilter(new Filter\StringTrim)
            ->addValidator(new Validator\StringLength([2, 128])));

        //klucz pola
        $this->addElement((new Element\Text('key'))
            ->setLabel('form.attribute.key.label')
            ->addFilter(new Filter\Ascii([]))
            ->setRequired()
            ->addValidator((new Validator\Alnum)->setMessage('form.attribute.key.validator'))
            ->addValidator(new Validator\StringLength([2, 64]))
            ->addValidator(new Validator\RecordUnique([new \Cms\Orm\CmsAttributeQuery, 'key', $this->getRecord()->id])));

        //opis
        $this->addElement((new Element\Text('description'))
            ->setLabel('form.attribute.description.label')
            ->addFilter(new Filter\StringTrim));

        //pole formularza
        $this->addElement((new Element\Select('cmsAttributeTypeId'))
            ->setLabel('form.attribute.cmsAttributeTypeId.label')
            ->setRequired()
            ->addValidator(new Validator\NotEmpty)
            ->setMultioptions((new \Cms\Orm\CmsAttributeTypeQuery)
                ->orderAscName()
                ->findPairs('id', 'name')));

        //opcje pola formularz
        $this->addElement((new Element\Textarea('fieldOptions'))
            ->setLabel('form.attribute.fieldOptions.label'));

        //zapis
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('form.attribute.submit.label'));
    }

}
