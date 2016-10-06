<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

/**
 * Kontroler konfiguracji kategorii - stron CMS
 */
class CategoryWidgetRelationController extends Mvc\Controller {

	/**
	 * Wybór widgeta do dodania
	 */
	public function addAction() {
		//wyszukiwanie kategorii
		if (null === $cat = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->id)) {
			return;
		}
		//zakładka sekcje
		$widgetForm = (new \CmsAdmin\Form\CategoryAddWidget($cat));
		//zapisany form
		if ($widgetForm->isSaved()) {
			$this->getResponse()->redirect('cmsAdmin', 'categoryWidgetRelation', 'config', ['categoryId' => $this->id, 'widgetId' => $widgetForm->getElement('cmsWidgetId')->getValue()]);
		}
		//form do widoku
		$this->view->widgetForm = $widgetForm;
	}

	/**
	 * Konfiguracja widgeta
	 */
	public function configAction() {
		//wyszukiwanie widgeta
		if (null === $widgetRecord = (new \Cms\Orm\CmsCategoryWidgetQuery)->findPk($this->widgetId)) {
			//brak widgeta
			return;
		}
		//wyszukiwanie relacji do edycji
		if (null === $widgetRelationRecord = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
			->whereCmsCategoryId()->equals($this->categoryId)
			->andFieldCmsCategoryWidgetId()->equals($widgetRecord->id)
			->findPk($this->id)) {
			//nowy rekord relacji
			$widgetRelationRecord = new \Cms\Orm\CmsCategoryWidgetCategoryRecord();
			//parametry relacji
			$widgetRelationRecord->cmsCategoryWidgetId = $widgetRecord->id;
			$widgetRelationRecord->cmsCategoryId = $this->categoryId;
		}
		//rekord do formularza to rekord wiązania
		$record = $widgetRelationRecord;
		//jeśli widget ma swój rekord, to ten idzie do formularza
		if ($widgetRecord->recordClass) {
			$record = new $widgetRecord->recordClass($widgetRelationRecord->recordId);
		}
		//instancja formularza
		$form = new $widgetRecord->formClass($record);
		$form->setFromArray((array) $widgetRelationRecord->getConfig());
		//form zapisany
		if ($form->isSaved()) {
			//zapis powiązanego id
			$widgetRelationRecord->recordId = $record->id;
			//zapis konfiguracji
			$widgetRelationRecord->configJson = \json_encode($record->getOptions());
			$widgetRelationRecord->save();
			$this->getResponse()->redirect('cmsAdmin', 'categoryWidgetRelation', 'config');
		}
		//form do widoku
		$this->view->widgetRelationForm = $form;
	}

	/**
	 * Lista podglądów widgetów
	 */
	public function previewAction() {
		//wyłączenie layout
		$this->view->setLayoutDisabled();
		$this->view->widgetModel = new \Cms\Model\CategoryWidgetModel($this->categoryId);
	}

	/**
	 * Kasowanie widgeta
	 */
	public function deleteAction() {
		//wyszukiwanie relacji do edycji
		if (null === $widgetRelation = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
			->whereCmsCategoryId()->equals($this->categoryId)
			->findPk($this->id)) {
			return '';
		}
		//usuwanie relacji
		$widgetRelation->delete();
		return '';
	}

	/**
	 * Sortowanie ajax widgetów
	 * @return string
	 */
	public function sortAction() {
		$this->getResponse()->setTypePlain();
		//brak pola
		if (null === $serial = $this->getPost()->__get('widget-item')) {
			return $this->view->getTranslate()->_('Przenoszenie nie powiodło się');
		}
		//sortowanie
		(new \Cms\Model\CategoryWidgetModel($this->id))
			->sortBySerial($serial);
		//pusty zwrot
		return '';
	}

}
