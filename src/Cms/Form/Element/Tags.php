<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

use \Cms\Model\TagRelationModel;

/**
 * Element tagi
 */
class Tags extends Select
{

    //szablon początku pola
    CONST TEMPLATE_BEGIN = 'cmsAdmin/form/element/element-abstract/begin';
    //szablon opisu
    CONST TEMPLATE_DESCRIPTION = 'cmsAdmin/form/element/element-abstract/description';
    //szablon końca pola
    CONST TEMPLATE_END = 'cmsAdmin/form/element/element-abstract/end';
    //szablon błędów
    CONST TEMPLATE_ERRORS = 'cmsAdmin/form/element/element-abstract/errors';
    //szablon etykiety
    CONST TEMPLATE_LABEL = 'cmsAdmin/form/element/element-abstract/label';

    /**
     * Konstruktor
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setMultiple()
            ->setValue([]);
    }

    /**
     * Ustawia objekt cms
     * @param string $object
     * @return \Cms\Form\Element\Tags
     */
    public function setObject($object)
    {
        return $this->setOption('object', $object);
    }

    /**
     * Ustawia możliwość dodania nowego taga z pola
     * @return \Cms\Form\Element\Tags
     */
    public function setAddTags()
    {
        return $this->setOption('addTags', true);
    }

    /**
     * Ustawianie tagów na podstawie formularza
     * @return \Cms\Form\Element\Tags
     */
    public function setAutoTagValue()
    {
        //brak rekordu
        if (!$this->_form->hasRecord()) {
            return $this;
        }
        //ustawianie wartości
        $this->setValue((new TagRelationModel($this->getOption('object') ? $this->getOption('object') : $this->_form->getFileObjectName(), $this->_form->getRecord()->getPk()))
                ->getTagRelations());
        //zwrot obiektu
        return $this;
    }

    /**
     * Zapis tagów po zapisie formularza
     */
    public function onFormSaved()
    {
        //zapis tagów
        (new TagRelationModel($this->getOption('object') ? $this->getOption('object') : $this->_form->getFileObjectName(), $this->_form->getRecord()->getPk()))
            ->createTagRelations($this->getValue());
    }

    /**
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        $id = $this->getOption('id');
        $inputId = \str_replace('-', '_', $id);
        //ustawianie wartości
        $this->setAutoTagValue();
        //pobranie czy mozna dodac tag
        $addTags = $this->getOption('addTags') ? 'true' : 'false';
        $view = \Mmi\App\FrontController::getInstance()->getView();
        $view->headLink()->appendStylesheet('/resource/cmsAdmin/css/chosen.min.css');
        $view->headScript()->appendFile('/resource/cmsAdmin/js/chosen.jquery.min.js');
        $view->headScript()->appendScript("
			$(document).ready(function ($) {
				$('#" . $id . "').chosen({			    
				disable_search_threshold:10,
				placeholder_text_multiple:'Wpisz lub wybierz tagi',
				no_results_text:'Tag nieodnaleziony'
			});
			
			var customTagPrefix = '';

			// event 
			$('#" . $inputId . "_chosen input').keyup(function(event) {

				// wiecej niz 3 znaki, entery
				if (this.value && this.value.length >= 3 && (event.which === 13 || event.which === 188)) {

					// podswietlamy
					var highlighted = $('#" . $inputId . "_chosen').find('li.active-result.highlighted').first();

					if (event.which === 13 && highlighted.text() !== '')
					{
						//sprawdzamy czy juz jest dodany
						var customOptionValue = customTagPrefix + highlighted.text();
						$('#" . $id . " option').filter(function () { return $(this).val() == customOptionValue; }).remove();

						var tagOption = $('#" . $id . " option').filter(function () { return $(this).html() == highlighted.text(); });
						tagOption.attr('selected', 'selected');
					}
					// Add the custom tag option
					else
					{
						var customTag = this.value;

						// test czy juz taki tag istnieje
						var tagOption = $('#" . $id . " option').filter(function () { return $(this).html() == customTag; });
						if (tagOption.text() !== '')
						{
							tagOption.attr('selected', 'selected');
						}
						else
						{
							if ( $addTags ){
								var option = $('<option>');
								option.text(this.value).val(customTagPrefix + this.value);
								option.attr('selected','selected');

								//dodanie nowego taga
								$('#" . $id . "').append(option);
							}
						}
					}

					this.value = '';
					$('#" . $id . "').trigger('chosen:updated');
					event.preventDefault();

				}
			});
			});
		");

        $values = is_array($this->getValue()) ? $this->getValue() : [$this->getValue()];

        if ($this->issetOption('multiple')) {
            $this->setName($this->getName() . '[]');
        }

        //nagłówek selecta
        $html = '<select ' . $this->_getHtmlOptions() . '>';
        //generowanie opcji
        foreach ($this->getMultioptions() as $key => $caption) {
            $disabled = '';
            //disabled
            if (strpos($key, ':disabled') !== false && !is_array($caption)) {
                $key = '';
                $disabled = ' disabled="disabled"';
            }
            //dodawanie pojedynczej opcji
            $html .= '<option value="' . $key . '"' . $this->_calculateSelected($key, $values) . $disabled . '>' . $caption . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * przerobienie tablicy + klucz
     * @return array
     */
    public function getValue()
    {
        $arr = [];
        if (!is_array($this->_options['value'])) {
            return [];
        }
        foreach ($this->_options['value'] as $key) {
            $arr[$key] = $key;
        }
        return $arr;
    }

    /**
     * łączenie wartości
     * @return array
     */
    public function getMultioptions()
    {
        $array = [];
        foreach ((new \Cms\Orm\CmsTagQuery)->orderAscId()->findPairs('tag', 'tag') as $k => $t) {
            $array[$k] = $k;
        }

        return array_merge($this->getValue(), $array);
    }

}
