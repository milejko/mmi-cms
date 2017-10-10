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
 * Formularz edycji szegółów kategorii
 */
class Category extends \Cms\Form\AttributeForm
{

    public function init()
    {

        //szablony/typy (jeśli istnieją)
        if ([] !== $types = (new \Cms\Orm\CmsCategoryTypeQuery)->orderAscName()->findPairs('id', 'name')) {
            $this->addElement((new Element\Select('cmsCategoryTypeId'))
                ->setLabel('szablon strony')
                ->addFilterEmptyToNull()
                ->setMultioptions([null => 'Domyślny'] + $types));
        }

        //nazwa kategorii
        $this->addElement((new Element\Text('name'))
            ->setLabel('nazwa')
            ->setRequired()
            ->addFilterStringTrim()
            ->addValidator(new Validator\StringLength(2, 128)));

        //początek publikacji
        $this->addElement((new Element\DateTimePicker('dateStart'))
            ->setLabel('początek publikacji')
            ->setDateMin(date('Y-m-d H:i')));

        //zakończenie publikacji
        $this->addElement((new Element\DateTimePicker('dateEnd'))
            ->setLabel('zakończenie publikacji')
            ->setDateMin(date('Y-m-d H:i'))
            ->setDateMinField($this->getElement('dateStart')));

        //ustawienie bufora
        $this->addElement((new Element\Select('cacheLifetime'))
            ->setLabel('odświeżanie')
            ->setMultioptions([null => 'domyślne dla szablonu'] + \Cms\Orm\CmsCategoryRecord::CACHE_LIFETIMES)
            ->addFilterEmptyToNull());

        //aktywna
        $this->addElement((new Element\Checkbox('active'))
            ->setChecked()
            ->setLabel('włączona'));

        //zapis
        $this->addElement((new Element\Submit('submit1'))
            ->setLabel('zapisz'));

        //SEO
        //nazwa kategorii
        $this->addElement((new Element\Text('title'))
            ->setLabel('meta tytuł')
            ->setDescription('jeśli brak, użyta zostanie kaskada złożona nazw')
            ->addFilterStringTrim()
            ->addValidator(new Validator\StringLength(2, 128)));

        //meta description
        $this->addElement((new Element\Textarea('description'))
            ->setLabel('meta opis'));

        $view = \Mmi\App\FrontController::getInstance()->getView();

        //własny uri
        $this->addElement((new Element\Text('customUri'))
            ->setLabel('własny adres strony')
            //adres domyślny (bez baseUrl)
            ->setDescription('domyślnie: ' . substr($view->url(['module' => 'cms', 'controller' => 'category', 'action' => 'dispatch', 'uri' => $this->getRecord()->uri], true), strlen($view->baseUrl) + 1))
            ->addFilterStringTrim()
            ->addFilterEmptyToNull()
            ->addValidator(new Validator\RecordUnique(new \Cms\Orm\CmsCategoryQuery, 'uri'))
            ->addValidator(new Validator\RecordUnique(new \Cms\Orm\CmsCategoryQuery, 'customUri', $this->getRecord()->id))
            ->addValidator(new Validator\StringLength(1, 255)));

        //blank
        $this->addElement((new Element\Checkbox('follow'))
            ->setChecked()
            ->setLabel('widoczna dla wyszukiwarek'));

        //zapis
        $this->addElement((new Element\Submit('submit2'))
            ->setLabel('zapisz'));

        //Treść
        //atrybuty
        $this->initAttributes('cmsCategoryType', $this->getRecord()->cmsCategoryTypeId, 'category');

        //jeśli wstawione, dodany button z zapisem
        $this->addElement((new Element\Submit('submit3'))
            ->setLabel('zapisz'));

        //Zaawansowane
        //przekierowanie na link
        $this->addElement((new Element\Text('redirectUri'))
            ->setLabel('przekierowanie na adres')
            ->setDescription('np. http://www.google.pl')
            ->addFilterStringTrim());

        //przekierowanie na moduł
        $this->addElement((new Element\Text('mvcParams'))
            ->setLabel('przekierowanie na moduł CMS')
            ->setDescription('np. module=blog&controller=index&action=index')
            ->addFilterStringTrim()
            ->addValidator(new Validator\Regex('@module\=[a-zA-Z0-9\&\=]+@', 'niepoprawny adres modułu cms')));

        //config JSON
        $this->addElement((new Element\Text('configJson'))
            ->setLabel('dodatkowe flagi')
            ->setDescription('format JSON')
            ->addValidator(new Validator\Json)
            ->addFilterStringTrim());

        //https
        $this->addElement((new Element\Select('https'))
            ->setMultioptions([null => 'bez zmian', '0' => 'wymuś brak https', 1 => 'wymuś https'])
            ->addFilterEmptyToNull()
            ->setLabel('https'));

        //blank
        $this->addElement((new Element\Checkbox('blank'))
            ->setLabel('otwieranie w nowym oknie'));
		
		//role uprawnione do wyświetlenia kategorii/strony
		$this->addElement((new Element\MultiCheckbox('roles'))
			->setLabel('widoczne dla')
			->setMultioptions((new \Cms\Orm\CmsRoleQuery)->orderAscName()->findPairs('id', 'name'))
			->setValue($this->getRecord()->id ? (new \Cms\Orm\CmsCategoryRoleQuery)
				->whereCmsCategoryId()->equals($this->getRecord()->id)
				->findPairs('cms_role_id', 'cms_role_id') : []
			));

        //zapis
        $this->addElement((new Element\Submit('submit4'))
            ->setLabel('zapisz'));
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
        //zapis udany
        return true;
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
				->whereCmsCategoryId()->equals($this->getRecord()->id)
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
				->whereCmsCategoryId()->equals($this->getRecord()->id)
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
			$record->cmsCategoryId = $this->getRecord()->id;
			$record->cmsRoleId = $roleId;
			if (!$record->save()) {
				return false;
			}
		}
		return true;
	}

}
