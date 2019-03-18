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
    Mmi\Validator;

/**
 * Formularz wiązania szablon <-> atrybut
 */
class CategoryAttributeRelationForm extends \Cms\Form\Form
{

    public function init()
    {
        $query = (new \Cms\Orm\CmsAttributeRelationQuery)
                ->whereObject()->equals($this->getRecord()->object)
                ->andFieldObjectId()->equals($this->getRecord()->objectId);

        //atrybut
        $this->addElement((new Element\Select('cmsAttributeId'))
                ->setRequired()
                ->addValidator(new Validator\NotEmpty)
                ->setMultioptions([null => '---'] + (new \Cms\Orm\CmsAttributeQuery)
                    ->orderAscName()
                    ->findPairs('id', 'name'))
                //unikalność atrybutu dla wybranego szablonu
                ->addValidator(new Validator\RecordUnique([$query, 'cmsAttributeId', $this->getRecord()->id]))
                ->setLabel('form.categoryAttributeRelationForm.cmsAttributeId.label'));

        //zablokowana edycja
        if ($this->getRecord()->id) {
            $this->getElement('cmsAttributeId')
                ->setIgnore()
                ->setDisabled();
        }

        //rekord wartości domyślnej
        $defaultValueRecord = (new \Cms\Orm\CmsAttributeValueQuery)
            ->findPk($this->getRecord()->cmsAttributeValueId);

        //wartość domyślna
        $this->addElement((new Element\Text('defaultValue'))
                ->setLabel('form.categoryAttributeRelationForm.defaultValue.label')
                ->addFilter(new \Mmi\Filter\EmptyToNull)
                //string odpowiadający wartości domyślnej
                ->setValue($defaultValueRecord ? $defaultValueRecord->value : null));

        //filtry
        $this->addElement((new Element\Text('filterClasses'))
                ->setLabel('form.categoryAttributeRelationForm.filterClasses.label'));

        //walidatory
        $this->addElement((new Element\Text('validatorClasses'))
                ->setLabel('form.categoryAttributeRelationForm.validatorClasses.label'));

        //wymagany
        $this->addElement((new Element\Checkbox('required'))
                ->setLabel('form.categoryAttributeRelationForm.required.label'));

        //unikalny
        $this->addElement((new Element\Checkbox('unique'))
                ->setLabel('form.categoryAttributeRelationForm.unique.label'));

        //zmaterializowany
        $this->addElement((new Element\Select('materialized'))
                ->setMultioptions([0 => 'form.categoryAttributeRelationForm.materialized.options.0', 1 => 'form.categoryAttributeRelationForm.materialized.options.1', 2 => 'form.categoryAttributeRelationForm.materialized.options.2'])
                ->setLabel('form.categoryAttributeRelationForm.materialized.label')
                ->setDescription('form.categoryAttributeRelationForm.materialized.description'));

        //kolejność
        $this->addElement((new Element\Text('order'))
                ->setRequired()
                ->setLabel('form.categoryAttributeRelationForm.order.label')
                ->addValidator(new Validator\NumberBetween([0, 10000000]))
                ->setValue(0));

        //zapis
        $this->addElement((new Element\Submit('submit'))
                ->setLabel('form.categoryAttributeRelationForm.submit.label'));
    }

    /**
     * Przed zapisem
     * @return boolean
     */
    public function beforeSave()
    {
        //brak domyślnej wartości
        if (null === $defaultValue = $this->getElement('defaultValue')->getValue()) {
            return true;
        }
        //wszukiwanie rekordu z domyślną wartością
        if (null === $record = (new \Cms\Orm\CmsAttributeValueQuery)
            ->whereCmsAttributeId()->equals($this->getRecord()->cmsAttributeId)
            ->whereValue()->equals($defaultValue)
            ->findFirst()) {
            //tworzenie rekordu domyślnej wartości
            $record = new \Cms\Orm\CmsAttributeValueRecord;
            $record->value = $defaultValue;
            $record->cmsAttributeId = $this->getRecord()->cmsAttributeId;
            $record->save();
        }
        //ustawianie domyślnej wartości
        $this->getRecord()->cmsAttributeValueId = $record->id;
        return true;
    }

}
