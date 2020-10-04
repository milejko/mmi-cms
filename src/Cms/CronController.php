<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

/**
 * Kontroler harmonogramu zadań
 */
class CronController extends \Mmi\Mvc\Controller
{

    /**
     * Uruchomienie crona
     * @return string
     */
    public function indexAction()
    {
        \Cms\Model\Cron::run();
        return 'OK';
    }

    /**
     * Wysyłka maili
     */
    public function sendMailAction()
    {
        $this->view->result = \Cms\Model\Mail::send();
    }

    /**
     * Czyszczenie kolejki mailowej
     */
    public function cleanMailQueueAction()
    {
        $this->view->cleared = \Cms\Model\Mail::clean();
    }

    /**
     * Agregator statystyk
     */
    public function agregateAction()
    {
        $this->view->result = \Cms\Model\Stat::agregate();
    }

    /**
     * Czyści stare wersje
     */
    public function versionCleanupAction()
    {
        //dłuższy czas i więcej pamięci
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '2G');
        //usuwanie draftów starszych niz 3 dni
        $drafts = (new \Cms\Orm\CmsCategoryQuery)
            ->whereCmsCategoryOriginalId()->notEquals(null)
            ->andFieldStatus()->equals(\Cms\Orm\CmsCategoryRecord::STATUS_DRAFT)
            ->andFieldDateAdd()->less(date('Y-m-d H:i:s', strtotime('-3 days')))
            ->find();
        //usuwanie wersji roboczych starszych niz 3 dni    
        $versions = (new \Cms\Orm\CmsCategoryQuery)
            ->whereCmsCategoryOriginalId()->notEquals(null)
            ->andFieldStatus()->equals(\Cms\Orm\CmsCategoryRecord::STATUS_HISTORY)
            ->andFieldDateAdd()->less(date('Y-m-d H:i:s', strtotime('-6 months')))
            ->find();
        //usuwa wersje robocze i archiwalne
        return 'Deleted: ' . $drafts->delete() . ' drafts, ' . $versions->delete() . ' historical versions';
    }

    /**
     * Usuwa pliki tymczasowe Cms File
     */
    public function deleteOrphansAction()
    {
        //usuwanie plików tmp
        \Cms\Model\File::deleteOrphans();
        return '';
    }
}
