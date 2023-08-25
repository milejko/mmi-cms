<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\App\CmsAppMvcEvents;
use Cms\Orm\CmsCategoryRecord;
use Mmi\EventManager\EventManagerInterface;

class CategoryEventCollector
{
    private array $categoryEvents = [];

    public function __construct(private EventManagerInterface $eventManager)
    {
    }

    public function getEvents(): array
    {
        return $this->categoryEvents;
    }

    public function collectCategory(CmsCategoryRecord $cmsCategoryRecord): void
    {
        if (in_array($cmsCategoryRecord->status, [CmsCategoryRecord::STATUS_DRAFT, CmsCategoryRecord::STATUS_HISTORY])) {
            return;
        }
        $this->categoryEvents[$cmsCategoryRecord->id] = $cmsCategoryRecord;
    }

    public function triggerEvents(): void
    {
        foreach ($this->categoryEvents as $key => $categoryRecord) {
            $this->eventManager->trigger($categoryRecord->isActive() ? CmsAppMvcEvents::CATEGORY_UPDATE : CmsAppMvcEvents::CATEGORY_DELETE, $categoryRecord);
            unset($this->categoryEvents[$key]);
        }
    }
}
