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
class CategoryConfigController extends Mvc\Controller {

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
			$this->getResponse()->redirect('cmsAdmin', 'categoryConfig', 'config', ['categoryId' => $this->id, 'widgetId' => $widgetForm->getElement('cmsWidgetId')->getValue()]);
		}
		//form do widoku
		$this->view->widgetForm = $widgetForm;
	}

	/**
	 * Konfiguracja widgeta
	 */
	public function configAction() {
		//wyszukiwanie widgeta
		if (null === $widget = (new \Cms\Orm\CmsCategoryWidgetQuery)->findPk($this->widgetId)) {
			//brak widgeta
			return;
		}
		//wyszukiwanie relacji do edycji
		if (null === $widgetRelation = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
			->whereCmsCategoryId()->equals($this->categoryId)
			->andFieldCmsCategoryWidgetId()->equals($widget->id)
			->findPk($this->id)) {
			//nowy rekord relacji
			$widgetRelation = new \Cms\Orm\CmsCategoryWidgetCategoryRecord();
			//parametry relacji
			$widgetRelation->cmsCategoryWidgetId = $widget->id;
			$widgetRelation->cmsCategoryId = $this->categoryId;
		}
		//instancja formularza
		$form = new $widget->formClass($widget->recordClass ? new $widget->recordClass($widgetRelation->recordId) : null);
		//form zapisany
		if ($form->isSaved()) {
			//zapis powiązanego id
			$widgetRelation->recordId = $widget->recordClass ? $form->getRecord()->id : null;
			$widgetRelation->configJson = \json_encode($widget->recordClass ? $form->getRecord()->getOptions() : $form->getValues());
			$widgetRelation->save();
			$this->getResponse()->redirect('cmsAdmin', 'categoryConfig', 'config');
		}
		//form do widoku
		$this->view->widgetConfigForm = $form;
	}

}
