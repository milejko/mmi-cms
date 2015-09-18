<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Controller;

class PageWidget extends Action {

	public function indexAction() {
		$this->view->grid = new \Cms\Plugin\PageWidgetGrid();
	}

	public function editAction() {
		$widget = \Cms\Orm\Page\Widget\Query::factory()->findPk($this->id);

		if ($widget !== null) {
			$this->widget = ucfirst($widget->module) . ':' . ucfirst($widget->controller) . ':' . $widget->action;
		}

		$form = new \CmsAdmin\Form\Page\Widget($widget, [
			'widget' => $this->widget
		]);

		if ($form->isSaved()) {
			$this->getHelperMessenger()->addMessage('Widget zapisany poprawnie');
			$this->getResponse()->redirect('cmsAdmin', 'pageWidget');
		}
		$this->view->widgetForm = $form;		
	}

	public function deleteAction() {
		$record = \Cms\Orm\Page\Widget\Query::factory()->findPk($this->id);
		if ($record !== null && $record->delete()) {
			$this->getHelperMessenger()->addMessage('Widget zostal usuniety');
		}
		$this->getResponse()->redirect('cmsAdmin', 'pageWidget');
	}

}
