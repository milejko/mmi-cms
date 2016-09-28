<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use \Cms\Model\TagRelationModel;

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
		$this->initAttributes('cms_category_type', $this->getRecord()->cmsCategoryTypeId, 'category');

		//tagi
		$this->addElementText('tags')
			->setLabel('tagi')
			->setDescription('lista tagów oddzielonych spacją')
			->setValue($this->getRecord()->id ? implode(' ', (new TagRelationModel('category', $this->getRecord()->id))->getTagRelations()) : '')
			->addFilterStringTrim();

		//jeśli wstawione, dodany button z zapisem
		$this->addElementSubmit('submit3')
			->setLabel('zapisz');

		//Zaawansowane
		//przekierowanie na link
		$this->addElementText('redirect')
			->setLabel('przekierowanie na adres')
			->setDescription('np. http://www.google.pl')
			->addFilterStringTrim()
			->addValidatorStringLength(5, 128);

		//przekierowanie na moduł
		$this->addElementText('mvcParams')
			->setLabel('przekierowanie na moduł CMS')
			->setDescription('np. blog/index/index')
			->addFilterStringTrim()
			->addValidatorStringLength(5, 128);

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

	/**
	 * Po zapisie rekordu
	 * @return boolean
	 */
	public function afterSave() {
		//zapis tagów
		(new TagRelationModel('category', $this->getRecord()->id))
			->createTagRelations(explode(' ', $this->getElement('tags')->getValue()));
		return parent::afterSave();
	}

}
