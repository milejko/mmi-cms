<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

/**
 * Element uploader
 * 
 * Metody add
 * @method self addClass($className) dodaje klasę HTML
 * @method self addFilter(\Mmi\Filter\FilterAbstract $filter) dodaje filtr
 * @method self addError($error) dodaje błąd
 * 
 * Settery
 * @method self setName($name) ustawia nazwę
 * @method self setValue($value) ustawia wartość
 * @method self setId($id) ustawia identyfikator
 * @method self setPlaceholder($placeholder) ustawia placeholder pola
 * @method self setDescription($description) ustawia opis
 * @method self setIgnore($ignore = true) ustawia ignorowanie
 * @method self setDisabled($disabled = true) ustawia wyłączone
 * @method self setReadOnly($readOnly = true) ustawia tylko do odczytu
 * @method self setLabel($label) ustawia labelkę
 * @method self setRequiredAsterisk($asterisk = '*') ustawia znak gwiazdki
 * @method self setRequired($required = true) ustawia wymagalność
 * @method self setLabelPostfix($labelPostfix) ustawia postfix labelki
 * @method self setForm(\Mmi\Form\Form $form) ustawia formularz
 */
class Uploader extends File
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
     * Dodaje dozwolony typ pliku
     * @param string $type
     * @return \Cms\Form\Element\Uploader
     */
    public function addAllowedType($type)
    {
        $types = $this->getOption('types');
        //brak typów - tworzenie
        if (null === $types) {
            $this->setOption('types', [$type => $type]);
        }
        $types[$type] = $type;
        return $this->setOption('types', $types);
    }

    /**
     * Ustawia sciezke do opcjonalnego pliku JS w ramce
     * @param string $path
     * @return \Cms\Form\Element\Uploader
     */
    public function addJsFile($path)
    {
        return $this->setOption('js', $path);
    }

    /**
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        $object = 'library';
        $objectId = null;
        if ($this->_form->hasRecord()) {
            $object = $this->_form->getFileObjectName();
            $objectId = $this->_form->getRecord()->getPk();
        }
        if (!$objectId) {
            $object = 'tmp-' . $object;
            $objectId = \Mmi\Session\Session::getNumericId();
        }
        return '<iframe frameborder="0" src="' . \Mmi\App\FrontController::getInstance()->getView()->url([
                'module' => 'cms',
                'controller' => 'file',
                'action' => 'uploader',
                'class' => str_replace('\\', '', get_class($this->_form)),
                'object' => $object,
                'objectId' => $objectId,
                'types' => $this->getOption('types'),
                'js' => $this->getOption('js')
            ]) . '"
			style="border-style: none;
			border: none;
			border-width: initial;
			border-color: initial;
			border-image: initial;
			padding: 0;
			margin: 0;
			overflow-x: hidden;
			overflow-y: auto;
			height: 180px;
			width: 100%;"></iframe>';
    }

}
