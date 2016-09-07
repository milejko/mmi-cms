<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use \Cms\Model\CategoryModel,
	\Cms\Model\CategoryRelationModel;

/**
 * Formularz artykułów
 */
class Article extends \Cms\Form\Form {

	public function init() {

		//tytuł
		$this->addElementText('title')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorNotEmpty()
			->setLabel('tytuł');

		//pobranie typów
		$types = (new \Cms\Orm\CmsArticleTypeQuery)->findPairs('id', 'name');

		//są dodane typy
		if (!empty($types)) {
			//typ artykułu
			$this->addElementSelect('cmsArticleTypeId')
				->setMultioptions([null => '---'] + $types)
				->setLabel('typ artykułu');
		}

		//nagłówek
		$this->addElementTinyMce('lead')
			->setLabel('nagłówek artykułu')
			->setModeAdvanced();

		//treść
		$this->addElementTinyMce('text')
			->setLabel('treść artykułu')
			->setModeAdvanced();

		//kategorie
		$this->addElementSelect('cmsCategoryId')
			->setMultiple()
			->setMultioptions((new CategoryModel)->getCategoryFlatTree())
			->setValue($this->getRecord()->id ? (new CategoryRelationModel('article', $this->getRecord()->id))->getCategoryIds() : [])
			->setLabel('kategorie')
			->setDescription('nie jest obowiązkowa, wybór wielu kategorii z CTRL');

		//uploader - plupload
		$this->addElementPlupload('uploader')
			->setLabel('załaduj pliki');

		//aktywny
		$this->addElementCheckbox('active')
			->setChecked()
			->setLabel('włączony');

		//button
		$this->addElementSubmit('submit')
			->setLabel('zapisz stronę');
	}

	/**
	 * Po zapisie rekordu
	 * @return boolean
	 */
	public function afterSave() {
		//zapis kategorii
		(new CategoryRelationModel('article', $this->getRecord()->id))
			->createCategoryRelations($this->getElement('cmsCategoryId')->getValue());
		return true;
	}

}
