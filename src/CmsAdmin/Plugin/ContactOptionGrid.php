<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

use CmsAdmin\Grid\Column;

/**
 * Grid opcji kontaktu
 */
class ContactOptionGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery(new \Cms\Orm\CmsContactOptionQuery);

        //temat
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('grid.contactOption.name.label'));

        //forward
        $this->addColumn((new Column\TextColumn('sendTo'))
            ->setLabel('grid.contactOption.sendTo.label'));

        //operacje
        $this->addColumn((new Column\OperationColumn())->setEditParams([
                'module' => 'cmsAdmin',
                'controller' => 'contact',
                'action' => 'editSubject',
                'id' => '%id%'
            ])
            ->setDeleteParams([
                'module' => 'cmsAdmin',
                'controller' => 'contact',
                'action' => 'deleteSubject',
                'id' => '%id%'
        ]));
    }

}
