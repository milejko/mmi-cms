<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Controller;

class Widget extends \Mmi\Controller\Action {

	public function textWidgetAction() {
		$widgetData = \Cms\Orm\Widget\Text\Query::factory()->findPk($this->id);
		/* @var $widgetData \Cms\Orm\Widget\Text\Record */

		if ($widgetData != null) {
			$this->view->text = $widgetData->data;
		}
	}

	public function pictureWidgetAction() {
		$picture = \Cms\Orm\Widget\Picture\Query::factory()->findPk($this->id);
		/* @var $picture \Cms\Orm\Widget\Picture\Record */

		if ($picture != null) {
			$this->view->imageUrl = $picture->getFirstImage()->getUrl();
		}
	}

}
