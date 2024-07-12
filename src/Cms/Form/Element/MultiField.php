<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Cms\Form\Element;

use Mmi\Form\Element\ElementAbstract;
use Mmi\Form\Form;

/**
 * Element wielokrotny checkbox
 * @method ?string getClass()
 */
class MultiField extends ElementAbstract
{
    public const IS_ACTIVE = 'isActive';
    //szablon początku pola
    public const TEMPLATE_BEGIN = 'cmsAdmin/form/element/element-abstract/begin';
    //szablon opisu
    public const TEMPLATE_DESCRIPTION = 'cmsAdmin/form/element/element-abstract/description';
    //szablon końca pola
    public const TEMPLATE_END = 'cmsAdmin/form/element/element-abstract/end';
    //szablon błędów
    public const TEMPLATE_ERRORS = 'cmsAdmin/form/element/element-abstract/errors';
    //szablon etykiety
    public const TEMPLATE_LABEL = 'cmsAdmin/form/element/element-abstract/label';
    //pliki js i css
    protected const JQUERY_URL = '/resource/cmsAdmin/js/jquery/jquery.js';
    protected const MULTIFIELD_JS_URL = '/resource/cmsAdmin/js/multifield.js';
    protected const MULTIFIELD_CSS_URL = '/resource/cmsAdmin/css/multifield.css';

    /**
     * Elementy formularza
     *
     * @var ElementAbstract[]
     */
    protected array $_elements = [];

    /**
     * Błędy elementów formularza
     *
     * @var array
     */
    protected array $_elementErrors = [];

    /**
     * Błędy zagnieżdzonych elementów formularza
     *
     * @var array
     */
    protected array $_elementNestedErrors = [];

    /**
     * Konstruktor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setIgnore(false);
        $this
            ->addClass('form-control')
            ->addClass('multifield')
            ->addElement(
                (new Checkbox(self::IS_ACTIVE))
                    ->setLabel('form.multifield.active.label')
            );
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
     * Zdarzenie wywoływane przed zapisem całego formularza
     * @return void
     */
    public function beforeFormSave()
    {
        parent::beforeFormSave();

        foreach ($this->getElements() as $element) {
            $element->beforeFormSave();
        }
    }

    /**
     * Zdarzenie wywoływane po zapisie całego formularza
     * @return void
     */
    public function onFormSaved()
    {
        parent::onFormSaved();

        foreach ($this->getElements() as $element) {
            $element->onFormSaved();
        }
    }

    /**
     * Zdarzenie wywoływane po zapisie rekordu - znane PK, ale niewykonane jeszcze afterSave
     * @return void
     */
    public function onRecordSaved()
    {
        parent::onRecordSaved();

        foreach ($this->getElements() as $element) {
            $element->onRecordSaved();
        }
    }

    /**
     * Waliduje pole
     *
     * @return boolean
     */
    public function isValid(): bool
    {
        $result = true;

        foreach ($this->getValidators() as $validator) {
            if (false === $validator->isValid($this->getValue())) {
                $this->addError($validator->getError());
                $result = false;
            }
        }

        if (false === is_array($this->getValue())) {
            return $result;
        }

        foreach ($this->getValue() as $index => $itemValues) {
            //walidujemy tylko aktywne kafelki
            if (true === $itemValues[self::IS_ACTIVE]) {
                $this->validateItem($index, $itemValues, $result);
            }
        }

        //zwrot rezultatu wszystkich walidacji (iloczyn)
        return $result;
    }

    /**
     * Waliduje pole
     *
     * @param int $parentIndex
     * @return bool
     */
    public function isNestedValid(int $parentIndex): bool
    {
        $result = true;

        if (false === is_array($this->getValue())) {
            return $result;
        }

        foreach ($this->getValue() as $index => $itemValues) {
            //walidujemy tylko aktywne kafelki
            if (true === $itemValues[self::IS_ACTIVE]) {
                $this->validateItem($index, $itemValues, $result, $parentIndex);
            }
        }

        //zwrot rezultatu wszystkich walidacji (iloczyn)
        return $result;
    }

    /**
     * @param int $index
     * @param array $itemValues
     * @param bool $result
     * @param int|null $parentIndex
     */
    protected function validateItem(int $index, array $itemValues, bool &$result, ?int $parentIndex = null): void
    {
        foreach ($this->getElements() as $element) {
            $value = $itemValues[$element->getBaseName()] ?? null;

            //waliduje poprawnie jeśli niewymagane, ale tylko gdy niepuste
            if (empty($value) && false === $element->getRequired()) {
                continue;
            }

            //iteracja po walidatorach
            $this->validateElement($index, $element, $value, $result, $parentIndex);

            //waliduje zagnieżdżone pole multifield
            if ($element instanceof self) {
                $this->validateMultifieldElement($element, $value, $result, $index);
            }
        }
    }

    /**
     * @param Multifield $element
     * @param array|null $value
     * @param bool $result
     * @param int $parentIndex
     */
    private function validateMultifieldElement(Multifield $element, ?array $value, bool &$result, int $parentIndex): void
    {
        $element->setValue($value);

        if (false === $element->isNestedValid($parentIndex)) {
            $result = false;
        }
    }

