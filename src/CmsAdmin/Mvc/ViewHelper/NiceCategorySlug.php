<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Mvc\ViewHelper;

use Cms\Orm\CmsCategoryRecord;
use Mmi\Mvc\ViewHelper\HelperAbstract;

class NiceCategorySlug extends HelperAbstract
{
    private const SEPARATOR = ' / ';

    public function niceCategorySlug(CmsCategoryRecord $cmsCategoryRecord): string
    {
        
        $parentRecord = $cmsCategoryRecord->getParentRecord();
        $parents = [$parentRecord];
        $slug = '';
        while ($parentRecord = $parentRecord->getParentRecord()) {
            $parents[] = $parentRecord;
        }
        foreach (array_reverse($parents) as $parentRecord) {
            $slug .= $parentRecord->name . self::SEPARATOR;
        }
        return rtrim($slug, self::SEPARATOR);
    }
}
