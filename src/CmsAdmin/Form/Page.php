<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

/**
 * Formularz dodawania strony CMS
 * @method \Cms\Orm\Page\Record getRecord()
 */
class Page extends \Cms\Form\Form {

	public function init() {

		//nazwa strony
		$this->addElementText('name')
			->setLabel('Nazwa strony')
			->addValidatorStringLength(2, 128)
			->setRequired();

		//adres url do routera
		$this->addElementText('address')
			->setLabel('Adres strony')
			->addFilter('url')
			->addValidatorStringLength(2, 128)
			->setRequired();

		//tytuł
		$this->addElementText('title')
			->setLabel('Tytuł strony (head/title)')
			->addValidatorStringLength(3, 128);

		//meta opis
		$this->addElementTextarea('description')
			->setLabel('Opis strony (meta/description)')
			->addValidatorStringLength(3, 1024);

		$this->addElementCheckbox('active')
			->setLabel('Aktywna')
			->setValue(true);

		//@TODO: do testów
		$this->addElementTextarea('text')
			->setLabel('Treść szablonu (do testów)');

		//ustawianie pól nawigatora i routera
		if ($this->_record->cmsNavigationId && (null !== ($nr = \Cms\Orm\CmsNavigationQuery::factory()->findPk($this->_record->cmsNavigationId)))) {
			$this->getElement('title')->setValue($nr->title);
			$this->getElement('description')->setValue($nr->description);
		}
		if ($this->_record->cmsRouteId && (null !== ($rr = \Cms\Orm\Route\Query::factory()->findPk($this->_record->cmsRouteId)))) {
			$this->getElement('address')->setValue($rr->pattern);
		}

		//submit
		$this->addElementSubmit('submit')
			->setLabel('Zapisz')
			->setIgnore();
	}
	
	/**
	 * Przed zapisem ustawienie i przypięcie elementu nawigatora i routy
	 * @return boolean
	 */
	public function beforeSave() {
		//pobieranie elementu nawigacji
		$navigationRecord = $this->getRecord()->cmsNavigationId ? \Cms\Orm\CmsNavigationQuery::factory()->findPk($this->getRecord()->cmsNavigationId) : null;
		//jeśli nie pobrano - nowy
		if ($navigationRecord === null) {
			$navigationRecord = new \Cms\Orm\CmsNavigationRecord();
		}
		//ustawianie opcji elementu
		$navigationRecord->absolute = 0;
		$navigationRecord->action = 'index';
		$navigationRecord->active = 1;
		$navigationRecord->blank = 0;
		$navigationRecord->controller = 'page';
		$navigationRecord->description = $this->getElement('description')->getValue();
		$navigationRecord->https = 0;
		$navigationRecord->independent = 1;
		$navigationRecord->label = $this->getRecord()->name;
		$navigationRecord->module = 'cms';
		$navigationRecord->nofollow = 0;
		$navigationRecord->title = $this->getElement('title')->getValue();
		$navigationRecord->visible = 0;
		$navigationRecord->save();

		//pobieranie routy
		$routeRecord = $this->getRecord()->cmsRouteId ? \Cms\Orm\Route\Query::factory()->findPk($this->getRecord()->cmsRouteId) : null;
		//jeśli nie pobrano
		if ($routeRecord === null) {
			$routeRecord = new \Cms\Orm\Route\Record();
		}
		//ustawianie routy
		$routeRecord->active = $this->getElement('active')->getValue();
		$routeRecord->pattern = $this->getElement('address')->getValue();
		$routeRecord->save();

		//przypisanie routy i elementu nawigacji
		$this->getRecord()->cmsNavigationId = $navigationRecord->id;
		$this->getRecord()->cmsRouteId = $routeRecord->id;
		//ustawienie właściciela
		$this->getRecord()->cmsAuthId = \App\Registry::$auth->getId();
		return true;
	}

	/**
	 * Po zapisie ustawianie opcji routy i nawigacji
	 * @return boolean
	 */
	public function afterSave() {
		//pobranie nawigacji
		$navigationRecord = \Cms\Orm\CmsNavigationQuery::factory()->findPk($this->getRecord()->cmsNavigationId);
		//pobranie routy
		$routeRecord = \Cms\Orm\Route\Query::factory()->findPk($this->getRecord()->cmsRouteId);
		//zapis do rekordu nawigacji parametru ID strony
		$navigationRecord->params = 'id=' . $this->getRecord()->id;
		if (!$navigationRecord->order) {
			$navigationRecord->order = 10000;
		}
		//zapis do rekordu routy ID
		$routeRecord->replace = 'module=cms&controller=page&action=index&id=' . $this->getRecord()->id;
		if (!$routeRecord->order) {
			$routeRecord->order = 10000;
		}
		//zapis elementu nawigacyjnego i routy
		return $navigationRecord->save() && $routeRecord->save();
	}

}
