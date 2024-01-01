<?php

use Cms\App\CmsAppEventInterceptor;
use Cms\App\CmsScopeConfig;
use Cms\Orm\CmsCategoryRepository;
use Cms\Security\AuthProvider;
use Mmi\App\AppEventInterceptorInterface;
use Mmi\Security\AuthProviderInterface;

use function DI\autowire;
use function DI\env;

return [
    'cms.language.default'  => env('CMS_LANGUAGE_DEFAULT', 'pl'),
    'cms.language.list'     => env('CMS_LANGUAGE_LIST', 'pl,en'),
    'cms.thumb.quality'     => env('CMS_THUMB_QUALITY', 85),
    'cms.auth.salt'         => env('CMS_AUTH_SALT', 'better-use-some-random-salt'),

    //auth & cms interceptor
    AppEventInterceptorInterface::class => autowire(CmsAppEventInterceptor::class),
    AuthProviderInterface::class        => autowire(AuthProvider::class),
    CmsScopeConfig::class               => autowire(CmsScopeConfig::class),
    //repositories
    CmsCategoryRepository::class        => autowire(CmsCategoryRepository::class),
];
