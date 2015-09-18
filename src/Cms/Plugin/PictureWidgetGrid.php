<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Plugin;

class PictureWidgetGrid extends \Mmi\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Widget\Picture\Query::factory()
				->join('cms_file')->on('id', 'objectId')
				->where('object', 'cms_file')->equals('cmswidgetpicture'));

		$this->addColumn('text', 'id', [
			'label' => 'ID zdjecia'
		]);

		$this->addColumn('custom', 'picture', [
			'label' => 'Zdjecie',
			'seekable' => 'false',
			'sortable' => 'false',
			'value' => '<img src="{$rowData->getJoined(\'cms_file\')->getUrl(\'scale\',\'150\')}">'
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'Operacje',
			'links' => [
				'edit' => $this->_view->url(['id' => '%id%', 'action' => 'pictureWidgetEdit', 'controller' => 'admin-widget', 'module' => 'cms']),
				'delete' => $this->_view->url(['id' => '%id%', 'action' => 'pictureWidgetDelete', 'controller' => 'admin-widget', 'module' => 'cms'])
			]
		]);
	}

}
