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
		
		$grid2 = new \CmsAdmin\Grid\Grid();
		$grid2->setQuery(\Cms\Orm\CmsLogQuery::factory())
			->addElement((new \CmsAdmin\Grid\Element\IndexElement()))
			->addElement((new \CmsAdmin\Grid\Element\TextElement())->setName('dateTime')->setLabel('data i czas'))
			->addElement((new \CmsAdmin\Grid\Element\TextElement())->setName('operation')->setLabel('operacja'))
			->addElement((new \CmsAdmin\Grid\Element\TextElement())->setName('url')->setLabel('URL'))
			->addElement((new \CmsAdmin\Grid\Element\TextElement())->setName('data')->setLabel('dane'))
			->addElement((new \CmsAdmin\Grid\Element\TextElement())->setName('ip')->setLabel('adres IP'))
			->addElement((new \CmsAdmin\Grid\Element\CheckboxElement())->setName('success')->setLabel('sukces'))
		;
		$grid2->getState()
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
		$this->view->grid2 = $grid2;

	}

}
