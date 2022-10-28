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
use Mmi\Http\Request;
use Mmi\Mvc\View;

/**
 * Obsługa requestu
 */
class CheckboxRequestHandler
{
    /**
     * Obiekt checkboxa
     * @var CheckboxColumn
     */
    protected $_checkbox;

    /**
     * Konstruktor przypina obiekt checkboxa
     * @param CheckboxColumn $checkbox
     */
    public function __construct(CheckboxColumn $checkbox)
    {
        $this->_checkbox = $checkbox;
    }

    /**
     * Obsługa requestu jeśli się pojawił
     */
    public function handleRequest()
    {
        //obsługa danych z POST
        $post = App::$di->get(Request::class)->getPost();
        //brak posta
        if ($post->isEmpty()) {
            return;
        }
        //niedozwolone na ACL (w edycji na polu operacje)
        if ($this->_checkbox->getGrid()->getColumn('_operation_') && !(new AclAllowed(App::$di->get(View::class)))->aclAllowed($this->_checkbox->getGrid()->getColumn('_operation_')->getOption('editParams'))) {
            return;
        }
        if ($this->_changeRecord($post)) {
            exit;
        }
    }

    /**
     * Zwraca obiekt sortowania na podstawie post
     * @param \Mmi\Http\RequestPost $post
     * @return boolean
     */
    protected function _changeRecord(\Mmi\Http\RequestPost $post)
    {
        //brak danych dla tego checkboxa
        if ($post->name != $this->_checkbox->getFormColumnName()) {
            return;
        }
        //brak id
        if (!$post->id) {
            return;
        }
        //wybór rekordu
        $record = $this->_checkbox->getGrid()
            ->getQuery()
            ->findPk($post->id);
        //pole leży w tabeli dołączonej
        if (false !== strpos($fieldName = $this->_checkbox->getName(), '.')) {
            $recordField = explode('.', $this->_checkbox->getName());
            //nadpisanie wartości
            $record = $record->getJoined($recordField[0]);
            $fieldName = $recordField[1];
        }
        //brak property z checkboxa
        if (!property_exists($record, $fieldName)) {
            return;
        }
        //ustawianie property
        $record->$fieldName = ($post->checked == 'true') ? 1 : 0;
        return $record->save();
    }
}
