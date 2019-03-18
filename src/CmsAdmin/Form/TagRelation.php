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
 * Klasa formularza relacji tagów
 */
class TagRelation extends \Cms\Form\Form
{

    public function init()
    {

        //tag
        $this->addElement((new Element\Text('tag'))
            ->setLabel('form.tagRelation.tag.label')
            ->setRequired()
            ->addFilter(new Filter\StringTrim)
            ->addValidator(new Validator\StringLength([2, 64])));

        //ustawienie wartości tagu
        if ($this->getRecord()->cmsTagId && (null !== $tagRecord = (new \Cms\Orm\CmsTagQuery)->findPk($this->getRecord()->cmsTagId))) {
            $this->getElement('tag')->setValue($tagRecord->tag);
        }

        //obiekt
        $this->addElement((new Element\Text('object'))
            ->setLabel('form.tagRelation.object.label')
            ->setRequired()
            ->addFilter(new Filter\StringTrim)
            ->addValidator(new Validator\StringLength([2, 64])));

        //id obiektu
        $this->addElement((new Element\Text('objectId'))
            ->setLabel('form.tagRelation.objectId.label')
            ->addFilter(new Filter\EmptyToNull)
            ->addValidator(new Validator\Integer([]))
            ->addValidator(new Validator\NumberBetween([0, 100000000])));

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('form.tagRelation.submit.label'));
    }

    /**
     * Przed zapisem odnalezienie identyfikatora wprowadzonego tagu
     * @return boolean
     */
    public function beforeSave()
    {
        $tag = $this->getElement('tag')->getValue();
        //wyszukanie tagu
        if (null === $tagRecord = (new \Cms\Orm\CmsTagQuery)
            ->whereTag()->equals($tag)
            ->findFirst()) {
            //utworzenie tagu
            $tagRecord = new \Cms\Orm\CmsTagRecord;
            $tagRecord->tag = $tag;
            $tagRecord->save();
        }
        //przypisanie id tagu
        $this->getRecord()->cmsTagId = $tagRecord->id;
        return true;
    }

}
