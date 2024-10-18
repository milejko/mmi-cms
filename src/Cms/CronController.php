<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

use Cms\Form\Element\UploaderElementInterface;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Cms\Orm\CmsFileQuery;

/**
 * Kontroler harmonogramu zadań
 */
class CronController extends \Mmi\Mvc\Controller
{
    private const RETAIN_HISTORICAL_VERSIONS = 3;
    private const BATCH_SIZE = 1000;
    private const TEMP_MAX_AGE = '-30 days';
    private const DRAFT_MAX_AGE = '-30 days';
    private const TRASH_MAX_AGE = '-6 months';
    private const VERSION_MAX_AGE = '-6 months';
    private const DELETE_MESSAGE_TEMPLATE = 'Deleted: %s out of %s';

    public function init()
    {
        //dłuższy czas i więcej pamięci
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '1G');
    }

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
    public function mailQueueCleanupAction()
    {
        $this->view->cleared = \Cms\Model\Mail::clean();
    }

    public function draftCleanupAction()
    {
        $query = (new CmsCategoryQuery())
            ->andFieldStatus()->equals(CmsCategoryRecord::STATUS_DRAFT)
            ->andFieldDateAdd()->less(date('Y-m-d H:i:s', strtotime(self::DRAFT_MAX_AGE)));
        $applicableItems = $query->count();
        //usuwanie draftów
        $query->limit(self::BATCH_SIZE)
            ->delete();
        return sprintf(self::DELETE_MESSAGE_TEMPLATE, $applicableItems < self::BATCH_SIZE ? $applicableItems : self::BATCH_SIZE, $applicableItems);
    }

    /**
     * Usuwa stare artykuły z kosza
     */
    public function trashCleanupAction()
    {
        $query = (new CmsCategoryQuery())
            ->andFieldStatus()->equals(CmsCategoryRecord::STATUS_DELETED)
            ->andFieldDateAdd()->less(date('Y-m-d H:i:s', strtotime(self::TRASH_MAX_AGE)));
        $applicableItems = $query->count();
        //usuwanie z kosza
        $query->limit(self::BATCH_SIZE)
            ->delete();
        return sprintf(self::DELETE_MESSAGE_TEMPLATE, $applicableItems < self::BATCH_SIZE ? $applicableItems : self::BATCH_SIZE, $applicableItems);
    }

    /**
     * Czyści historyczne wersje aktywnych artykułów
     */
    public function versionCleanupAction()
    {
        $deletedArticles = 0;
        //pobranie identyfikatorów aktywnych i usuniętych kategorii w losowej kolejności
        $activeCategoryRecordIds = (new CmsCategoryQuery())
            ->whereStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
            ->orderAsc('RAND()')
            ->limit(self::BATCH_SIZE)
            ->findPairs('id', 'id');
        //iteracja po identyfikatorach kategorii
        foreach ($activeCategoryRecordIds as $categoryRecordId) {
            //wyszukiwanie wersji historycznych danego artykułu
            $versions = (new CmsCategoryQuery())
                ->whereCmsCategoryOriginalId()->equals($categoryRecordId)
                ->andFieldStatus()->equals(CmsCategoryRecord::STATUS_HISTORY)
                ->andFieldDateAdd()->less(date('Y-m-d H:i:s', strtotime(self::VERSION_MAX_AGE)))
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
            //przekroczona ilość jednorazowo kasowanych artykułów
            if ($deletedArticles > self::BATCH_SIZE) {
                break;
            }
        }
        //komunikat o zakończeniu
        return sprintf(self::DELETE_MESSAGE_TEMPLATE, $deletedArticles, '?');
    }

    /**
     * Usuwa pliki tymczasowe Cms File
     */
    public function tempCleanupAction()
    {
        $query = (new CmsFileQuery())->whereObject()->like(UploaderElementInterface::TEMP_OBJECT_PREFIX . '%')
            ->andFieldDateModify()->less(date('Y-m-d H:i:s', strtotime(self::TEMP_MAX_AGE)));
        $applicableItems = $query->count();
        $query->limit(self::BATCH_SIZE)
            ->delete();
        return sprintf(self::DELETE_MESSAGE_TEMPLATE, $applicableItems < self::BATCH_SIZE ? $applicableItems : self::BATCH_SIZE, $applicableItems);
    }
}
