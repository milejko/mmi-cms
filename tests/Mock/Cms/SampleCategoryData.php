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
        $sampleRecord->id = 1;
        $sampleRecord->order = 1;
        $sampleRecord->active = 1;
        $sampleRecord->template = 'sample/sampletpl';
        $sampleRecord->cmsAuthId = 1;
        $sampleRecord->name = 'sample name (also a title)';
        $sampleRecord->configJson = json_encode(['some-attribute' => 'some-value']);
        $sampleRecord->status = CmsCategoryRecord::STATUS_ACTIVE;
        $sampleRecord->save();

        $anotherRecord = new CmsCategoryRecord();
        $anotherRecord->id = 2;
        $anotherRecord->order = 2;
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
        $yetAnotherRecord->id = 3;
        $yetAnotherRecord->order = 3;
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
        $redirectRecord->id = 4;
        $redirectRecord->order = 4;
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
        $internalRedirectRecord->id = 5;
        $internalRedirectRecord->order = 5;
        $internalRedirectRecord->active = 1;
        $internalRedirectRecord->template = 'sample/sampletpl';
        $internalRedirectRecord->path = $anotherRecord->parentId;
        $internalRedirectRecord->blank = true;
        $internalRedirectRecord->cmsAuthId = 1;
        $internalRedirectRecord->name = 'Sample internal redirect';
        $internalRedirectRecord->redirectUri = 'internal://3';
        $internalRedirectRecord->status = CmsCategoryRecord::STATUS_ACTIVE;
        $internalRedirectRecord->save();

        $inactiveRecord = new CmsCategoryRecord();
        $inactiveRecord->id = 6;
        $inactiveRecord->order = 6;
        $inactiveRecord->active = false;
        $inactiveRecord->template = 'sample/sampletpl';
        $inactiveRecord->cmsAuthId = 1;
        $inactiveRecord->name = 'Inactive record';
        $inactiveRecord->status = CmsCategoryRecord::STATUS_ACTIVE;
        $inactiveRecord->save();

        $historicalRecord1 = new CmsCategoryRecord();
        $historicalRecord1->cmsCategoryOriginalId = 1;
        $historicalRecord1->id = 7;
        $historicalRecord1->order = 7;
        $historicalRecord1->active = true;
        $historicalRecord1->template = 'sample/sampletpl';
        $historicalRecord1->cmsAuthId = 1;
        $historicalRecord1->name = 'sample name';
        $historicalRecord1->status = CmsCategoryRecord::STATUS_HISTORY;
        $historicalRecord1->save();

        $draftRecord1 = new CmsCategoryRecord();
        $draftRecord1->cmsCategoryOriginalId = 1;
        $draftRecord1->id = 8;
        $draftRecord1->order = 8;
        $draftRecord1->active = true;
        $draftRecord1->template = 'sample/sampletpl';
        $draftRecord1->cmsAuthId = 1;
        $draftRecord1->name = 'sample name';
        $draftRecord1->status = CmsCategoryRecord::STATUS_DRAFT;
        $draftRecord1->save();

        $junkRecord = new CmsCategoryRecord();
        $junkRecord->id = 9;
        $junkRecord->order = 9;
        $junkRecord->active = 1;
        $junkRecord->name = 'junk';
        $junkRecord->status = CmsCategoryRecord::STATUS_ACTIVE;
        $junkRecord->template = 'junk';
        $junkRecord->save();

        $customUriRecord = new CmsCategoryRecord();
        $customUriRecord->id = 10;
        $customUriRecord->order = 10;
        $customUriRecord->active = true;
        $customUriRecord->template = 'sample/sampletpl';
        $customUriRecord->cmsAuthId = 1;
        $customUriRecord->parentId = 3;
        $customUriRecord->customUri = 'get-me-by-this-address';
        $customUriRecord->name = 'unimportant';
        $customUriRecord->status = CmsCategoryRecord::STATUS_ACTIVE;
        $customUriRecord->save();

        $invalidTemplate = new CmsCategoryRecord();
        $invalidTemplate->id = 11;
        $invalidTemplate->order = 11;
        $invalidTemplate->active = 1;
        $invalidTemplate->name = 'invalid';
        $invalidTemplate->status = CmsCategoryRecord::STATUS_ACTIVE;
        $invalidTemplate->template = 'sample/invalid';
        $invalidTemplate->save();

        $templateWithABug = new CmsCategoryRecord();
        $templateWithABug->id = 12;
        $templateWithABug->order = 12;
        $templateWithABug->active = 1;
        $templateWithABug->name = 'bugged template';
        $templateWithABug->status = CmsCategoryRecord::STATUS_ACTIVE;
        $templateWithABug->template = 'sample/invalidsampletpl';
        $templateWithABug->save();
    }
}
