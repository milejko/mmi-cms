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
 * Abstrakcyjna klasa grida
 */
abstract class Grid extends \Mmi\OptionObject
{

    /**
     * Columny grida
     * @var array
     */
    protected $_columns = [];

    /**
     * Obiekt zapytania
     * @var \Mmi\Orm\Query
     */
    protected $_query;

    /**
     * Dane
     * @var \Mmi\Orm\RecordCollection
     */
    protected $_data;

    /**
     * Stan siatki
     * @var GridState
     */
    protected $_state;

    /**
     * Konstruktor
     */
    public function __construct(array $options = [])
    {
        //parametry wejściowe do grida
        $this->setOptions($options);
        //tworzy obiekt stanu
        $this->_state = (new GridState)->setGrid($this);
        //indeks
        $this->addColumn(new Column\IndexColumn);
        $this->init();
        //obsługa zapytań JSON do grida
        (new GridRequestHandler($this))->handleRequest();
    }

    /**
     * Inicjalizacja
     */
    abstract public function init();

    /**
     * Dodaje Column grida
     * @param \CmsAdmin\Grid\Column\ColumnAbstract $column
     * @return Column\ColumnAbstract
     */
    public final function addColumn(Column\ColumnAbstract $column)
    {
        //dodawanie Columnu (nazwa unikalna)
        return $this->_columns[$column->getName()] = $column->setGrid($this);
    }

    /**
     * Pobranie kolumny
     * @return \CmsAdmin\Grid\Column\ColumnAbstract[]
     */
    public final function getColumns()
    {
        return $this->_columns;
    }

    /**
     * Pobiera kolumnę po nazwie
     * @param string $name
     * @return \CmsAdmin\Grid\Column\ColumnAbstract
     */
    public final function getColumn($name)
    {
        //iteracja po kolumnach
        foreach ($this->getColumns() as $column) {
            //nazwa zgodna
            if ($column->getName() == $name) {
                return $column;
            }
        }
    }

    /**
     * Zwraca obiekt stanu
     * @return GridState
     */
    public final function getState()
    {
        return $this->_state;
    }

    /**
     * Pobiera zapytanie
     * @return \Mmi\Orm\Query
     * @throws GridException
     */
    public final function getQuery()
    {
        //brak obiektu zapytania
        if (!$this->_query) {
            throw new GridException('Query not initialized');
        }
        return $this->_query;
    }

    /**
     * Ustawia startowe zapytanie filtrujące
     * @param \Mmi\Orm\Query $query
     * @return \CmsAdmin\Grid\Grid
     */
    public final function setQuery(\Mmi\Orm\Query $query)
    {
        $this->_query = $query;
        return $this;
    }

    /**
     * Pobiera uproszczoną nazwę klasy grida
     * @return string
     */
    public final function getClass()
    {
        return str_replace('\\', '', get_class($this));
    }

    /**
     * Pobiera kolekcję rekordów
     * @return \Mmi\Orm\RecordCollection
     */
    public final function getDataCollection()
    {
        if (null !== $this->_data) {
            return $this->_data;
        }
        //aktualizuje zapytanie i pobiera dane
        return $this->_data = $this->getState()
            ->setupQuery($this->getQuery())
            ->find();
    }

    /**
     * Render grida
     */
    public final function __toString()
    {
        try {
            //rendering grida HTML
            return (new GridRenderer($this))->render();
        } catch (\Exception $e) {
            return $e->getMessage() . ' ' . $e->getTraceAsString();
        }
    }

}
