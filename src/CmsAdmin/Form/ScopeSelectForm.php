<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\App\CmsSkinsetConfig;
use Cms\Form\Element\Select;
use Cms\Form\Element\Submit;
use Cms\Form\Form;
use Mmi\App\App;

/**
 * Formularz wyboru scope/domeny
 */
class ScopeSelectForm extends Form
{
    public const SCOPE_CONFIG_OPTION_NAME = 'scope-option';

    public function init()
    {
        $this->addElement((new Select('scope'))
            ->setValue($this->getOption(self::SCOPE_CONFIG_OPTION_NAME)->getName())
            ->setMultioptions($this->getSkinMultioptions())
        );
    }

    private function getSkinMultioptions(): array
    {
        $options = [null => '---'];
        foreach (App::$di->get(CmsSkinsetConfig::class)->getSkins() as $skin) {
            $options[$skin->getKey()] = $skin->getName();
        }
        return $options;
    }

}
