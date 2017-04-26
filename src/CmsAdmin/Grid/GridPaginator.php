<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid;

/**
 * Obiekt stronicowania grida
 */
class GridPaginator
{

    /**
     * Obiekt grida
     * @var Grid
     */
    private $_grid;

    /**
     * Konstruktror
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        //podpięcie grida
        $this->_grid = $grid;
    }

    /**
     * Renderuje 
     * @return type
     */
    public function render()
    {
        return '<tr><th class="paginator" colspan="' . count($this->_grid->getColumns()) . '">' .
            'Znaleziono: <strong>' . $this->_grid->getState()->getDataCount() . '</strong> pozycji, strona: ' .
            $this->_renderSelect() .
            ' z ' . $this->getPagesCount() .
            (\App\Registry::$auth->hasRole('admin') ? ', <a target="_blank" href="' . \Mmi\App\FrontController::getInstance()->getView()->url([$this->_grid->getClass() => 'export']) . '">export csv</a>' : '') .
            '</th></tr>';
    }

    /**
     * Zwraca obliczoną ilość stron
     * @return integer
     */
    public function getPagesCount()
    {
        return ceil($this->_grid->getState()->getDataCount() / $this->_grid->getState()->getRowsPerPage());
    }

    /**
     * Renderuje Column select
     * @return \Mmi\Form\Element\Select
     */
    protected function _renderSelect()
    {
        //ustawienie opcji i zaznaczenia
        return (new \Mmi\Form\Element\Select($this->_grid->getClass() . '[_paginator_]'))
                ->setMultioptions($this->_getPages())
                ->setValue($this->_grid->getState()->getPage());
    }

    /**
     * Pobiera tablicę ze stronami
     * @return array
     */
    protected function _getPages()
    {
        $multioptions = [];
        for ($i = 1; $i <= $this->getPagesCount(); $i++) {
            $multioptions[$i] = $i;
        }
        return $multioptions;
    }

}
