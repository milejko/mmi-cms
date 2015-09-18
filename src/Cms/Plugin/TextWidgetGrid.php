<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Plugin;

class TextWidgetGrid extends \Mmi\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Widget\Text\Query::factory()
				->orderAscId());

		$this->addColumn('text', 'id', [
			'label' => 'ID zawartości'
		]);

		$this->addColumn('text', 'data', [
			'label' => 'Zawartość'
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'Operacje',
			'links' => [
				'edit' => $this->_view->url(['id' => '%id%', 'action' => 'textWidgetEdit', 'controller' => 'admin-widget', 'module' => 'cms']),
				'delete' => $this->_view->url(['id' => '%id%', 'action' => 'textWidgetDelete', 'controller' => 'admin-widget', 'module' => 'cms'])
			]
		]);
	}

}
