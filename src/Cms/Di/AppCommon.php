<?php

use Cms\App\CmsAppEventInterceptor;
use Mmi\App\AppEventInterceptorAbstract;

use function DI\autowire;
use function DI\env;

return [
    'cms.lang.default'   => env('CMS_LANG_DEFAULT', 'pl'),
    'cms.lang.available' => env('CMS_LANG_AVAILABLE', 'pl,en'),

    AppEventInterceptorAbstract::class => autowire(CmsAppEventInterceptor::class),
];