<?php

use function DI\env;

return [
    'cms.lang.default'   => env('CMS_LANG_DEFAULT', 'pl'),
    'cms.lang.available' => env('CMS_LANG_AVAILABLE', 'pl,en'),
    'cms.thumb.quality'  => env('CMS_THUMB_QUALITY', 85),
];