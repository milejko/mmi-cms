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
 * Formularz edycji seo kategorii
 */
class CategorySeo extends \Cms\Form\AttributeForm {

	public function init() {

		//nazwa kategorii
		$this->addElementText('title')
			->setLabel('meta tytuł')
			->setDescription('jeśli brak, użyta zostanie kaskada złożona nazw')
			->addFilterStringTrim()
			->addValidatorStringLength(2, 128);

		//meta description
		$this->addElementTextarea('description')
			->setLabel('meta opis');

		$defaultUri = \Mmi\App\FrontController::getInstance()->getView()->url(['module' => 'cms', 'controller' => 'category', 'action' => 'dispatch', 'uri' => $this->getRecord()->uri], true);

		//własny uri
		$this->addElementText('customUri')
			->setLabel('własny adres strony')
			->setDescription('domyślnie: <a target="_blank" href="' . $defaultUri . '">' . $defaultUri . '</a>')
			->addFilterStringTrim()
			->addFilterEmptyToNull()
			->addValidatorRecordUnique(new \Cms\Orm\CmsCategoryQuery, 'customUri', $this->getRecord()->id)
			->addValidatorStringLength(3, 255);

		//blank
		$this->addElementCheckbox('follow')
			->setChecked()
			->setLabel('widoczna dla wyszukiwarek');

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz');
	}

}
