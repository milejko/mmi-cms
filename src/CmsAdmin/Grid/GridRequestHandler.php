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
		$post = FrontController::getInstance()->getRequest()->getPost();
		//brak posta
		if ($post->isEmpty()) {
			return;
		}
		$this->_rebuildStateAndRender($post);
	}

	/**
	 * Zwraca sparsowaną tabelę
	 * @param \Mmi\Http\RequestPost $post
	 * @return GridStateFilter
	 */
	protected function _retrievePostFilter(\Mmi\Http\RequestPost $post) {
		if (false !== strpos($post->filter, $this->_grid->getClass())) {
			return (new GridStateFilter())
					->setField(substr($post->filter, strpos($post->filter, '[') + 1, -1))
					->setMethod('like')
					->setValue($post->value);
		}
	}

	protected function _retrievePostOrder(\Mmi\Http\RequestPost $post) {
		if (false !== strpos($post->order, $this->_grid->getClass())) {
			die($post->order);
			return (new GridStateOrder());
		}
	}

	protected function _rebuildStateAndRender(\Mmi\Http\RequestPost $post) {
		if (null !== $filter = $this->_retrievePostFilter($post)) {
			$this->_grid->getState()
				->addFilter($filter);
			$this->_render();
		}
		if (null !== $order = $this->_retrievePostOrder($post)) {
			$this->_grid->getState()
				->addOrder($order);
			$this->_render();
		}
	}

	protected function _render() {
		$renderer = new GridRenderer($this->_grid);
		FrontController::getInstance()->getResponse()
			->setContent($renderer->renderHeader() . $renderer->renderBody() . $renderer->renderFooter())
			->send();
		die();
	}

}
