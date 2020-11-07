README
What is MMi CMS?

MMi CMS is a content management system written with MMi PHP Framework. It allows developers to build CMS solutions easily.

Requirements

MMi CMS is only supported on PHP 7.3.0 and up.

Installation

The best way to install MMi CMS is to use composer:

1). composer require mmi/mmi-cms
2). configure Your environment in .env (.env.sample can be found in this repository)
3). deploy your database with ./bin/mmi Mmi:DbDeploy
4). you will need to inject:
    - RouterConfigAbstract::class pointing to your router configuration class
    - AuthInterface::class pointing to the Authentication model (ie. Cms\Model\Auth)
    - NavigationConfigAbstract::class pointing to the Navigation config (ie. CmsNavigationConfig::class)
    - AppEventInterceptorAbstract::class pointing to CmsAppEventInterceptor::class or a subclass ot this
