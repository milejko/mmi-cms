<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

use Cms\Orm\CmsTagQuery;
use Cms\Orm\CmsTagRecord;
use Cms\Orm\CmsTagRelationQuery;
use Cms\Orm\CmsTagRelationRecord;

/**
 * Element tagi
 * @method self setClass(string $class)
 * @method self setAutoTagValue()
 */
class Tags extends Select
{
    private string $object;
    private string $objectId;
    private ?string $scope = null;

    /**
     * Konstruktor
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setMultiple()
            ->setValue([]);
    }

    public function setObject(string $object): self
    {
        $this->object = $object;
        return $this;
    }

    public function setObjectId(string $objectId): self
    {
        $this->objectId = $objectId;
        return $this;
    }

    public function setScope(string $scope): self
    {
        $this->scope = $scope;
        return $this;
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
     * Ustawia maksymalna mozliwa ilosc tagow
     * @return \Cms\Form\Element\Tags
     */
    public function setMaxTags(?int $number)
    {
        return $this->setOption('maxTags', $number);
    }

    /**
     * Ustawia rozwijanie do gory
     * @return mixed
     */
    public function setDropUp()
    {
        return $this->setClass('dropUpchosen');
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
        $maxTags = $this->getOption('maxTags');
        $this->view->headLink()->appendStylesheet('/resource/cmsAdmin/css/chosen.min.css');
        $this->view->headScript()->appendFile('/resource/cmsAdmin/js/chosen.jquery.min.js');
        $this->view->headScript()->appendScript("
			$(document).ready(function ($) {
				$('#" . $id . "').chosen({			    
				disable_search_threshold:10,
				" . (is_int($maxTags) ? 'max_selected_options:' . $maxTags . ',' : '') . "
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

    public function getValue()
    {
        return is_array($this->_options['value']) ? $this->_options['value'] : [];
    }

    /**
     * Po przed zapisem rekordu
     */
    public function beforeFormSave()
    {
        if (!$this->getOption('addTags')) {
            return;
        }

        $nonExistentTags = array_diff($this->getValue(), array_flip($this->getMultioptions()));
        $lang = $this->_form->getRecord()->lang;
        $value = $this->getValue();

        foreach ($nonExistentTags as $id => $tag) {
            $newTag = new CmsTagRecord();
            $newTag->scope = $this->scope;
            $newTag->tag = $tag;
            $newTag->lang = $lang;
            $newTag->save();

            unset($value[$id]);
            $value[] = $newTag->id;
        }

        $this->setValue($value);
    }

    /**
     * Po zapisie rekordu
     */
    public function onRecordSaved()
    {
        $object = $this->object ?? $this->_form->getRecordClass();
        $objecId = $this->objectId ?? $this->_form->getRecord()->id;
        //tag relations cleanup
        (new CmsTagRelationQuery())
            ->whereObject()->equals($object)
            ->andFieldObjectId()->equals($objecId)
            ->delete();
        foreach ($this->getValue() as $tagValue) {
            $newTagRelation = new CmsTagRelationRecord();
            $newTagRelation->object = $object;
            $newTagRelation->objectId = $objecId;
            $newTagRelation->cmsTagId = $tagValue;
            $newTagRelation->save();
        }
    }

    /**
     * łączenie wartości
     * @return array
     */
    public function getMultioptions()
    {
        return (new CmsTagQuery())
            ->whereScope()->equals($this->scope)
            ->orderAscTag()->findPairs('id', 'tag');
    }
}
