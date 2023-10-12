# README
## What is MMi CMS?

* MMi CMS is a content management system written with MMi PHP Framework. It allows developers to build CMS solutions easily.

## Requirements
* MMi CMS is only supported on PHP 8.1 and up.

## Installation

### The best way to install MMi CMS is to use composer:

1. composer require mmi/mmi-cms
2. configure Your environment in .env (.env.sample can be found in this repository)
3. you will need to inject (for example in /src/App/di.app.php):
    - RouterConfig::class pointing to your router configuration class
    - NavigationConfig::class pointing to the Navigation config (ie. CmsNavigationConfig::class)
    - CmsSkinsetConfig::class pointing to the Skinset config
    - AuthInterface::class pointing to the Authentication model (ie. Cms\Security\AuthProvider)
    - AppEventInterceptorInterface::class pointing to CmsAppEventInterceptor::class or a subclass ot this
    - Optionally you can inject AuthProviderInterface::class to manage user authentication process
4. deploy your database with ./bin/mmi Mmi:DbDeploy

### .env configuration:
* CMS_AUTH_SALT=some-random-salt (should be random)
* CMS_LANG_DEFAULT=en (default admin panel language)
* CMS_THUMB_QUALITY=85 (WebP / JPEG quality in %)
