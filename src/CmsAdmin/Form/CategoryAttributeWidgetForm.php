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
 * Formularz widgetu z podpiętymi atrybutami
 * @method \Cms\Orm\CmsCategoryWidgetCategoryRecord getRecord()
 */
class CategoryAttributeWidgetForm extends \Cms\Form\AttributeForm {

	public function init() {

		$this->initAttributes('cmsCategoryWidget', $this->getOption('widgetId'), 'categoryWidgetRelation');

		$this->addElementSubmit('submit')
			->setLabel('zapisz');
	}
	
	/**
	 * Metoda użytkownika wykonywana na koniec konstruktora
	 * odrzuca transakcję jeśli zwróci false
	 */
	public function afterSave() {
		parent::afterSave();
		//wyczyszczenie cache listy produktów
		if ($this->getRecord()->cmsCategoryId) {
			\App\Registry::$cache->remove('category-widget-model-' . $this->getRecord()->cmsCategoryId);
		}
		return true;
	}

}
