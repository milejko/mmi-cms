<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Mock\Cms;

use Cms\Orm\CmsCategoryRecord;

class SampleCategoryData
{
    public static function insertObjectsIntoDatabase(): void
    {
        $sampleRecord = new CmsCategoryRecord();
        $sampleRecord->active = 1;
        $sampleRecord->template = 'sample/sampletpl';
        $sampleRecord->cmsAuthId = 1;
        $sampleRecord->name = 'sample name (also a title)';
        $sampleRecord->configJson = json_encode(['some-attribute' => 'some-value']);
        $sampleRecord->status = CmsCategoryRecord::STATUS_ACTIVE;
        $sampleRecord->save();

        $anotherRecord = new CmsCategoryRecord();
        $anotherRecord->active = 1;
        $anotherRecord->parentId = $sampleRecord->id;
        $anotherRecord->path = $anotherRecord->parentId;
        $anotherRecord->template = 'sample/sampletpl';
        $anotherRecord->cmsAuthId = 1;
        $anotherRecord->name = 'another name';
        $anotherRecord->title = 'another title';
        $anotherRecord->description = 'description';
        $anotherRecord->configJson = json_encode(['some-other-attribute' => 'some-other-value']);
        $anotherRecord->status = CmsCategoryRecord::STATUS_ACTIVE;
        $anotherRecord->save();

        $yetAnotherRecord = new CmsCategoryRecord();
        $yetAnotherRecord->active = 1;
        $yetAnotherRecord->visible = 0;
        $yetAnotherRecord->parentId = $sampleRecord->id;
        $yetAnotherRecord->path = $anotherRecord->parentId;
        $yetAnotherRecord->template = 'sample/sampletpl';
        $yetAnotherRecord->blank = true;
        $yetAnotherRecord->cmsAuthId = 1;
        $yetAnotherRecord->name = 'yet another name';
        $yetAnotherRecord->title = 'yet another title';
        $yetAnotherRecord->description = 'description';
        $yetAnotherRecord->configJson = json_encode(['some-other-attribute' => 'some-other-value']);
        $yetAnotherRecord->status = CmsCategoryRecord::STATUS_ACTIVE;
        $yetAnotherRecord->save();

        $redirectRecord = new CmsCategoryRecord();
        $redirectRecord->active = 1;
        $redirectRecord->template = 'sample/sampletpl';
        $redirectRecord->path = $anotherRecord->parentId;
        $redirectRecord->blank = true;
        $redirectRecord->cmsAuthId = 1;
        $redirectRecord->name = 'Sample redirect';
        $redirectRecord->redirectUri = 'https://www.google.com';
        $redirectRecord->status = CmsCategoryRecord::STATUS_ACTIVE;
        $redirectRecord->save();

        $internalRedirectRecord = new CmsCategoryRecord();
        $internalRedirectRecord->active = 1;
        $internalRedirectRecord->template = 'sample/sampletpl';
        $internalRedirectRecord->path = $anotherRecord->parentId;
        $internalRedirectRecord->blank = true;
        $internalRedirectRecord->cmsAuthId = 1;
        $internalRedirectRecord->name = 'Sample internal redirect';
        $internalRedirectRecord->redirectUri = 'internal://3';
        $internalRedirectRecord->status = CmsCategoryRecord::STATUS_ACTIVE;
        $internalRedirectRecord->save();

        $junkRecord = new CmsCategoryRecord();
        $junkRecord->active = 1;
        $junkRecord->status = CmsCategoryRecord::STATUS_ACTIVE;
        $junkRecord->template = 'junk';
        $junkRecord->save();
    }
}
