<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid;

use AppendIterator;
use Mmi\App\App;
use Mmi\Mvc\View;

/**
 * Obiekt stronicowania grida
 */
class GridPaginator
{

    /**
     * Template paginatora
     */
    const TEMPLATE = 'cmsAdmin/grid/paginator';

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
        $view = App::$di->get(View::class);
        $view->_grid = $this->_grid;
        $view->_paginator = $this;
        return $view->renderTemplate(self::TEMPLATE);
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
     * Pobiera tablicę ze stronami
     * @return array
     */
    public function getPages()
    {
        $multioptions = [];
        for ($i = 1; $i <= $this->getPagesCount(); $i++) {
            $multioptions[$i] = $i;
        }
        return $multioptions;
    }

    /**
     * @return string
     */
    public function getExportCsvUrl()
    {
        return App::$di->get(View::class)->url([$this->_grid->getClass() => 'export']);
    }
}
