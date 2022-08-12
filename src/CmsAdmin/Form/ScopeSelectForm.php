<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Form\Element\Select;
use Cms\Form\Form;

/**
 * Formularz wyboru scope/domeny
 */
class ScopeSelectForm extends Form
{
    public const OPTION_SELECTED = 'selected';
    public const OPTION_MULTIOPTIONS = 'multioptions';

    public function init()
    {
        $this->addElement((new Select('scope'))
            ->setValue($this->getOption(self::OPTION_SELECTED))
            ->setMultioptions($this->getOption(self::OPTION_MULTIOPTIONS))
        );
    }

}
