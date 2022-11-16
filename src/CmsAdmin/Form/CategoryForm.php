<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Api\LinkData;
use Cms\Api\RedirectTransport;
use Cms\Form\Element;
use Cms\Form\Form;
use Cms\Orm\CmsCategoryQuery;
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
            ->addFilter(new Filter\StringTrim())
            ->addValidator(new Validator\StringLength([2, 128])));

        $this->addElement((new Element\Radio('visibility'))
            ->setIgnore()
            ->setValue((int) $this->getRecord()->active + (int) $this->getRecord()->visible)
            ->setMultioptions([
                2 => 'form.category.visibility.on.label',
                1 => 'form.category.visibility.off.label',
                0 => 'form.category.visibility.disabled.label',
            ]));

        //blank
        $this->addElement((new Element\Checkbox('blank'))
            ->setLabel('form.category.blank.label'));

        //przekierowanie na link
        $this->addElement((new Element\Text('redirectUri'))
                ->setLabel('form.category.redirect.label')
                ->addValidator(new Url())
                ->addFilter(new Filter\StringTrim()));
        //tylko jeśli ma template (jest stroną)
        if ($this->getRecord()->template && strpos($this->getRecord()->template, '/')) {
            //SEO
            //meta title
            $this->addElement((new Element\Text('title'))
                ->setOption('tab', 'seo')
                ->setLabel('form.category.title.label')
                ->setDescription('form.category.title.description')
                ->addFilter(new Filter\StringTrim())
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
                ->addFilter(new Filter\Lowercase())
                ->addFilter(new Filter\StringTrim())
                ->addFilter(new Filter\EmptyToNull())
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

    public function beforeSave()
    {
        switch ($this->getElement('visibility')->getValue()) {
            case 0:
                $this->getRecord()->visible = false;
                $this->getRecord()->active = false;
                return;
            case 1:
                $this->getRecord()->visible = false;
                $this->getRecord()->active = true;
                return;
            case 2:
                $this->getRecord()->visible = true;
                $this->getRecord()->active = true;
                return;
        }
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
        //weryfikacja unikalnosci uri
        if ($this->getRecord()->active && (new CmsCategoryQuery())->isSimilarActivePage($this->getRecord()->getUri(), $this->getRecord()->getScope(), $this->getRecord()->cmsCategoryOriginalId)
        ) {
            $this->getElement('visibility')->addError(['form.category.visibility.error', [$this->getRecord()->getUri()]]);
            return false;
        }
        //weryfikacja zapetlenia przekierowania
        $redirectUri = $this->getElement('redirectUri')->getValue();
        if ($redirectUri) {
            $elementRedirectUrl = parse_url((new RedirectTransport($redirectUri))->_links[0]->href, PHP_URL_PATH);
            $currentRedirectUrl = (new RedirectTransport(LinkData::INTERNAL_REDIRECT_PREFIX . $this->getRecord()->id))->_links[0]->href;
            if ($elementRedirectUrl === $currentRedirectUrl) {
                $elementError = LinkData::REL_INTERNAL === $this->getElement('redirectType') && $this->getElement('redirectType')->getValue() ? 'redirectCategoryId' : 'redirectUri';
                $this->getElement($elementError)->addError('form.category.redirectUri.error');
                return false;
            }
        }
        //commit wersji
        if ($this->getElement('commit')->getValue()) {
            $this->getRecord()->commitVersion();
        }
        //usunięcie locka
        return (new CategoryLockModel($this->getRecord()->cmsCategoryOriginalId))->releaseLock();
    }
}
