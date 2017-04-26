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
 * Formularz wiązania szablon <-> atrybut
 */
class CategoryAttributeRelationForm extends \Cms\Form\Form
{

    public function init()
    {

        //atrybut
        $this->addElementSelect('cmsAttributeId')
            ->setRequired()
            ->addValidatorNotEmpty()
            ->setMultioptions([null => '---'] + (new \Cms\Orm\CmsAttributeQuery)
                ->orderAscName()
                ->findPairs('id', 'name'))
            //unikalność atrybutu dla wybranego szablonu
            ->addValidatorRecordUnique((new \Cms\Orm\CmsAttributeRelationQuery)
                ->whereObject()->equals($this->getRecord()->object)
                ->andFieldObjectId()->equals($this->getRecord()->objectId)
                , 'cmsAttributeId', $this->getRecord()->id)
            ->setLabel('atrybut');

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
        $this->addElementText('defaultValue')
            ->setLabel('wartość domyślna')
            ->addFilterEmptyToNull()
            //string odpowiadający wartości domyślnej
            ->setValue($defaultValueRecord ? $defaultValueRecord->value : null);

        //filtry
        $this->addElementText('filterClasses')
            ->setLabel('filtry');

        //walidatory
        $this->addElementText('validatorClasses')
            ->setLabel('walidatory');

        //wymagany
        $this->addElementCheckbox('required')
            ->setLabel('wymagany');

        //unikalny
        $this->addElementCheckbox('unique')
            ->setLabel('unikalny');

        //zmaterializowany
        $this->addElementSelect('materialized')
            ->setMultioptions([0 => 'nie', 1 => 'tak', 2 => 'tak, odziedziczony'])
            ->setLabel('zmaterializowany')
            ->setDescription('opcja administracyjna, zmiana może uszkodzić formularze zawierające ten atrybut');

        //kolejność
        $this->addElementText('order')
            ->setRequired()
            ->setLabel('kolejność')
            ->addValidatorNumberBetween(0, 10000000)
            ->setValue(0);

        //zapis
        $this->addElementSubmit('submit')
            ->setLabel('zapisz wiązanie');
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
