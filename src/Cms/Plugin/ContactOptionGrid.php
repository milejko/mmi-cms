<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Plugin;

class ContactOptionGrid extends \Mmi\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Contact\Option\Query::factory());

		$this->addColumn('text', 'name', [
			'label' => 'temat pytania'
		]);

		$this->addColumn('text', 'sendTo', [
			'label' => 'prześlij na e-mail'
		]);

		$this->addColumn('text', 'order', [
			'label' => 'kolejność'
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje',
			'links' => [
				'edit' => $this->_view->url([
					'module' => 'cmsAdmin',
					'controller' => 'contact',
					'action' => 'editSubject',
					'id' => '%id%'
				]),
				'delete' => $this->_view->url([
					'module' => 'cmsAdmin',
					'controller' => 'contact',
					'action' => 'deleteSubject',
					'id' => '%id%'
				]),
			]
		]);
	}

}
