<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form;

use Cms\App\CmsScopeConfig;
use Mmi\App\App;

/**
 * Formularz CMS
 */
abstract class Form extends \Mmi\Form\Form
{
    //szablon rozpoczynający formularz
    public const TEMPLATE_START = 'cmsAdmin/form/start';

    public function getCurrentScope(): string
    {
        return App::$di->get(CmsScopeConfig::class)->getName();
    }
}