    /**
     * @param int $index
     * @param ElementAbstract $element
     * @param                 $value
     * @param bool $result
     * @param int|null $parentIndex
     */
    protected function validateElement(int $index, ElementAbstract $element, $value, bool &$result, ?int $parentIndex = null): void
    {
        foreach ($element->getValidators() as $validator) {
            if ($validator->isValid($value)) {
                continue;
            }

            $result = false;

            // dodawanie wiadomości z walidatora
            if (null !== $parentIndex) {
                $this->_elementNestedErrors[$parentIndex][$index][$element->getBaseName()][] = $validator->getError();
                continue;
            }

            $this->_elementErrors[$index][$element->getBaseName()][] = $validator->getError();
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
     * Pobranie elementu multifielda
     *
     * @param string $name nazwa elementu
     * @return ElementAbstract
     */
    final public function getElement($name)
    {
        return isset($this->_elements[$name]) ? $this->_elements[$name] : null;
    }

    protected function addScriptsAndLinks(): void
    {
        $this->view->headScript()->prependFile(self::JQUERY_URL);
        $this->view->headScript()->appendScript($this->jsScript());
        $this->view->headScript()->appendFile(self::MULTIFIELD_JS_URL);
        $this->view->headLink()->appendStylesheet(self::MULTIFIELD_CSS_URL);
    }

    /**
     * @return string
     */
    public function fetchField(): string
    {
        $this->addScriptsAndLinks();

        return '
            <a href="#" class="btn-toggle-all" role="button">
                <span>Rozwiń wszystkie</span> <i class="fa fa-angle-down fa-2"></i>
            </a>
            <div id="' . $this->getId() . '-list" class="' . $this->getClass() . '">
                ' . $this->renderList() . '
                <a href="#" class="btn btn-primary btn-add" role="button" data-template="' . $this->getDeclaredName() . '">Dodaj element</a>
            </div>';
    }

    /**
     * @return string
     */
    protected function renderList(): string
    {
        $html = '<ul class="list-unstyled field-list sortable">';
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
     * @param string $index
     *
     * @return string
     */
    protected function renderListElement(?array $itemValues = null, string $index = '**'): string
    {
        $html = '
        <li class="field-list-item border mb-3 p-3">
            <div class="icons">
                <a href="#" class="btn-toggle" role="button">
                    <i class="fa fa-angle-down fa-6"></i>
                </a>
            </div>
            ' . $this->renderListSection($itemValues, $index) . '
            <div class="icons">
                <a href="#" class="sortable-handler" role="button">
                    <i class="fa fa-arrows fa-2"></i>
                </a>
                <a href="#" class="btn-active" role="button">
                    <i class="fa fa-eye fa-2"></i>
                </a>
                <a href="#" class="btn-remove" role="button">
                    <i class="fa fa-trash-o fa-2"></i>
                </a>
            </div>
        </li>';

        return trim(preg_replace('/\r|\n|\s\s+/', ' ', $html));
    }

    /**
     * Renderer sekcji formularza
     *
     * @param array|null $itemValues
     * @param string $index
     *
     * @return string
     */
    protected function renderListSection(?array $itemValues, string $index): string
    {
        $content = '';

        foreach ($this->getElements() as $element) {
            $element->setId($this->getId() . '-' . $index . '-' . $element->getBaseName());
            $element->setName($this->getName() . '[' . $index . '][' . $element->getBaseName() . ']');
            $element->setValue($itemValues[$element->getBaseName()] ?? null);
            $element->setErrors($this->_elementErrors[$index][$element->getBaseName()] ?? []);

            if ($element instanceof self) {
                $element->_elementErrors = $element->_elementNestedErrors[$index] ?? [];
            }

            if ('**' === $index) {
                $element->setId(preg_replace('/\-(\d+|\*\*)\-(.+\-\*\*)/', '-##-$2', $element->getId()));
                $element->setName(preg_replace('/\[(\d+|\*\*)\](\[.+\]\[\*\*\])/', '[##]$2', $element->getName()));
            }

            if ($element instanceof Checkbox) {
                $element->setChecked((bool)$element->getValue());
            }

            $content .= $element->__toString();
        }

        return '<section>' . $content . '</section>';
    }

    /**
     * @return string
     */
    protected function jsScript(): string
    {
        $listElement = addcslashes($this->renderListElement(), "'");
        $listType = $this->getDeclaredName();

        return <<<html
            $(document).ready(function() {
                multifieldListItemTemplate['$listType'] = '$listElement';
            });
        html;
    }

    protected function getDeclaredName(): string
    {
        $name = $this->getBaseName();

        if (false !== strpos($name, '[')) {
            $name = substr($name, strpos($name, '['));
            $name = str_replace(['[', ']'], '', $name);
        }

        return $name;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value): self
    {
        $this->normalizeValues($value, $this->getElements());

        return parent::setValue($value);
    }

    /**
     * @param array|null $value
     * @param array|null $elements
     * @return void
     */
    private function normalizeValues(?array &$value, ?array $elements): void
    {
        if (!is_array($value)) {
            return;
        }

        foreach ($value as $key => $itemValues) {
            foreach ($elements as $elementKey => $element) {
                if ($element->getElements()) {
                    $this->normalizeValues($value[$key][$elementKey], $element->getElements());
                }

                if ($element instanceof Checkbox) {
                    $value[$key][$element->getBaseName()] = (bool)($value[$key][$element->getBaseName()] ?? false);
                    continue;
                }

                foreach ($element->getFilters() as $filter) {
                    $value[$key][$element->getBaseName()] = $filter->filter($value[$key][$element->getBaseName()] ?? null);
                }
            }
        }
    }
}
