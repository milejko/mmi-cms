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
 * Renderer HTML grida
 */
class GridRenderer {

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

	/**
	 * Renderuje nagłówek
	 * @return string html
	 */
	public function renderHeader() {
		//wiersz nagłówka
		$html = '<tr>';
		//iteracja po elementach
		foreach ($this->_grid->getElements() as $element) {
			//renderuje kolumnę z labelką i filtrem
			$html .= '<th>' . $element->renderLabel() . '<br />' . $element->renderFilter() . '</th>';
		}
		//zwrot html
		return $html . '</tr>';
	}

	/**
	 * Renderuje ciało tabeli
	 * @return string html
	 */
	public function renderBody() {
		$html = '';
		//iteracja po rekordach
		foreach ($this->_grid->getDataCollection() as $record) {
			//tworzenie wiersza
			$html .= '<tr>';
			//iteracja po elementach
			foreach ($this->_grid->getElements() as $element) {
				//renderuje krotkę
				$html .= '<td>' . $element->renderCell($record) . '</td>';
			}
			//zamknięcie wiersza
			$html .= '</tr>';
		}
		//zwrot html
		return $html;
	}

	/**
	 * Renderuje paginator
	 * @return string html
	 */
	public function renderFooter() {
		//powołanie paginatora i render
		return (new GridPaginator($this->_grid))->render();
	}

	/**
	 * Uruchomienie renderingu
	 * @return string
	 */
	public function render() {
		$view = FrontController::getInstance()->getView();
		//dołączenie js
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/jquery/ui.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/grid2.js');
		//render nagłówka ciała i stopki
		return '<table id="' . $this->_grid->getClass() . '" class="grid striped">' .
			$this->renderHeader() .
			$this->renderBody() .
			$this->renderFooter() .
			'</table>';
	}
	
}
