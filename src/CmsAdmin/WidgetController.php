<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

class WidgetController extends Mvc\Controller {

	public function indexAction() {
		$this->view->grid = new \CmsAdmin\Plugin\WidgetGrid();
	}

	public function textWidgetEditAction() {
		$widget = \Cms\Orm\Widget\Text\Query::factory()->findPk($this->id);
		if ($widget !== null) {
			$this->view->textId = $widget->id;
		}

		$this->view->grid = new \CmsAdmin\Plugin\TextWidgetGrid();

		$form = new \CmsAdmin\Form\Widget\Text($widget);
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Tekst został dodany');
			$this->getResponse()->redirect('cmsAdmin', 'widget', 'textWidgetEdit');
		}
		$this->view->textForm = $form;
	}

	public function textWidgetDeleteAction() {
		if (null !== ($record = \Cms\Orm\Widget\Text\Query::factory()->findPk($this->id)) && $record->delete()) {
			$this->getMessenger()->addMessage('Tekst usunięty poprawnie');
		}
		$this->getResponse()->redirect('cmsAdmin', 'widget', 'textWidgetEdit');
	}

	public function pictureWidgetEditAction() {
		$pictureRec = \Cms\Orm\Widget\Picture\Query::factory()->findPk($this->id);
		if ($pictureRec != null) {
			$this->view->pictureId = $pictureRec->id;
		}
		$this->view->grid = new \CmsAdmin\Plugin\PictureWidgetGrid();

		$form = new \CmsAdmin\Form\Widget\Picture($pictureRec);
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Zdjęcie zostało zapisane');
			$this->getResponse()->redirect('cmsAdmin', 'widget', 'pictureWidgetEdit');
		}
		$this->view->pictureForm = $form;
	}

	public function pictureWidgetDeleteAction() {
		if (null !== ($record = \Cms\Orm\Widget\Picture\Query::factory()->findPk($this->id)) && $record->delete()) {
			$this->getMessenger()->addMessage('Zdjęcie usunięte poprawnie');
		}
		$this->getResponse()->redirect('cmsAdmin', 'widget', 'pictureWidgetEdit');
	}

}
