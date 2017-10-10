<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Form\Element;

class Role extends \Mmi\Form\Form
{

    public function init()
    {

        $this->addElement((new Element\Text('name'))
            ->setLabel('nazwa roli')
            ->addValidator(new \Mmi\Validator\StringLength([3, 64])));

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('utwórz nową rolę'));
    }

}
