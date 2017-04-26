<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

/**
 * Kontroler ajax formularzy
 */
class FormController extends \Mmi\Mvc\Controller
{

    /**
     * Walidacja formularza
     * @return string
     */
    public function validateAction()
    {
        //typ odpowiedzi: plain
        $this->getResponse()->setTypePlain();
        //wyłączenie layoutu
        $this->view->setLayoutDisabled();

        //sprawdzenie obecności obowiązkowych pól w poscie
        if (!$this->getPost()->class || !$this->getPost()->field) {
            return '';
        }
        //nazwa klasy forma
        $className = $this->getPost()->class;
        //klasa rekordu
        $recordClassName = $this->getPost()->recordClass;
        //powoływanie forma
        $form = new $className($recordClassName ? new $recordClassName($this->getPost()->recordId ? $this->getPost()->recordId : null) : null);
        /* @var $form \Mmi\Form\Form */
        //pobieranie elementu do walidacji
        $element = $form->getElement($this->getPost()->field);
        //jeśli brak elementu - wyjście
        if (!$element instanceof \Mmi\Form\Element\ElementAbstract) {
            return '';
        }
        //ustawienie wartości elementu
        $element->setValue(urldecode($this->getPost()->value));
        //walidacja i zwrot wyniku
        if (!$element->isValid()) {
            $this->view->errors = $element->getErrors();
            return;
        }
        //poprawna walidacja
        return '';
    }

}
