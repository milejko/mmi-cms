<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

/**
 * Grid opcji kontaktu
 */
class ContactOptionGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery(new \Cms\Orm\CmsContactOptionQuery);

		//temat
		$this->addColumnText('name')
			->setLabel('temat pytania');

		//forward
		$this->addColumnText('sendTo')
			->setLabel('prześlij na e-mail');

		//operacje
		$this->addColumnOperation()
			->setEditParams([
				'module' => 'cmsAdmin',
				'controller' => 'contact',
				'action' => 'editSubject',
				'id' => '%id%'
			])
			->setDeleteParams([
				'module' => 'cmsAdmin',
				'controller' => 'contact',
				'action' => 'deleteSubject',
				'id' => '%id%'
		]);
	}

}
