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
 * Formularz edycji widgetu kategorii
 */
class CategoryWidget extends \Cms\Form\Form
{

    public function init()
    {

        //lista widgetów
        $widgets = [null => '---'] + \CmsAdmin\Model\Reflection::getOptionsWildcard(3, '/widget/');

        //nazwa
        $this->addElement((new Element\Text('name'))
            ->setLabel('nazwa')
            ->setRequired()
            ->addValidator(new Validator\StringLength(3, 64)));

        //parametry wyświetlania
        $this->addElement((new Element\Select('mvcParams'))
            ->setLabel('adres modułu wyświetlania')
            ->setMultioptions($widgets)
            ->setRequired()
            ->addValidator(new Validator\NotEmpty()));

        //parametry podglądu
        $this->addElement((new Element\Select('mvcPreviewParams'))
            ->setLabel('adres modułu podglądu')
            ->setMultioptions($widgets)
            ->setRequired()
            ->addValidator(new Validator\NotEmpty()));

        //klasa formularza (brak - domyślna)
        $this->addElement((new Element\Text('formClass'))
            ->setLabel('klasa formularza')
            ->setDescription('dane i konfiguracja')
            ->addFilterEmptyToNull()
            ->addValidator(new Validator\StringLength(3, 64)));

        //ustawienie bufora
        $this->addElement((new Element\Select('cacheLifetime'))
            ->setLabel('odświeżanie')
            ->setMultioptions(\Cms\Orm\CmsCategoryWidgetRecord::CACHE_LIFETIMES)
            ->setValue(\Cms\Orm\CmsCategoryWidgetRecord::DEFAULT_CACHE_LIFETIME)
            ->addFilterEmptyToNull());

        //zapis
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('zapisz widget'));
    }

}
