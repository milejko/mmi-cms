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
            ->setLabel('grid.cron.name.label'));

        //opis
        $this->addColumn((new Column\TextColumn('description'))
            ->setLabel('grid.cron.description.label'));

        //crontab
        $this->addColumn((new Column\CustomColumn('crontab'))
            ->setLabel('grid.cron.crontab.label')
            ->setTemplateCode('{$record->minute} {$record->hour} {$record->dayOfMonth} {$record->month} {$record->dayOfWeek}'));

        //data dodania
        $this->addColumn((new Column\DateTimeColumn('dateAdd'))
            ->setLabel('grid.cron.dateAdd.label'));

        //ostatnie wywołanie
        $this->addColumn((new Column\DateTimeColumn('dateLastExecute'))
            ->setLabel('grid.cron.dateLastExecute.label'));

        //aktywny
        $this->addColumn((new Column\CheckboxColumn('active'))
            ->setLabel('grid.cron.active.label'));

        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
