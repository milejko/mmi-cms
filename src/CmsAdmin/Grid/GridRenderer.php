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
	 * @param array $elements
	 * @param integer $count
	 * @param \Mmi\Orm\RecordCollection $collection
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
			$html .= '<th>' . $element->renderLabel() . $element->renderFilter() . '</th>';
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
		$paginator = (new \Mmi\Paginator\Paginator())
			->setPage($this->_grid->getState()->getPage())
			->setRowsPerPage($this->_grid->getState()->getRowsPerPage())
			->setRowsCount($this->_grid->getState()->getDataCount());
		return '<tr><th colspan="' . count($this->_grid->getElements()) . '">' . (string) $paginator . '</th></tr>';
	}

	/**
	 * Uruchomienie renderingu
	 * @return string
	 */
	public function render() {
		return '<table class="grid striped"><tbody>' .
			$this->renderHeader() .
			$this->renderBody() .
			$this->renderFooter() .
			'</tbody></table>';
	}

}
