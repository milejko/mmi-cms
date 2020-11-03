<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

use Mmi\Http\Request;

/**
 * Kontroler ajax formularzy
 */
class FormController extends \Mmi\Mvc\Controller
{

    /**
     * Walidacja formularza
     * @return string
     */
    public function validateAction(Request $request)
    {
        //typ odpowiedzi: plain
        $this->getResponse()->setTypePlain();
        //wyłączenie layoutu
        $this->view->setLayoutDisabled();

        //sprawdzenie obecności obowiązkowych pól w poscie
        if (!$request->getPost()->class || !$request->getPost()->field) {
            return '';
        }
        //nazwa klasy forma
        $className = $request->getPost()->class;
        //klasa rekordu
        $recordClassName = $request->getPost()->recordClass;
        //powoływanie forma
        $form = new $className($recordClassName ? new $recordClassName($request->getPost()->recordId ? $request->getPost()->recordId : null) : null);
        /* @var $form \Mmi\Form\Form */
        //pobieranie elementu do walidacji
        $element = $form->getElement($request->getPost()->field);
        //jeśli brak elementu - wyjście
        if (!$element instanceof \Mmi\Form\Element\ElementAbstract) {
            return '';
        }
        //ustawienie wartości elementu
        $element->setValue(urldecode($request->getPost()->value));
        //walidacja i zwrot wyniku
        if (!$element->isValid()) {
            $this->view->errors = $element->getErrors();
            return;
        }
        //poprawna walidacja
        return '';
    }

}
