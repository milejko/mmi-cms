<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

/**
 * Kontroler widgetów
 */
class WidgetController extends \Mmi\Mvc\Controller {

	/**
	 * Widget tekstu
	 */
	public function textAction() {
		
	}

	/**
	 * Widget galerii
	 */
	public function galleryAction() {
		$widgetModel = $this->view->widgetModel;
		/* @var $widgetModel \Cms\Model\CategoryWidgetModel */
		//brak widgeta
		if (null === $widgetRelation = $widgetModel->findWidgetRelationById($this->widgetId)) {
			return '';
		}
		//wyszukiwanie obrazów
		$this->view->images = (new \Cms\Orm\CmsFileQuery)->imagesByObject('cmsgallery', $widgetRelation->getConfig()->recordId)
			->find();
	}

}
