<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Plugin;

class WidgetGrid extends \Mmi\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Page\Widget\Query::active()
				->orderAscId());

		$this->addColumn('text', 'name', [
			'label' => 'Nazwa',
		]);

		$this->addColumn('custom', 'data', [
			'label' => 'Zawartość widgetów',
			'seekable' => false,
			'sortable' => false,
			'value' => '{if $rowData->isExistWidgetEdit($rowData->action)}<a class="button small" href="' . $this->_view->baseUrl . '/cms/admin-widget/%action%Edit/' . '">Przejdź</a>{/if}'
		]);
	}

}
