<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid\Column;

use Cms\Mvc\ViewHelper\AclAllowed;
use Mmi\App\App;
use Mmi\Mvc\View;

/**
 * Klasa Columnu indeksującego
 *
 * @method IndexColumn setIndex($index) ustawia index
 * @method integer getIndex() pobiera wartość indeksu
 * @method self setName($name) ustawia nazwę pola
 * @method string getName() pobiera nazwę pola
 * @method self setLabel($label) ustawia labelkę
 * @method string getLabel() pobiera labelkę
 */
class OperationColumn extends ColumnAbstract
{
    /**
     * Domyślny label
     */
    public const LABEL = 'grid.shared.operation.label';

    /**
     * Konstruktor ustawia domyślny label
     * pole bez nazwy
     */
    public function __construct()
    {
        //ustawia domyślne parametry
        $this->setLabel(self::LABEL)
            ->setEditParams()
            ->setDeleteParams();
        //ustawia nazwę na _operation_
        parent::__construct('_operation_');
    }

    /**
     * Ustawia parametry linku edycyjnego
     * ['action' => 'edit', 'id' => '%id%']
     * %pole% zastępowany jest przez $record->pole
     *
     * @param array $params
     * @param string $hashTarget część url po #
     * @return OperationColumn
     */
    public function setEditParams(array $params = ['action' => 'edit', 'id' => '%id%'], $hashTarget = '')
    {
        return $this
            ->setOption('editHashTarget', $hashTarget)
            ->setOption('editParams', $params);
    }

    /**
     * Ustawia parametry linku usuwającego
     * ['action' => 'delete', 'id' => '%id%']
     * %pole% zastępowany jest przez $record->pole
     *
     * @param array $params
     * @param string $hashTarget część url po #
     * @return OperationColumn
     */
    public function setDeleteParams(array $params = ['action' => 'delete', 'id' => '%id%'], $hashTarget = '')
    {
        return $this
            ->setOption('deleteHashTarget', $hashTarget)
            ->setOption('deleteParams', $params);
    }

    /**
     * Dodaje dowolny button
     * @param string $iconName
     * @param array $params parametry
     * @param string $hashTarget
     * @return OperationColumn
     */
    public function addCustomButton($iconName, array $params = [], $hashTarget = '')
    {
        $customButtons = is_array($this->getOption('customButtons')) ? $this->getOption('customButtons') : [];
        $customButtons[] = ['iconName' => $iconName, 'params' => $params, 'hashTarget' => $hashTarget];
        return $this->setOption('customButtons', $customButtons);
    }

    /**
     * Renderuje komórkę
     * @param \Mmi\Orm\RecordRo $record
     * @return string
     */
    public function renderCell(\Mmi\Orm\RecordRo $record)
    {
        $html = '<div class="operation-container">';
        //pobieranie parametrów linku edycji
        $editParams = $this->getOption('editParams');
        //pobieranie parametrów linku usuwania
        $deleteParams = $this->getOption('deleteParams');
        //przyciski dodatkowe
        $customButtons = $this->getOption('customButtons');
        //przyciski dodatkowe
        if (!empty($customButtons)) {
            //iteracja po przyciskach
            foreach ($customButtons as $button) {
                //brak uprawnień w ACL
                if (!(new AclAllowed($this->view))->aclAllowed($params = $this->_parseParams($button['params'], $record))) {
                    continue;
                }
                //html przycisku
                $html .= '<a class="operation-button" href="' . $this->view->url($params, false) . rtrim('#' . $button['hashTarget'], '#') . '"><i class="fa fa-' . $button['iconName'] . ' icon-' . $button['iconName'] . '"></i></a>';
            }
        }
        //link edycyjny ze sprawdzeniem ACL
        if (!empty($editParams) && (new AclAllowed($this->view))->aclAllowed($params = $this->_parseParams($editParams, $record))) {
            $html .= '<a class="operation-button" href="' . $this->view->url($params, false) . rtrim('#' . $this->getOption('editHashTarget'), '#') . '"><i class="fa fa-2 fa-pencil "></i></a>';
        }
        //link kasujący ze sprawdzeniem ACL
        if (!empty($deleteParams) && (new AclAllowed($this->view))->aclAllowed($params = $this->_parseParams($deleteParams, $record))) {
            $html .= '<a class="operation-button confirm" href="' . $this->view->url($params, false) . rtrim('#' . $this->getOption('deleteHashTarget'), '#') . '" title="' . $this->view->_('gird.shared.operation.delete.label') . '" class="confirm"><i class="fa fa-2 fa-trash-o "></i></a>';
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * Zwraca tablicę sparsowanych parametrów do linku
     * @param array $params
     * @param \Mmi\Orm\RecordRo $record
     * @return array
     */
    protected function _parseParams(array $params, \Mmi\Orm\RecordRo $record)
    {
        //inicjalizacja parametrów
        $parsedParams = [];
        $matches = [];
        //iteracja po parametrach
        foreach ($params as $key => $param) {
            //parametr %pole%
            if (preg_match('/%([a-zA-Z]+)%/', $param, $matches)) {
                $parsedParams[$key] = $record->{$matches[1]};
                continue;
            }
            //w pozostałych przypadkach przepisanie parametru
            $parsedParams[$key] = $param;
        }
        return $parsedParams;
    }
}
