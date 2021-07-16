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

/**
 * Element wielokrotny checkbox
 */
class MultiField extends \Mmi\Form\Element\ElementAbstract
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
        $this->addClass('form-control');
        parent::__construct($name);
    }

    /**
     * Ustawia form macierzysty
     *
     * @param \Mmi\Form\Form $form
     *
     * @return self
     */
    public function setForm(\Mmi\Form\Form $form)
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
    public function isValid()
    {
        $result = true;
        if (!is_array($this->getValue())) {
            return $result;
        }
        foreach ($this->getValue() as $index => $itemValues) {
            foreach ($this->getElements() as $element) {
                $value = $itemValues[$element->getBaseName()] ?? null;
                //waliduje poprawnie jeśli niewymagane, ale tylko gdy niepuste
                if (empty($value) && false === $element->getRequired()) {
                    continue;
                }
                //iteracja po walidatorach
                foreach ($element->getValidators() as $validator) {
                    if ($validator->isValid($value)) {
                        continue;
                    }
                    $result = false;
                    //dodawanie wiadomości z walidatora
                    $this->_elementErrors[$index][$element->getBaseName()][] = $validator->getMessage() ? $validator->getMessage() : $validator->getError();
                }
            }
        }

        //zwrot rezultatu wszystkich walidacji (iloczyn)
        return $result;
    }

    /**
     * Dodawanie elementu formularza z gotowego obiektu
     *
     * @param ElementAbstract $element obiekt elementu formularza
     *
     * @return self
     */
    public function addElement(ElementAbstract $element)
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
    final public function getElements()
    {
        return $this->_elements;
    }

    /**
     * @return string
     */
    public function fetchField()
    {
        $this->view->headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js');
        $this->view->headScript()->appendScript($this->jsScript());

        $this->view->headLink()->appendStylesheet('/resource/cmsAdmin/css/multifield.css');

        return '<div id="' . $this->getId() . '-list" class="' . $this->getClass() . '">' . $this->renderList() . '<a href="#" class="btn btn-primary btn-add" role="button">Dodaj element</a></div>';
    }

    /**
     * @return string
     */
    private function renderList()
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
    private function renderListElement(?array $itemValues = null, string $index = '**')
    {
        $html = '<li class="field-list-item border mb-3 p-3">
            <a href="#" class="btn-toggle" role="button">
                <i class="fa fa-angle-down fa-2"></i>
            </a>
        <section>';

        foreach ($this->getElements() as $element) {
            $element->setId($this->getId() . '-' . $index . '-' . $element->getBaseName());
            $element->setName($this->getBaseName() . '[' . $index . '][' . $element->getBaseName() . ']');
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
    private function jsScript()
    {
        $listElement = addcslashes($this->renderListElement(), "'");
        $listId      = $this->getId() . '-list';

        return <<<html
            $(document).ready(function() {
                let list = $('#$listId > .field-list');
                
                list.find('.ne-error-list').each(function(){
                    if($(this).children().length > 0){
                        toggleTile($(this).closest('.field-list-item'));
                    }
                });

                $(document).off('click', '#$listId > .field-list > li > .btn-remove');
                $(document).on('click', '#$listId > .field-list > li > .btn-remove', function(e) {
                    e.preventDefault();
                    $(this).parent().remove();
                    reindex(list);
                });

                $(document).off('click', '#$listId > .btn-add');
                $(document).on('click', '#$listId > .btn-add', function(e) {
                    e.preventDefault();
                    list.append('$listElement'.replaceAll('**', list.children().length));
                });
                
                $(document).off('click', '#$listId > .field-list > li > .btn-toggle');
                $(document).on('click', '#$listId > .field-list > li > .btn-toggle', function(e) {
                    e.preventDefault();
                    toggleTile($(this).closest('.field-list-item'));
                });

                function reindex(list) {
                    list.children().each(function(i) {
                        let elementsWithId = $('[id]', this);                      
                        elementsWithId.each(function(){
                            $(this).attr('id', $(this).attr('id').replace(/-\d+-/ig, '-' + i + '-'));
                        });

                        let elementsWithFor = $('[for]', this);
                        elementsWithFor.each(function(){
                            $(this).attr('for', $(this).attr('for').replace(/-\d+-/ig, '-' + i + '-'));
                        });

                        let elementsWithName  = $('[name]', this);   
                        elementsWithName.each(function(){
                            $(this).attr('name', $(this).attr('name').replace(/\[\d+\]/ig, '[' + i + ']'));
                        });
                    });
                }
                
                function toggleTile(tile) {
                    tile.toggleClass('active');
                    tile.children('.btn-toggle').children('.fa').toggleClass('fa-angle-up fa-angle-down');
                    
                    tile.siblings().removeClass('active');
                    tile.siblings().children('.btn-toggle').children('.fa').removeClass('fa-angle-up');
                    tile.siblings().children('.btn-toggle').children('.fa').addClass('fa-angle-down');
                }
            });    
html;
    }
}
