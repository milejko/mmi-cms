<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use \Cms\Model\TagModel,
	\Cms\Model\CategoryModel;

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

		$types = (new \Cms\Orm\CmsArticleTypeQuery)->findPairs('id', 'name');

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
			->setMultioptions((new CategoryModel('x'))->getCategoryList())
			->setValue($this->getRecord()->id ? array_keys((new CategoryModel('article', $this->getRecord()->id))->getCategoryRelations()) : [])
			->setLabel('kategorie')
			->setDescription('nie jest obowiązkowa, wybór wielu kategorii z CTRL');

		//tagi
		$this->addElementText('tags')
			->setLabel('tagi')
			->setDescription('lista tagów oddzielonych spacją')
			->setValue($this->getRecord()->id ? implode(' ', (new TagModel('article', $this->getRecord()->id))->getTagRelations()) : '')
			->addFilterStringTrim();

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
		(new CategoryModel('article', $this->getRecord()->id))->createCategoryRelations($this->getElement('cmsCategoryId')->getValue());
		//zapis tagów
		(new TagModel('article', $this->getRecord()->id))->createTagRelations(explode(' ', $this->getElement('tags')->getValue()));
		return true;
	}

}
