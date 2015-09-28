<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

class PageWidgetGrid extends \CmsAdmin\Plugin\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Page\Widget\Query::factory()
				->orderAscId());

		$this->addColumn('text', 'name', [
			'label' => 'Nazwa'
		]);

		$this->addColumn('custom', 'Object', [
			'label' => 'Moduł - Kontroler - Akcja',
			'value' => '{$rowData->module} - {$rowData->controller} - {$rowData->action}'
		]);

		$this->addColumn('text', 'params', [
			'label' => 'Domyślne parametry'
		]);

		$this->addColumn('checkbox', 'active', [
			'label' => 'Aktywny'
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'Operacje'
		]);
	}

}
