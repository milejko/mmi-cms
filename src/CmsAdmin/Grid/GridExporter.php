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
 * Obiekt eksportu danych z grida
 */
class GridExporter
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
     * Parsuje dane do postaci CSV
     */
    public function passCsv()
    {
        $csv = fopen('php://output', 'w');
        //nagłówek CSV
        fputcsv($csv, $this->_getHeader());
        //iteracja po danych
        foreach ($this->_getData() as $data) {
            //zapis linii CSV
            fputcsv($csv, $data);
        }
        fclose($csv);
    }

    /**
     * Wybiera etykiety kolumn CSV
     * @return array
     */
    protected function _getHeader()
    {
        $header = [];
        foreach ($this->_grid->getColumns() as $column) {
            if ($column instanceof Column\IndexColumn || $column instanceof Column\OperationColumn) {
                continue;
            }
            if ($column instanceof Column\CustomColumn && !$column->getExporting()) {
                continue;
            }
            $header[] = $column->getLabel();
        }
        return $header;
    }

    /**
     * Wybiera dane do eksportu (uwzględnia filtry i sortowania)
     * @return array
     */
    protected function _getData()
    {
        //pobranie kolekcji rekordów
        $rawCollection = $this->_grid->getState()
            ->setupQuery($this->_grid->getQuery())
            //eksport maksimum 100k rekordów
            ->limit(100000)
            ->offset(null)
            ->find();
        $exportTable = [];
        //iteracja po kolekcji
        foreach ($rawCollection as $record) {
            $recordTable = [];
            //iteracja po kolumnach
            foreach ($this->_grid->getColumns() as $column) {
                if ('?' === $value = $column->getValueFromRecord($record)) {
                    //jeśli kolumna typu custom i eksportowalna
                    if ($column instanceof Column\CustomColumn && $column->getExporting()) {
                        $recordTable[] = $column->renderCell($record);
                    }
                    continue;
                }
                //kolumna to select ale nie checkbox
                if ($column instanceof Column\SelectColumn && !($column instanceof Column\CheckboxColumn)) {
                    $recordTable[] = html_entity_decode($column->renderCell($record));
                    continue;
                }
                //domyślnie przepisujemy wartość z rekordu
                $recordTable[] = $value;
            }
            $exportTable[] = $recordTable;
        }
        return $exportTable;
    }

}
