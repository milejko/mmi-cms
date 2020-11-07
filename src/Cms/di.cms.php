<?php

use Cms\App\CmsAppEventInterceptor;
use Cms\Model\Auth;
use Mmi\App\AppEventInterceptorInterface;
use Mmi\Security\AuthInterface;

use function DI\autowire;
use function DI\env;

return [
    'cms.language.default'  => env('CMS_LANGUAGE_DEFAULT', 'pl'),
    'cms.language.list'     => env('CMS_LANGUAGE_LIST', 'pl,en'),
    'cms.thumb.quality'     => env('CMS_THUMB_QUALITY', 85),
    AppEventInterceptorInterface::class => autowire(CmsAppEventInterceptor::class),
    //auth & cms interceptor
    AuthInterface::class                => autowire(Auth::class),

];