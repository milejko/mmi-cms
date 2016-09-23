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

		//nazwa kategorii
		$this->addElementText('name')
			->setLabel('nazwa')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorStringLength(2, 128);

		//typy treści (jeśli istnieją)
		if ([] !== $types = (new \Cms\Orm\CmsCategoryTypeQuery)->orderAscName()->findPairs('id', 'name')) {
			$this->addElementSelect('cmsCategoryTypeId')
				->setLabel('typ treści')
				->addFilterEmptyToNull()
				->setMultioptions([null => 'Domyślny'] + $types);
		}

		//tagi
		$this->addElementText('tags')
			->setLabel('tagi')
			->setDescription('lista tagów oddzielonych spacją')
			->setValue($this->getRecord()->id ? implode(' ', (new TagRelationModel('category', $this->getRecord()->id))->getTagRelations()) : '')
			->addFilterStringTrim();

		//aktywna
		$this->addElementCheckbox('active')
			->setChecked()
			->setLabel('widoczna');

		//atrybuty
		if ($this->initAttributes('cms_category_type', $this->getRecord()->cmsCategoryTypeId, 'category', 'Atrybuty')) {
			
		}

		$defaultUri = \Mmi\App\FrontController::getInstance()->getView()->url(['module' => 'cms', 'controller' => 'category', 'action' => 'dispatch', 'uri' => $this->getRecord()->uri], true);

		//własny uri
		$this->addElementText('customUri')
			->setLabel('własny adres strony')
			->setDescription('domyślnie: <a target="_blank" href="' . $defaultUri . '">' . $defaultUri . '</a>')
			->addFilterStringTrim()
			->addFilterEmptyToNull()
			->addValidatorRecordUnique(new \Cms\Orm\CmsCategoryQuery, 'customUri', $this->getRecord()->id)
			->addValidatorStringLength(3, 255);

		//nazwa kategorii
		$this->addElementText('title')
			->setLabel('meta tytuł')
			->setDescription('jeśli brak, użyta zostanie kaskada nazw')
			->addFilterStringTrim()
			->addValidatorStringLength(2, 128);

		//meta description
		$this->addElementTextarea('description')
			->setLabel('meta opis');

		//https
		$this->addElementSelect('https')
			->setMultioptions([null => 'bez zmian', '0' => 'wymuś brak https', 1 => 'wymuś https'])
			->addFilterEmptyToNull()
			->setLabel('https');

		//blank
		$this->addElementCheckbox('blank')
			->setLabel('otwórz w nowym oknie');

		//blank
		$this->addElementCheckbox('follow')
			->setChecked()
			->setLabel('widoczna dla wyszukiwarek');

		//zapis
		$this->addElementSubmit('submit3')
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
		return true;
	}

}
