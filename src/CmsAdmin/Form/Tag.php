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

/**
 * Formularz tagów
 */
class Tag extends \Mmi\Form\Form
{

    public function init()
    {

        //tag
        $this->addElement((new Element\Text('tag'))
            ->setLabel('tag')
            ->setRequired()
            ->addFilter(new \Mmi\Filter\StringTrim)
            ->addValidator(new \Mmi\Validator\StringLength([2, 64]))
            ->addValidator(new \Mmi\Validator\RecordUnique([(new \Cms\Orm\CmsTagQuery), 'tag', $this->getRecord()->id])));

        //zapis
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('zapisz tag'));
    }

}
