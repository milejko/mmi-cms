<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Cms\Form\Element;

use Mmi\Form\Element\ElementAbstract;
use Mmi\Form\Form;

/**
 * Element wielokrotny checkbox
 */
class MultiField extends ElementAbstract
{
    //szablon początku pola
    const TEMPLATE_BEGIN = 'cmsAdmin/form/element/element-abstract/begin';
    //szablon opisu
    const TEMPLATE_DESCRIPTION = 'cmsAdmin/form/element/element-abstract/description';
    //szablon końca pola
    const TEMPLATE_END = 'cmsAdmin/form/element/element-abstract/end';
    //szablon błędów
    const TEMPLATE_ERRORS = 'cmsAdmin/form/element/element-abstract/errors';
    //szablon etykiety
    const TEMPLATE_LABEL = 'cmsAdmin/form/element/element-abstract/label';
    //pliki js i css
    const JQUERY_URL         = '/resource/cmsAdmin/js/jquery/jquery.js';
    const MULTIFIELD_JS_URL  = '/resource/cmsAdmin/js/multifield.js';
    const MULTIFIELD_CSS_URL = '/resource/cmsAdmin/css/multifield.css';

    /**
     * Elementy formularza
     *
     * @var ElementAbstract[]
     */
    protected $_elements = [];

    /**
     * Błędy elementów formularza
     *
     * @var array
     */
    private $_elementErrors = [];

    /**
     * Konstruktor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this
            ->addClass('form-control')
            ->addClass('multifield');
    }

    /**
     * Ustawia form macierzysty
     *
     * @param Form $form
     *
     * @return self
     */
    public function setForm(Form $form): self
    {
        parent::setForm($form);

        foreach ($this->getElements() as $element) {
            $element->setForm($form);
        }

        return $this;
    }

    /**
     * Waliduje pole
     *
     * @return boolean
     */
    public function isValid(): bool
    {
        $result = true;
        if (false === is_array($this->getValue())) {
            return $result;
        }

        foreach ($this->getValue() as $index => $itemValues) {
            $this->validateItem($index, $itemValues, $result);
        }

        //zwrot rezultatu wszystkich walidacji (iloczyn)
        return $result;
    }

    /**
     * @param int   $index
     * @param array $itemValues
     * @param bool  $result
     */
    private function validateItem(int $index, array $itemValues, bool &$result): void
    {
        foreach ($this->getElements() as $element) {
            $value = $itemValues[$element->getBaseName()] ?? null;

            //waliduje zagnieżdżone pole multifield
            if ($element instanceof self) {
                $this->validateMultifieldElement($element, $value, $result);
                continue;
            }

            //waliduje poprawnie jeśli niewymagane, ale tylko gdy niepuste
            if (empty($value) && false === $element->getRequired()) {
                continue;
            }

            //iteracja po walidatorach
            $this->validateElement($index, $element, $value, $result);
        }
    }

    /**
     * @param ElementAbstract $element
     * @param array|null      $value
     * @param bool            $result
     */
    private function validateMultifieldElement(ElementAbstract $element, ?array $value, bool &$result): void
    {
        $element->setValue($value);

        if (false === $element->isValid()) {
            $result = false;
        }
    }

    /**
     * @param int               $index
     * @param ElementAbstract   $element
     * @param array|string|null $value
     * @param bool              $result
     */
    private function validateElement(int $index, ElementAbstract $element, array|string|null $value, bool &$result): void
    {
        foreach ($element->getValidators() as $validator) {
            if ($validator->isValid($value)) {
                continue;
            }

            $result = false;
            //dodawanie wiadomości z walidatora
            $this->_elementErrors[$index][$element->getBaseName()][] = $validator->getMessage() ? $validator->getMessage() : $validator->getError();
        }
    }

    /**
     * Dodawanie elementu formularza z gotowego obiektu
     *
     * @param ElementAbstract $element obiekt elementu formularza
     *
     * @return self
     */
    public function addElement(ElementAbstract $element): self
    {
        //ustawianie opcji na elemencie
        if ($this->_form) {
            $element->setForm($this->_form);
        }
        $this->_elements[$element->getBaseName()] = $element;

        return $this;
    }

    /**
     * Pobranie elementów formularza
     *
     * @return ElementAbstract[]
     */
    final public function getElements(): array
    {
        return $this->_elements;
    }

    /**
     * @return string
     */
    public function fetchField(): string
    {
        $this->view->headScript()->prependFile(self::JQUERY_URL);
        $this->view->headScript()->appendScript($this->jsScript());
        $this->view->headScript()->appendFile(self::MULTIFIELD_JS_URL);

        $this->view->headLink()->appendStylesheet(self::MULTIFIELD_CSS_URL);

        return '<div id="' . $this->getId() . '-list" class="' . $this->getClass() . '">
            <a href="#" class="btn-toggle" role="button">
                <span>Rozwiń wszystkie</span> <i class="fa fa-angle-down fa-2"></i>
            </a>
            ' . $this->renderList() . '
            <a href="#" class="btn btn-primary btn-add" role="button">Dodaj element</a>
            </div>';
    }

    /**
     * @return string
     */
    private function renderList(): string
    {
        $html = '<ul class="list-unstyled field-list">';

        if (is_array($this->getValue())) {
            $index = 0;
            foreach ($this->getValue() as $itemValues) {
                if (is_array($itemValues)) {
                    $html .= $this->renderListElement($itemValues, $index++);
                }
            }
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Renderer pol formularza
     *
     * @param array|null $itemValues
     * @param string     $index
     *
     * @return string
     */
    private function renderListElement(?array $itemValues = null, string $index = '**'): string
    {
        $html = '<li class="field-list-item border mb-3 p-3">
            <a href="#" class="btn-toggle" role="button">
                <i class="fa fa-angle-down fa-2"></i>
            </a>
        <section>';

        foreach ($this->getElements() as $element) {
            $element->setId($this->getId() . '-' . $index . '-' . $element->getBaseName());
            $element->setName($this->getName() . '[' . $index . '][' . $element->getBaseName() . ']');
            $element->setValue($itemValues[$element->getBaseName()] ?? null);
            $element->setErrors($this->_elementErrors[$index][$element->getBaseName()] ?? []);

            if ($element instanceof Checkbox) {
                $element->getValue() ? $element->setChecked() : $element->setChecked(false);
            }

            $html .= $element->__toString();
        }

        $html .= '</section>
            <a href="#" class="btn-remove" role="button">
                <i class="fa fa-trash-o fa-2"></i>
            </a>
        </li>';

        return trim(preg_replace('/\r|\n|\s\s+/', ' ', $html));
    }

    /**
     * @return string
     */
    private function jsScript(): string
    {
        $listElement = addcslashes($this->renderListElement(), "'");
        $listId      = $this->getId() . '-list';

        return <<<html
            $(document).ready(function() {
                let list = $('#$listId > .field-list');

                $(document).off('click', '#$listId > .btn-add');
                $(document).on('click', '#$listId > .btn-add', function(e) {
                    e.preventDefault();
                    $(list).append('$listElement'.replaceAll('**', list.children().length));
                    $(list).children('.field-list-item').last().find('.select2').select2();
                });
            });    
        html;
    }
}
