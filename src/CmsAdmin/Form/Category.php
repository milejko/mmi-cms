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
 * Formularz edycji szegółów kategorii
 */
class Category extends \Cms\Form\AttributeForm {

	public function init() {

		//Konfiguracja
		//szablony/typy (jeśli istnieją)
		if ([] !== $types = (new \Cms\Orm\CmsCategoryTypeQuery)->orderAscName()->findPairs('id', 'name')) {
			$this->addElementSelect('cmsCategoryTypeId')
				->setLabel('szablon strony')
				->addFilterEmptyToNull()
				->setMultioptions([null => 'Domyślny'] + $types);
		}

		//nazwa kategorii
		$this->addElementText('name')
			->setLabel('nazwa')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorStringLength(2, 128);
		
		//okres publikacji
		$this->addElementDateTimePicker('dateStart')
			->setLabel('początek publikacji')
			->addFilterEmptyToNull()
			->setDateStart(date('Y-m-d H:i:s'))
			->setDateMin(date('Y-m-d H:i:s'))
			->setLimitMax('cmsadmin-form-category-dateEnd');

		$this->addElementDateTimePicker('dateEnd')
			->setLabel('zakończenie publikacji')
			->addFilterEmptyToNull()
			->setDateStart(date('Y-m-d H:i:s'))
			->setLimitMin('cmsadmin-form-category-dateStart');

		//aktywna
		$this->addElementCheckbox('active')
			->setChecked()
			->setLabel('włączona');

		//zapis
		$this->addElementSubmit('submit1')
			->setLabel('zapisz');

		//SEO
		//nazwa kategorii
		$this->addElementText('title')
			->setLabel('meta tytuł')
			->setDescription('jeśli brak, użyta zostanie kaskada złożona nazw')
			->addFilterStringTrim()
			->addValidatorStringLength(2, 128);

		//meta description
		$this->addElementTextarea('description')
			->setLabel('meta opis');

		$view = \Mmi\App\FrontController::getInstance()->getView();

		//własny uri
		$this->addElementText('customUri')
			->setLabel('własny adres strony')
			//adres domyślny (bez baseUrl)
			->setDescription('domyślnie: ' . substr($view->url(['module' => 'cms', 'controller' => 'category', 'action' => 'dispatch', 'uri' => $this->getRecord()->uri], true), strlen($view->baseUrl) + 1))
			->addFilterStringTrim()
			->addFilterEmptyToNull()
			->addValidatorRecordUnique(new \Cms\Orm\CmsCategoryQuery, 'customUri', $this->getRecord()->id)
			->addValidatorStringLength(3, 255);

		//blank
		$this->addElementCheckbox('follow')
			->setChecked()
			->setLabel('widoczna dla wyszukiwarek');

		//zapis
		$this->addElementSubmit('submit2')
			->setLabel('zapisz');

		//Treść
		//atrybuty
		$this->initAttributes('cmsCategoryType', $this->getRecord()->cmsCategoryTypeId, 'category');

		//jeśli wstawione, dodany button z zapisem
		$this->addElementSubmit('submit3')
			->setLabel('zapisz');

		//Zaawansowane
		//przekierowanie na link
		$this->addElementText('redirectUri')
			->setLabel('przekierowanie na adres')
			->setDescription('np. http://www.google.pl')
			->addFilterStringTrim()
			->addValidatorRegex('@(https?|ftp)://(-\.)?([^\s/?\.#-]+\.?)+(/[^\s]*)?$@iS', 'Niepoprawny adres url');

		//przekierowanie na moduł
		$this->addElementText('mvcParams')
			->setLabel('przekierowanie na moduł CMS')
			->setDescription('np. module=blog&controller=index&action=index')
			->addFilterStringTrim()
			->addValidatorRegex('@module\=[a-zA-Z0-9\&\=]+@', 'niepoprawny adres modułu cms');

		//https
		$this->addElementSelect('https')
			->setMultioptions([null => 'bez zmian', '0' => 'wymuś brak https', 1 => 'wymuś https'])
			->addFilterEmptyToNull()
			->setLabel('https');

		//blank
		$this->addElementCheckbox('blank')
			->setLabel('otwieranie w nowym oknie');

		//zapis
		$this->addElementSubmit('submit4')
			->setLabel('zapisz');
	}

}
