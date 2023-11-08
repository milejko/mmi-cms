<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;

/**
 * Kontroler harmonogramu zadań
 */
class CronController extends \Mmi\Mvc\Controller
{
    private const RETAIN_HISTORICAL_VERSIONS = 3;
    private const HISTORICAL_PACKAGE_SIZE = 1000;
    private const DRAFTS_PACKAGE_SIZE = 1000;
    private const ORPHANS_PACKAGE_SIZE = 1000;

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
     * Czyści stare wersje
     */
    public function versionCleanupAction()
    {
        //dłuższy czas i więcej pamięci
        ini_set('max_execution_time', 12 * 3600);
        ini_set('memory_limit', '2G');
        //usuwanie draftów starszych niz 3 dni
        $deletedArticles = (new CmsCategoryQuery())
            ->whereCmsCategoryOriginalId()->notEquals(null)
            ->andFieldStatus()->equals(CmsCategoryRecord::STATUS_DRAFT)
            ->andFieldDateAdd()->less(date('Y-m-d H:i:s', strtotime('-3 days')))
            ->limit(self::DRAFTS_PACKAGE_SIZE)
            ->delete();
        //pobranie identyfikatorów aktywnych kategorii
        $activeCategoryRecordIds = (new CmsCategoryQuery())
            ->whereStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
            ->orderAsc('RAND()')
            ->limit(self::HISTORICAL_PACKAGE_SIZE)
            ->findPairs('id', 'id');
        //iteracja po identyfikatorach kategorii
        foreach ($activeCategoryRecordIds as $categoryRecordId) {
            //wyszukiwanie wersji historycznych danego artykułu
            $versions = (new CmsCategoryQuery())
                ->whereCmsCategoryOriginalId()->equals($categoryRecordId)
                ->andFieldStatus()->equals(CmsCategoryRecord::STATUS_HISTORY)
                ->andFieldDateAdd()->greater(date('Y-m-d H:i:s', strtotime('-6 months')))
                ->orderAscDateAdd()
                ->find();
            //zliczanie wersji historycznych
            $versionCount = count($versions);
            $counter = 0;
            //iteracja po wersjach
            foreach ($versions as $versionRecord) {
                //pozostało nie więcej wersji niz wymagana do pozostawienia
                if ($versionCount - $counter <= self::RETAIN_HISTORICAL_VERSIONS) {
                    break;
                }
                //usuwanie wersji historycznej
                $versionRecord->delete();
                $counter++;
            }
            $deletedArticles += $counter;
        }
        //komunikat o zakończeniu
        return 'Deleted drafts & historical versions: ' . $deletedArticles;
    }

    /**
     * Usuwa pliki tymczasowe Cms File
     */
    public function deleteOrphansAction()
    {
        //usuwanie plików tmp
        return 'Temporary files deleted: ' . \Cms\Model\File::deleteOrphans(self::ORPHANS_PACKAGE_SIZE);
    }
}
