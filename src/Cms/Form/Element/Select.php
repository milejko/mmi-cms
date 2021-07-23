<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Cms\Form\Element;

/**
 * Element select
 */
class Select extends \Mmi\Form\Element\Select
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

    //biblioteka Select2
    const SELECT2_JS_URL  = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
    const SELECT2_CSS_URL = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css';
    //pliki js i css
    const SELECT_JS_URL   = '/resource/cmsAdmin/js/select2.js';
    const SELECT_CSS_URL  = '/resource/cmsAdmin/css/select2.css';

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

    public function fetchField()
    {
        $this->view->headScript()->appendFile(self::SELECT2_JS_URL);
        $this->view->headScript()->appendFile(self::SELECT_JS_URL);

        $this->view->headLink()->appendStylesheet(self::SELECT2_CSS_URL);
        $this->view->headLink()->appendStylesheet(self::SELECT_CSS_URL);

        return parent::fetchField();
    }
}
