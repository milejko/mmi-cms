<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Plugin;

class MailGrid extends \Mmi\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Mail\Query::factory()
				->orderDescId());

		$this->addColumn('checkbox', 'active', [
			'label' => 'Wysłany'
		]);

		$this->addColumn('text', 'dateAdd', [
			'label' => 'Data dodania'
		]);

		$this->addColumn('text', 'dateSent', [
			'label' => 'Data wysłania'
		]);

		$this->addColumn('text', 'to', [
			'label' => 'Do'
		]);

		$this->addColumn('text', 'subject', [
			'label' => 'Temat'
		]);

		$this->addColumn('text', 'fromName', [
			'label' => 'Od'
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje',
			'links' => [
				'edit' => $this->_view->url(['module' => 'cmsAdmin', 'controller' => 'mail', 'action' => 'preview', 'id' => '%id%'])
			]
		]);
	}

}
