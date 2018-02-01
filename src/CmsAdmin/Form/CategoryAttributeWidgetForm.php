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
 * Formularz widgetu z podpiętymi atrybutami
 * @method \Cms\Orm\CmsCategoryWidgetCategoryRecord getRecord()
 */
class CategoryAttributeWidgetForm extends \Cms\Form\AttributeForm
{

    public function init()
    {

        $this->initAttributes('cmsCategoryWidget', $this->getOption('widgetId'), 'categoryWidgetRelation');

        //csrf - tylko jednokrotne kliknięcie możliwe
        $this->addElement((new Element\Csrf('csrf')));
        
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('zapisz'));
    }

}
