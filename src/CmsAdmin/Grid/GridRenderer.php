<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid;

use Mmi\App\FrontController;

/**
 * Renderer HTML grida
 */
class GridRenderer
{

    /**
     * Obiekt grida
     * @var Grid
     */
    private $_grid;

    //szablon scalający grida
    const TEMPLATE_GRID = 'cmsAdmin/grid/grid';

    //szablon nagłówka grida (filtrów)
    const TEMPLATE_HEADER = 'cmsAdmin/grid/header';

    //szablon wnętrza grida (danych)
    const TEMPLATE_BODY = 'cmsAdmin/grid/body';

    /**
     * Konstruktror
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        //podpięcie grida
        $this->_grid = $grid;
        //grid do widoku
        FrontController::getInstance()->getView()->_grid = $grid;
        //renderer do widoku
        FrontController::getInstance()->getView()->_renderer = $this;
    }

    /**
     * Renderuje nagłówek
     * @return string html
     */
    public function renderHeader()
    {
        //render szablonu
        return FrontController::getInstance()->getView()->renderTemplate(self::TEMPLATE_HEADER);
    }

    /**
     * Renderuje ciało tabeli
     * @return string html
     */
    public function renderBody()
    {
        //render szablonu
        return FrontController::getInstance()->getView()->renderTemplate(self::TEMPLATE_BODY);
    }

    /**
     * Renderuje paginator
     * @return string html
     */
    public function renderFooter()
    {
        //powołanie paginatora i render
        return (new GridPaginator($this->_grid))->render();
    }

    /**
     * Uruchomienie renderingu
     * @return string
     */
    public function render()
    {
        //render nagłówka, ciała i stopki
        return FrontController::getInstance()->getView()->renderTemplate(self::TEMPLATE_GRID);
    }

}
