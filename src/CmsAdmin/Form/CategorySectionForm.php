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
use Cms\Orm\CmsCategoryWidgetQuery;
use Cms\Orm\CmsCategoryWidgetSectionQuery;
use Cms\Orm\CmsCategoryWidgetSectionRecord;
use Mmi\Validator\StringLength;

/**
 * Formularz wiązania szablon <-> sekcja
 */
class CategorySectionForm extends \Cms\Form\Form
{

    public function init()
    {
        //nazwa
        $this->addElement((new Element\Text('name'))
            ->setRequired()
            ->addValidator(new StringLength([1,128]))
            ->setLabel('form.categorySection.name.label'));

        //klucz
        $this->addElement((new Element\Text('key'))
            ->setRequired()
            ->addValidator(new StringLength([1,64]))
            ->setLabel('form.categorySection.key.label')
            ->setDescription('form.categorySection.key.description'));

        //wymagane dodanie 1 widgeta
        $this->addElement((new Element\Checkbox('required'))
                ->setLabel('form.categorySection.required.label'));

        //kolejność
        $this->addElement((new Element\Text('order'))
                ->setRequired()
                ->setLabel('form.categorySection.order.label')
                ->addValidator(new Validator\NumberBetween([0, 10000000]))
                ->setValue(0));

        //kompatybilne widgety
        $this->addElement((new Element\MultiCheckbox('widgetIds'))
            ->setMultioptions((new CmsCategoryWidgetQuery())->findPairs('id', 'name'))
            ->setValue((new CmsCategoryWidgetSectionQuery())->whereCmsCategorySectionId()->equals($this->getRecord()->id)->findPairs('cms_category_widget_id', 'cms_category_widget_id'))
            ->setLabel('form.categorySection.widgetIds.label'));

        //zapis
        $this->addElement((new Element\Submit('submit'))
                ->setLabel('form.categorySection.submit.label'));
    }

    /**
     * Zapis kompatybilnych widgetów po zapisie sekcji
     * @return boolean
     */
    public function afterSave()
    {
        //usunięcie kompatybilnych widgetów
        (new CmsCategoryWidgetSectionQuery())
            ->whereCmsCategorySectionId()->equals($this->getRecord()->id)
            ->find()
            ->delete();
        //zapis kompatybilnych widgetów
        foreach ($this->getElement('widgetIds')->getValue() as $widgetId) {
            $widgetSectionRecord = new CmsCategoryWidgetSectionRecord();
            $widgetSectionRecord->cmsCategoryWidgetId = $widgetId;
            $widgetSectionRecord->cmsCategorySectionId = $this->getRecord()->id;
            $widgetSectionRecord->save();
        }
        //parent
        return parent::afterSave();
    }

}
