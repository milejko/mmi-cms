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
use Cms\Form\Form;
use Mmi\Validator;
use Mmi\Filter;
use Cms\Orm\CmsCategoryRecord;
use Mmi\Validator\Url;

/**
 * Formularz edycji szegółów kategorii
 * @method \Cms\Orm\CmsCategoryRecord getRecord()
 */
class CategoryForm extends Form
{
    /**
     * Konstruktor
     * @param CmsCategoryRecord $record
     * @param array $options
     */
    public function __construct(CmsCategoryRecord $record = null, array $options = [])
    {
        //podłączenie rekordu
        $this->_record = $record;

        //kalkulacja nazwy bazowej formularza
        $this->_formBaseName = strtolower(str_replace('\\', '-', get_class($this)));

        //domyślne opcje
        $this->setClass($this->_formBaseName . ' vertical')
            ->setOption('accept-charset', 'utf-8')
            ->setMethod('post')
            ->setOption('enctype', 'multipart/form-data')
            ->addTab('default', 'config', 'pencil') //tab domyślny
            ->addTab('seo', 'seo', 'magnifier'); //tab seo

        //opcje przekazywane z konstruktora
        $this->setOptions($options);

        //inicjalizacja formularza
        $this->init();

        //dane z rekordu
        $this->hasNotEmptyRecord() && $this->setFromRecord($this->_record);
    }

    /**
     * Dodawanie zakładki
     */
    public function addTab(string $key, string $label, string $icon): self
    {
        $tabs = is_array($this->getOption('tabs')) ? $this->getOption('tabs') : [];
        $tabs[$key] = ['label' => $label, 'icon' => $icon];
        $this->setOption('tabs', $tabs);
        return $this;
    }

    /**
     * Usuwanie zakładki
     */
    public function removeTab(string $key): self
    {
        $tabs = is_array($this->getOption('tabs')) ? $this->getOption('tabs') : [];
        unset($tabs[$key]);
        $this->setOption('tabs', $tabs);
        return $this;
    }

    public function init()
    {
        //nazwa kategorii
        $this->addElement((new Element\Text('name'))
            ->setLabel('form.category.name.label')
            ->setRequired()
            ->addFilter(new Filter\StringTrim)
            ->addValidator(new Validator\StringLength([0, 128])));

        //aktywna
        $this->addElement((new Element\Checkbox('active'))
            ->setChecked()
            ->setLabel('form.category.active.label'));

        //blank
        $this->addElement((new Element\Checkbox('blank'))
            ->setLabel('form.category.blank.label'));                

        //przekierowanie na link
        $this->addElement((new Element\Text('redirectUri'))
                ->setLabel('form.category.redirect.label')
                ->addValidator(new Url())
                ->addFilter(new Filter\StringTrim));
    
        //tylko jeśli ma template (jest stroną)
        if (strpos($this->getRecord()->template, '/')) {
            //SEO
            //meta title
            $this->addElement((new Element\Text('title'))
                ->setOption('tab', 'seo')
                ->setLabel('form.category.title.label')
                ->setDescription('form.category.title.description')
                ->addFilter(new Filter\StringTrim)
                ->addValidator(new Validator\StringLength([2, 128])));

            //meta description
            $this->addElement((new Element\Textarea('description'))
                ->setOption('tab', 'seo')
                ->setLabel('form.category.description.label'));

            //og image
            $this->addElement((new Element\Image('ogImage'))
                ->setOption('tab', 'seo')
                ->setObject(CmsCategoryRecord::OG_IMAGE_OBJECT)
                ->setLabel('form.category.image.label'));

            //własny uri
            $this->addElement((new Element\Text('customUri'))
                ->setLabel('form.category.customUri.label')
                //adres domyślny (bez baseUrl)
                ->addFilter(new Filter\StringTrim)
                ->addFilter(new Filter\EmptyToNull)
                ->addValidator(new Validator\StringLength([1, 255])));
        }

        //zapis
        $this->addElement((new Element\Submit('commit'))
            ->setLabel('template.category.edit.commit'));

        //podgląd
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('template.category.edit.preview'));
    }

    /**
     * Walidator sprawdzający możliwość zablokowania kategorii na czas edycji
     * @return type
     */
    public function validator()
    {
        //wynik założenia blokady zapisu
        return (new CategoryLockModel($this->getRecord()->cmsCategoryOriginalId))->lock();
    }

    /**
     * Zapisuje dodatkowe dane, m.in. role
     * @return bool
     */
    public function afterSave()
    {
        //jeśli czegoś nie uddało się zapisać wcześniej
        if (!parent::afterSave()) {
            return false;
        }
        //commit wersji
        if ($this->getElement('commit')->getValue()) {
            $this->getRecord()->commitVersion();
        }
        //usunięcie locka
        return (new CategoryLockModel($this->getRecord()->cmsCategoryOriginalId))->releaseLock();
    }
}
