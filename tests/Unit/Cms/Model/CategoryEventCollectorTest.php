<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Unit\Cms\Model;

use Cms\Model;
use Cms\Model\CategoryEventCollector;
use Cms\Orm\CmsCategoryRecord;
use PHPUnit\Framework\TestCase;
use Tests\Mock\Cms\ObservableEventManagerMock;

class CategoryEventCollectorTest extends TestCase
{
    public function testIfEmptyCollectorDoesNotTriggerAnyEvents(): void
    {
        $collector = new CategoryEventCollector($eventManager = new ObservableEventManagerMock);
        self::assertCount(0, $eventManager->getEvents());
        $collector->triggerEvents();
        self::assertCount(0, $eventManager->getEvents());
    }

    public function testIfCollectorIgnoresDraftsAndHistoryRecords(): void
    {
        $collector = new CategoryEventCollector($eventManager = new ObservableEventManagerMock);

        $draft = new CmsCategoryRecord();
        $draft->id = 1;
        $draft->status = CmsCategoryRecord::STATUS_DRAFT;
        $draft->active = 1;

        $history = new CmsCategoryRecord();
        $history->id = 2;
        $history->status = CmsCategoryRecord::STATUS_HISTORY;
        $history->active = 1;

        $collector->collectCategory($draft);
        $collector->collectCategory($history);

        self::assertCount(0, $eventManager->getEvents());

        $collector->triggerEvents();

        self::assertCount(0, $eventManager->getEvents());
    }

    public function testIfCollectorTriggersNecessaryEventsAtTheRightTime(): void
    {
        $collector = new CategoryEventCollector($eventManager = new ObservableEventManagerMock);

        $sampleCategory = new CmsCategoryRecord();
        $sampleCategory->id = 1;
        $sampleCategory->status = CmsCategoryRecord::STATUS_ACTIVE;
        $sampleCategory->active = 1;

        $sampleDeletedCategory = new CmsCategoryRecord();
        $sampleDeletedCategory->id = 2;
        $sampleDeletedCategory->status = CmsCategoryRecord::STATUS_DELETED;
        $sampleDeletedCategory->active = 1;

        $collector->collectCategory($sampleCategory);
        $collector->collectCategory($sampleDeletedCategory);

        self::assertCount(0, $eventManager->getEvents());

        $collector->triggerEvents();

        self::assertEquals([
            [
                'event' => 'category-update',
                'target' => $sampleCategory,
                'argv' => [],
                'callback' => null,
            ],
            [
                'event' => 'category-delete',
                'target' => $sampleDeletedCategory,
                'argv' => [],
                'callback' => null,
            ]
        ], $eventManager->getEvents());
    }

    public function testIfCollectorTriggersOnlyLastEvent(): void
    {
        $collector = new CategoryEventCollector($eventManager = new ObservableEventManagerMock);

        $sampleCategory = new CmsCategoryRecord();
        $sampleCategory->id = 1;
        $sampleCategory->status = CmsCategoryRecord::STATUS_ACTIVE;
        $sampleCategory->active = 1;

        $collector->collectCategory($sampleCategory);
        $collector->collectCategory($sampleCategory);
        $collector->collectCategory($sampleCategory);

        $collector->triggerEvents();
        self::assertCount(1, $eventManager->getEvents());
    }
}
