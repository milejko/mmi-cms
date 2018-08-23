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
 * Grid harmonogramu
 */
class CronGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery(new \Cms\Orm\CmsCronQuery);

        //nazwa
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('nazwa'));

        //opis
        $this->addColumn((new Column\TextColumn('description'))
            ->setLabel('opis'));

        //crontab
        $this->addColumn((new Column\CustomColumn('crontab'))
            ->setLabel('crontab')
            ->setTemplateCode('{$record->minute} {$record->hour} {$record->dayOfMonth} {$record->month} {$record->dayOfWeek}'));

        //data dodania
        $this->addColumn((new Column\RangeColumn('dateAdd'))
            ->setLabel('data dodania'));

        //ostatnie wywołanie
        $this->addColumn((new Column\TextColumn('dateLastExecute'))
            ->setLabel('ostatnie wywołanie'));

        //aktywny
        $this->addColumn((new Column\CheckboxColumn('active'))
            ->setLabel('włączony'));

        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
