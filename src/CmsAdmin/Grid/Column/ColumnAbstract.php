<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid\Column;

use Mmi\App\App;
use Mmi\Mvc\View;

/**
 * Abstrakcyjna klasa Columnu
 *
 * @method self setName($name) ustawia nazwę pola
 * @method string getName() pobiera nazwę pola
 * @method self setLabel($label) ustawia labelkę
 * @method string getLabel() pobiera labelkę
 *
 * @method self setFilterMethodBetween() ustawia metodę filtracji na "pomiędzy"
 * @method self setFilterMethodEquals() ustawia metodę filtracji na równość
 * @method self setFilterMethodLike() ustawia metodę filtracji na podobny
 * @method self setFilterMethodSearch() ustawia metodę filtracji na wyszukaj
 * @method self setFilterMethodNull() ustawia metodę filtracji na równe/różne null
 */
abstract class ColumnAbstract extends \Mmi\OptionObject
{

    const TEMPLATE_FILTER = 'cmsAdmin/grid/filter/text';
    const TEMPLATE_LABEL = 'cmsAdmin/grid/label';

    /**
     * Obiekt grida
     * @var \CmsAdmin\Grid\Grid
     */
    protected $_grid;

    /**
     * @var View
     */
    protected $view;

    /**
     * Konstruktor ustawia nazwę
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
        $this->view = App::$di->get(View::class);
    }

    /**
     * Zwraca nazwę dla Columnu formularza
     * @return string
     */
    public function getFormColumnName()
    {
        return $this->_grid->getClass() . '[' . $this->getName() . ']';
    }

    /**
     * Ustawia grida macieżystego
     * @param \CmsAdmin\Grid\Grid $grid
     * @return ColumnAbstract
     */
    public function setGrid(\CmsAdmin\Grid\Grid $grid)
    {
        $this->_grid = $grid;
        return $this;
    }

    /**
     * Pobiera obiekt grida
     * @return \CmsAdmin\Grid\Grid
     */
    public function getGrid()
    {
        return $this->_grid;
    }

    /**
     * Renderuje labelkę kolumny
     * @return string
     */
    public function renderLabel()
    {
        //zwrot labelki
        $this->view->_column = $this;
        return $this->view->renderTemplate(static::TEMPLATE_LABEL);
    }

    /**
     * Renderuje filtrację pola
     * @return string
     */
    public function renderFilter()
    {
        //zwrot filtra
        $this->view->_column = $this;
        return $this->view->renderTemplate(static::TEMPLATE_FILTER);
    }

    /**
     * Renderuje komórkę pola dla danego rekordu
     * @param \Mmi\Orm\RecordRo $record
     * @return string
     */
    abstract public function renderCell(\Mmi\Orm\RecordRo $record);

    /**
     * Wybiera wartość z rekordu
     * @param \Mmi\Orm\RecordRo $record
     * @return string
     */
    public function getValueFromRecord(\Mmi\Orm\RecordRo $record)
    {
        if (!$this->isFieldInRecord()) {
            return '?';
        }
        if (strpos($this->getName(), '.')) {
            $table = explode('.', $this->getName());
            return $record->getJoined($table[0])->{$table[1]};
        }
        return $record->{$this->getName()};
    }

    /**
     * Zwraca filtr dla pola
     * @return string
     */
    public function getFilterValue($param = null)
    {
        //iteracja po filtrach w gridzie
        foreach ($this->_grid->getState()->getFilters() as $filter) {
            //znaleziony filtr dla tego pola z tabelą
            if ($filter->getTableName() . '.' . $filter->getField() == $this->getName()) {
                //zwrot wartości filtra
                return $filter->getValue();
            }
            //znaleziony filtr dla tego pola (bez tabeli)
            if (!$filter->getTableName() && $filter->getField() == $this->getName()) {
                /** @var \stdClass $value */
                $valueObj = json_decode($filter->getValue());
                if (null !== $param && is_object($valueObj) && property_exists($valueObj, $param)) {
                    return $valueObj->{$param};
                }
                //zwrot wartości filtra
                return $filter->getValue();
            }
        }
    }

    /**
     * Sprawdza istnienie pola w rekordzie
     * @return boolean
     */
    public function isFieldInRecord()
    {
        //zażądany join
        if (strpos($this->getName(), '.')) {
            return true;
        }
        //sorawdzenie w rekordzie
        return property_exists($this->_grid->getQuery()->getRecordName(), $this->getName());
    }

    /**
     * Zwraca sortowanie dla pola
     * @return string
     */
    public function getOrderMethod()
    {
        //iteracja po sortowaniach w gridzie
        foreach ($this->_grid->getState()->getOrder() as $order) {
            //znalezione sortowanie tego pola
            //gdy jest podana nazwa tabeli
            if ($order->getTableName()) {
                if ($order->getTableName() . '.' . $order->getField() == $this->getName()) {
                    //zwrot metody sortowania
                    return $order->getMethod();
                }
            } else { //bez tabeli
                if ($order->getField() == $this->getName()) {
                    //zwrot metody sortowania
                    return $order->getMethod();
                }
            }
        }
    }

    /**
     * Obsługa setFilterMethod
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function __call($name, $params)
    {
        $matches = [];
        //obsługa getterów
        if (preg_match('/^setFilterMethod([a-zA-Z0-9]+)/', $name, $matches)) {
            return $this->setOption('method', lcfirst($matches[1]));
        }
        return parent::__call($name, $params);
    }

}
