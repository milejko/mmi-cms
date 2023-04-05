<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

use Cms\Orm\CmsTagQuery;
use CmsAdmin\Grid\Column\OperationColumn;
use CmsAdmin\Grid\Column\TextColumn;

/**
 * Grid tagów
 */
class TagGrid extends \CmsAdmin\Grid\Grid
{
    public const SCOPE_OPTION_NAME = 'scope';

    public function init()
    {
        //zapytanie
        $this->setQuery((new CmsTagQuery())
            ->whereScope()->equals($this->getOption(self::SCOPE_OPTION_NAME)));

        //nazwa taga
        $this->addColumn((new TextColumn('lang'))
            ->setLabel('grid.tag.lang.label'));

        //scope taga
        $this->addColumn((new TextColumn('scope'))
        ->setLabel('grid.tag.scope.label'));

        //nazwa taga
        $this->addColumn((new TextColumn('tag'))
            ->setLabel('grid.tag.tag.label'));

        //operacje
        $this->addColumn((new OperationColumn())->setEditParams([]));
    }
}
