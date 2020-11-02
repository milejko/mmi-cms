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
use Cms\Model\CacheOptions;
use Cms\Model\SkinsetModel;
use Cms\Orm\CmsCategoryRecord;

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
            ->setMultioptions([null => 'form.category.cmsCategoryTypeId.default'] + (new SkinsetModel(Registry::$config->skinset))->getTemplatesMultioptions()));

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
        
        //https
        $this->addElement((new Element\Select('https'))
            ->setOption(self::TAB_KEY, self::SECTION_ADVANCED)
            ->setMultioptions([null => 'form.category.https.option.default', '0' => 'form.category.https.option.nossl', 1 => 'form.category.https.option.ssl'])
            ->addFilter(new Filter\EmptyToNull)
            ->setLabel('form.category.https.label'));

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

        //przekierowanie na moduł
        $this->addElement((new Element\Text('mvcParams'))
            ->setOption(self::TAB_KEY, self::SECTION_ADVANCED)
            ->setLabel('form.category.mvcParams.label')
            ->setDescription('form.category.mvcParams.description')
            ->addFilter(new Filter\StringTrim)
            ->addValidator(new Validator\Regex(['@module\=[a-zA-Z0-9\&\=]+@', 'form.category.mvcParams.validator'])));

        //role uprawnione do wyświetlenia kategorii/strony
        $this->addElement((new Element\MultiCheckbox('roles'))
            ->setOption(self::TAB_KEY, self::SECTION_ADVANCED)
            ->setLabel('form.category.roles.label')
            ->setMultioptions((new \Cms\Orm\CmsRoleQuery)->orderAscName()->findPairs('id', 'name'))
            ->setValue(
                $this->getRecord()->id ? (new \Cms\Orm\CmsCategoryRoleQuery)
                    ->whereCmsCategoryId()->equals($this->getRecord()->id)
                    ->findPairs('cms_role_id', 'cms_role_id') : []
            ));

        //początek publikacji
        $this->addElement((new Element\DateTimePicker('dateStart'))
            ->setOption(self::TAB_KEY, self::SECTION_ADVANCED)
            ->setLabel('form.category.dateStart.label')
            ->setDateMin(date('Y-m-d H:i')));
    
        //zakończenie publikacji
        $this->addElement((new Element\DateTimePicker('dateEnd'))
            ->setOption(self::TAB_KEY, self::SECTION_ADVANCED)
            ->setLabel('form.category.dateEnd.label')
            ->setDateMin(date('Y-m-d H:i'))
            ->setDateMinField($this->getElement('dateStart')));

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
        //jeśli nie udało się zapisać powiązań z rolami
        if (!$this->_saveRoles()) {
            return false;
        }
        //commit wersji
        if ($this->getElement('commit')->getValue()) {
            $this->getRecord()->commitVersion();
        }
        //usunięcie locka
        return (new CategoryLockModel($this->getRecord()->cmsCategoryOriginalId))->releaseLock();
    }

    /**
     * Zapisuje powiązania kategorii z rolami
     * @return bool
     */
    protected function _saveRoles()
    {
        //role zaznaczone w formularzu
        $formRoles = $this->getElement('roles')->getValue();
        //role zapisane w bazie
        $savedRoles = (new \Cms\Orm\CmsCategoryRoleQuery)
            ->whereCmsCategoryId()->equals($this->getRecord()->getPk())
            ->findPairs('cms_role_id', 'cms_role_id');
        //usuwanie zbędnych
        if (!$this->_deleteRoles(array_diff($savedRoles, $formRoles))) {
            return false;
        }
        //wstawianie brakujących
        if (!$this->_insertRoles(array_diff($formRoles, $savedRoles))) {
            return false;
        }
        return true;
    }

    /**
     * Usuwa zbędne powiązania kategorii z rolami
     * @param array $delete
     * @return bool
     */
    protected function _deleteRoles(array $delete = [])
    {
        if (empty($delete)) {
            return true;
        }
        return count($delete) === (new \Cms\Orm\CmsCategoryRoleQuery)
            ->whereCmsCategoryId()->equals($this->getRecord()->getPk())
            ->andFieldCmsRoleId()->equals($delete)
            ->find()->delete();
    }

    /**
     * Wstawia brakujące powiązania kategorii z rolami
     * @param array $insert
     * @return bool
     */
    protected function _insertRoles(array $insert = [])
    {
        foreach ($insert as $roleId) {
            $record = new \Cms\Orm\CmsCategoryRoleRecord();
            $record->cmsCategoryId = $this->getRecord()->getPk();
            $record->cmsRoleId = $roleId;
            if (!$record->save()) {
                return false;
            }
        }
        return true;
    }
}
