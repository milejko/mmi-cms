<?php

namespace Cms\Api\Service;

use Cms\Orm\CmsCategoryRecord;

interface MenuServiceInterface
{

    public function getMenus(?CmsCategoryRecord $activatedCmsCategoryRecord): array;

    public function getBreadcrumbs(CmsCategoryRecord $cmsCategoryRecord): array;

}