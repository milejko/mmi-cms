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
 * Element ukrytego pola formularza
 */
class Csrf extends \Mmi\Form\Element\Csrf
{
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
}
