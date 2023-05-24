<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

use Cms\Validator\Captcha as Validator;
use Mmi\App\FrontController;
use Mmi\App\KernelException;
use Mmi\Filter\StringTrim;

/**
 * Element captcha
 */
class Captcha extends \Mmi\Form\Element\Text
{
    /**
     * Ignorowanie tego pola, pole obowiązkowe, automatyczna walidacja
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this
            ->setLabel('form.element.captcha.label')
            ->addValidator(new Validator(['name' => $name]))
            ->addFilter(new StringTrim())
            ->setRequired()
            ->setIgnore();
    }

    /**
     * Buduje pole
     * @return string
     * @throws KernelException
     */
    public function fetchField()
    {
        $this->setValue('');
        $url = FrontController::getInstance()->getView()
            ->url(
                [
                    'module' => 'cms',
                    'controller' => 'captcha',
                    'action' => 'index',
                    'name' => $this->_options['name']
                ]
            );

        return parent::fetchField() . '<div class="image"><img src="' . $url . '" alt="" /></div>';
    }
}
