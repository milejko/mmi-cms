<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid;

/**
 * Obiekt eksportu danych z grida
 */
class GridExporter {

	/**
	 * Obiekt grida
	 * @var Grid
	 */
	private $_grid;

	/**
	 * Konstruktror
	 * @param Grid $grid
	 */
	public function __construct(Grid $grid) {
		//podpięcie grida
		$this->_grid = $grid;
	}

	public function passCsv() {
		$csv = fopen('php://output', 'w');
		foreach ($this->_getData() as $data) {
			fputcsv($csv, $data);
		}
		fclose($csv);
	}

	/**
	 * Wybiera dane do eksportu (uwzględnia filtry i sortowania)
	 * @return array
	 */
	protected function _getData() {
		return $this->_grid->getState()
				->setupQuery($this->_grid->getQuery())
				->limit(100000)
				->offset(null)
				->find()->toArray();
	}

}
