<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid;
use Mmi\App\FrontController;

/**
 * Klasa obsługi zapytań do grida
 */
class GridRequestHandler {
	
	/**
	 * Obiekt grida
	 * @var Grid
	 */
	protected $_grid;
	
	/**
	 * Konstruktor przypina obiekt grida
	 * @param \CmsAdmin\Grid\Grid $grid
	 */
	public function __construct(Grid $grid) {
		$this->_grid = $grid;
	}

	/**
	 * Obsługa requestu jeśli się pojawił
	 */
	public function handleRequest() {
		//brak posta
		if (FrontController::getInstance()->getRequest()->getPost()->isEmpty()) {
			return;
		}
		
	}
	
}