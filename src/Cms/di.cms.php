<?php

use function DI\env;

return [
    'cms.language.default'  => env('CMS_LANGUAGE_DEFAULT', 'pl'),
    'cms.language.list'     => env('CMS_LANGUAGE_LIST', 'pl,en'),
    'cms.thumb.quality'     => env('CMS_THUMB_QUALITY', 85),
];