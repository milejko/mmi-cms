<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

/**
 * Grid opcji kontaktu
 */
class ContactOptionGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery(\Cms\Orm\CmsContactOptionQuery::factory());

		//temat
		$this->addColumnText('name')
			->setLabel('temat pytania');

		//forward
		$this->addColumnText('sendTo')
			->setLabel('prześlij na e-mail');

		//kolejność
		$this->addColumnText('order')
			->setLabel('kolejność');

		//operacje
		$this->addColumnOperation()
			->setDeleteParams([
				'module' => 'cmsAdmin',
				'controller' => 'contact',
				'action' => 'editSubject',
				'id' => '%id%'
			])
			->setEditParams([
				'module' => 'cmsAdmin',
				'controller' => 'contact',
				'action' => 'deleteSubject',
				'id' => '%id%'
		]);
	}

}
