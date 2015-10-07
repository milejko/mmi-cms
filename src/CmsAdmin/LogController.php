<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

/**
 * Kontroler logów
 */
class LogController extends Mvc\Controller {

	/**
	 * Lista logów
	 */
	public function indexAction() {
		$grid = new \CmsAdmin\Plugin\LogGrid();
		$this->view->grid = $grid;
		
		$grid->getState()
			->setFilters([])
			->setOrder([])
			->addFilter((new \CmsAdmin\Grid\GridStateFilter())
				->setField('operation')
				->setMethod('like')
				->setValue('log'))
			->addFilter((new \CmsAdmin\Grid\GridStateFilter())
				->setField('success')
				->setMethod('equals')
				->setValue(0))
			->addOrder((new \CmsAdmin\Grid\GridStateOrder())
				->setField('id')
				->setMethod('orderDesc'))
			->setRowsPerPage(10)
			->setPage(1);
	}

}
