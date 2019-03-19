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
 * Element captcha
 */
class Captcha extends \Mmi\Form\Element\ElementAbstract
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

    protected $_renderingOrder = ['fetchBegin', 'fetchLabel', 'fetchField', 'fetchDescription', 'fetchEnd'];

    /**
     * Ignorowanie tego pola, pole obowiązkowe, automatyczna walidacja
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setIgnore()
            ->setPlaceholder('form.element.captcha.placeholder')
            ->addClass('form-control')
            ->setRequired()
            ->addValidator(new \Cms\Validator\Captcha(['name' => $name]));
    }

    /**
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        $this->setValue('');
        $view = \Mmi\App\FrontController::getInstance()->getView();
        $html = '<div class="image"><img src="' . $view->url(['module' => 'cms', 'controller' => 'captcha', 'action' => 'index', 'name' => $this->_options['name']]) . '" alt="" /></div>';
        $html .= '<div class="input"><input ';
        $html .= 'type="text"' . $this->_getHtmlOptions() . '/></div>';
        return $html;
    }

}
