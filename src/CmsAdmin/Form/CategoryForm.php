<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\App\CmsSkinsetConfig;
use Cms\Form\Element;
use Cms\Form\Form;
use Mmi\Validator;
use Mmi\Filter;
use Cms\Model\CacheOptions;
use Cms\Model\SkinsetModel;
use Cms\Orm\CmsCategoryRecord;
use Mmi\App\App;

/**
 * Formularz edycji szegółów kategorii
 * @method \Cms\Orm\CmsCategoryRecord getRecord()
 */
class CategoryForm extends Form
{
    //klucz określający tab
    const TAB_KEY = 'tab';
    //klucze sekcji
    const SECTION_BASIC = 'basic';
    const SECTION_ADVANCED = 'advanced';
    const SECTION_INVISIBLE = 'not-visible';

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
            ->setOption('enctype', 'multipart/form-data');

        //opcje przekazywane z konstruktora
        $this->setOptions($options);

        //inicjalizacja formularza
        $this->init();

        //dane z rekordu
        $this->hasNotEmptyRecord() && $this->setFromRecord($this->_record);
    }

    public function init()
    {
        //szablony/typy (jeśli istnieją)
        $this->addElement((new Element\Select('template'))
            ->setOption(self::TAB_KEY, self::SECTION_BASIC)
            ->setLabel('form.category.cmsCategoryTypeId.label')
            ->addFilter(new Filter\EmptyToNull)
            ->setMultioptions([null => 'form.category.cmsCategoryTypeId.default'] + (new SkinsetModel(App::$di->get(CmsSkinsetConfig::class)))->getTemplatesMultioptions()));

        //nazwa kategorii
        $this->addElement((new Element\Text('name'))
            ->setOption(self::TAB_KEY, self::SECTION_BASIC)
            ->setLabel('form.category.name.label')
            ->setRequired()
            ->addFilter(new Filter\StringTrim)
            ->addValidator(new Validator\StringLength([0, 128])));

        //aktywna
        $this->addElement((new Element\Checkbox('active'))
            ->setOption(self::TAB_KEY, self::SECTION_BASIC)
            ->setChecked()
            ->setLabel('form.category.active.label'));

        //SEO
        //nazwa kategorii
        $this->addElement((new Element\Text('title'))
            ->setOption(self::TAB_KEY, self::SECTION_BASIC)
            ->setLabel('form.category.title.label')
            ->setDescription('form.category.title.description')
            ->addFilter(new Filter\StringTrim)
            ->addValidator(new Validator\StringLength([2, 128])));

        //meta description
        $this->addElement((new Element\Textarea('description'))
            ->setOption(self::TAB_KEY, self::SECTION_BASIC)
            ->setLabel('form.category.description.label'));

        //własny uri
        $this->addElement((new Element\Text('customUri'))
            ->setOption(self::TAB_KEY, self::SECTION_ADVANCED)
            ->setLabel('form.category.customUri.label')
            //adres domyślny (bez baseUrl)
            ->addFilter(new Filter\StringTrim)
            ->addFilter(new Filter\EmptyToNull)
            ->addValidator(new Validator\StringLength([1, 255])));

        //follow
        $this->addElement((new Element\Checkbox('follow'))
            ->setOption(self::TAB_KEY, self::SECTION_ADVANCED)
            ->setChecked()
            ->setLabel('form.category.visible.label'));
        
        //blank
            $this->addElement((new Element\Checkbox('blank'))
            ->setOption(self::TAB_KEY, self::SECTION_ADVANCED)
            ->setLabel('form.category.blank.label'));
        
        //Zaawansowane
        //przekierowanie na link
        $this->addElement((new Element\Text('redirectUri'))
            ->setOption(self::TAB_KEY, self::SECTION_ADVANCED)
            ->setLabel('form.category.redirect.label')
            ->addFilter(new Filter\StringTrim));

        //ustawienie bufora
        $this->addElement((new Element\Select('cacheLifetime'))
            ->setOption(self::TAB_KEY, self::SECTION_ADVANCED)
            ->setLabel('form.category.cacheLifetime.label')
            ->setMultioptions([null => 'form.category.cacheLifetime.default'] + CacheOptions::LIFETIMES)
            ->addFilter(new Filter\EmptyStringToNull));

        //zapis
        $this->addElement((new Element\Submit('commit'))
            ->setOption(self::TAB_KEY, self::SECTION_INVISIBLE)
            ->setLabel('template.category.edit.commit'));

        //zapis
        $this->addElement((new Element\Submit('submit'))
            ->setOption(self::TAB_KEY, self::SECTION_INVISIBLE)
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
