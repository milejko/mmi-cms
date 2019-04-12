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
        //cleanup
        if (50 == rand(0, 100)) {
            \Cms\Model\Mail::clean();
        }
        $this->view->result = \Cms\Model\Mail::send();
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
        //zapytanie wyszukujące drafty i wersje robocze
        $query = (new \Cms\Orm\CmsCategoryQuery)
            ->whereCmsCategoryOriginalId()->notEquals(null)
            ->andFieldStatus()->notEquals(\Cms\Orm\CmsCategoryRecord::STATUS_ACTIVE);
        //obliczanie z ilu tygodni usunąć
        $weeks = $this->weeks ? intval($this->weeks) : 53;
        if ($weeks > 0) {
            $query->andFieldDateAdd()->less(date('Y-m-d H:i:s', strtotime('-' . $weeks . ' weeks')));
        }
        //usuwa wersje robocze
        return 'Deleted: ' . $query->find()->delete() . ' archival versions';
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
