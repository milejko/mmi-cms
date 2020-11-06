<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid;

use Mmi\App\App;
use Mmi\Http\Request;
use Mmi\Http\Response;

/**
 * Klasa obsługi zapytań do grida
 */
class GridRequestHandler
{

    /**
     * Obiekt grida
     * @var Grid
     */
    protected $_grid;

    /**
     * Konstruktor przypina obiekt grida
     * @param \CmsAdmin\Grid\Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->_grid = $grid;
    }

    /**
     * Obsługa requestu jeśli się pojawił
     */
    public function handleRequest()
    {
        //obsługa eksportera
        $this->_handleCsvExporter(App::$di->get(Request::class));
        //obsługa danych z POST
        $post = App::$di->get(Request::class)->getPost();
        //brak posta
        if ($post->isEmpty()) {
            return;
        }
        //jeśli przebudowano filtry lub sortowania render grida
        if ($this->_rebuildFilter($post) || $this->_rebuildOrder($post)) {
            $this->_render();
        }
    }

    /**
     * Obsługa eksportera CSV
     * @param \Mmi\Http\Request $request
     * @return type
     */
    protected function _handleCsvExporter(\Mmi\Http\Request $request)
    {
        //nieznana operacja
        if ('export' != $request->{$this->_grid->getClass()}) {
            return;
        }
        //ustawia typ danych
        App::$di->get(Response::class)
            ->setHeader('Content-disposition', 'attachment; filename=' . $this->_grid->getClass() . '.csv')
            ->setTypePlain()
            ->sendHeaders();
        //przesyła CSV do klienta
        (new GridExporter($this->_grid))->passCsv();
        exit;
    }

    /**
     * Zwraca obiekt filtra na podstawie post
     * @param \Mmi\Http\RequestPost $post
     * @return GridStateFilter
     */
    protected function _retrievePostFilter(\Mmi\Http\RequestPost $post)
    {
        //brak filtracji dla tego grida
        if (false === strpos($post->filter, $this->_grid->getClass())) {
            return;
        }
        $columnName = substr($post->filter, strpos($post->filter, '[') + 1, -1);
        $tableName = null;
        $fieldName = $columnName;
        //obsługa joinowanych
        if (strpos($columnName, '.')) {
            $fieldTable = explode('.', $columnName);
            $tableName = $fieldTable[0];
            $fieldName = $fieldTable[1];
        }
        //paginator
        if ($fieldName == '_paginator_') {
            //ustawianie filtra
            return (new GridStateFilter())
                    ->setField($fieldName)
                    ->setValue($post->value);
        }
        //iteracja po kolumnach
        foreach ($this->_grid->getColumns() as $column) {
            if ($column->getName() != $columnName) {
                continue;
            }
            //ustawianie filtra
            return (new GridStateFilter())
                    ->setField($fieldName)
                    ->setTableName($tableName)
                    ->setMethod($column->getMethod())
                    ->setValue($post->value);
        }
    }

    /**
     * Zwraca obiekt sortowania na podstawie post
     * @param \Mmi\Http\RequestPost $post
     * @return GridStateOrder
     */
    protected function _retrievePostOrder(\Mmi\Http\RequestPost $post)
    {
        //brak zmian sortowania dla tego grida
        if (false === strpos($post->order, $this->_grid->getClass())) {
            return;
        }
        //obsługa joinowanych
        $fieldName = substr($post->order, strpos($post->order, '[') + 1, -1);
        $tableName = null;
        if (strpos($fieldName, '.')) {
            $fieldTable = explode('.', $fieldName);
            $tableName = $fieldTable[0];
            $fieldName = $fieldTable[1];
        }
        //nowy obiekt sortowania
        $gso = (new GridStateOrder())
            ->setField($fieldName)
            ->setTableName($tableName);
        //kalkulacja metody sortowania
        switch ($post->method) {
            //poprzednia metoda to DESC - usuwanie sortowania
            case 'orderDesc':
                return $gso->unsetMethod();
            //poprzednia metoda to ASC - zmiana na DESC
            case 'orderAsc':
                return $gso->setMethod('orderDesc');
        }
        //nowe sortowanie - ASC
        return $gso->setMethod('orderAsc');
    }

    /**
     * Przebudowa filtrów
     * @param \Mmi\Http\RequestPost $post
     * @return boolean
     */
    protected function _rebuildFilter(\Mmi\Http\RequestPost $post)
    {
        if (null === $filter = $this->_retrievePostFilter($post)) {
            return;
        }
        //obsługa paginatora
        if ($filter->getField() == '_paginator_') {
            $this->_grid->getState()->setPage(($filter->getValue()) ?: 1);
            return true;
        }
        //usuwa lub dodaje filtr
        $filter->getValue() == '' ? $this->_grid->getState()->removeFilter($filter)->setPage(1) : $this->_grid->getState()->addFilter($filter)->setPage(1);
        return true;
    }

    /**
     * Przebudowa sortowań
     * @param \Mmi\Http\RequestPost $post
     * @return boolean
     */
    protected function _rebuildOrder(\Mmi\Http\RequestPost $post)
    {
        //brak orderu
        if (null === $order = $this->_retrievePostOrder($post)) {
            return;
        }
        null === $order->getMethod() ? $this->_grid->getState()->removeOrder($order) : $this->_grid->getState()->addOrder($order);
        return true;
    }

    /**
     * Renderuje ciało grida
     */
    protected function _render()
    {
        $renderer = new GridRenderer($this->_grid);
        //ustawianie odpowiedzi
        App::$di->get(Response::class)
            ->setTypeJson()
            ->setContent(json_encode(['body' => $renderer->renderHeader() . $renderer->renderBody(), 'paginator' => $renderer->renderFooter()]))
            ->send();
        exit;
    }

}
