<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\App;

use Mmi\Mvc\RouterConfig;

/**
 * Klasa konfiguracji routera
 */
class CmsRouterConfig extends RouterConfig
{

    public function __construct()
    {
        //podgląd kategorii
        $this->setRoute('cms-category-admin-preview', 'cms-content-preview', ['module' => 'cms', 'controller' => 'category', 'action' => 'redactorPreview']);

        //moduł - cmsAdmin
        $this->setRoute('cms-admin-module', 'cmsAdmin', ['module' => 'cmsAdmin'], ['controller' => 'index', 'action' => 'index']);

        //moduł + kontroler + akcja index np. /cmsAdmin/text
        $this->setRoute('cms-admin-module-controller', '/^cmsAdmin\/([a-zA-Z\-]+)$/', ['module' => 'cmsAdmin', 'controller' => '$1'], ['action' => 'index']);

        //moduł + kontroler + akcja np. /cmsAdmin/text/display
        $this->setRoute('cms-admin-module-controller-action', '/^cmsAdmin\/([a-zA-Z\-]+)\/([a-zA-Z]+)$/', ['module' => 'cmsAdmin', 'controller' => '$1', 'action' => '$2']);

        //routa do strony głównej (opartej o category)
        $this->setRoute('cms-category-home', '', ['module' => 'cms', 'controller' => 'category', 'action' => 'dispatch', 'uri' => '/']);

        //routa API main
        $this->setRoute('cms-api', 'api', ['module' => 'cms', 'controller' => 'api', 'action' => 'index']);

        //routa API config
        $this->setRoute('cms-api-config', '/^api\/config\/([a-z0-9\/-]+)$/', ['module' => 'cms', 'controller' => 'api', 'action' => 'config', 'scope' => '$1']);

        //routa API stron cms po ID
        $this->setRoute('cms-category-id-api', '/^api\/category\/id\/([0-9]+)$/', ['module' => 'cms', 'controller' => 'api', 'action' => 'redirectId', 'id' => '$1']);

        //routa API stron cms
        $this->setRoute('cms-category-api', '/^api\/category\/([a-z0-9-]+)\/([a-z0-9\/-]+)$/', ['module' => 'cms', 'controller' => 'api', 'action' => 'getCategory', 'scope' => '$1', 'uri' => '$2']);

        //routy API menu
        $this->setRoute('cms-category-menu-scopes-api', '/^api\/category\/([a-z0-9-]+)$/', ['module' => 'cms', 'controller' => 'api', 'action' => 'getMenu', 'scope' => '$1']);
        $this->setRoute('cms-category-menu-api', '/^api\/category$/', ['module' => 'cms', 'controller' => 'api', 'action' => 'getMenu']);

        //routa API podgląd nieopublikownych stron cms
        $this->setRoute('cms-category-preview-api', '/^api\/category-preview\/([a-z0-9]+)\/([0-9]+)\/([0-9]+)\/([0-9]+)$/', ['module' => 'cms', 'controller' => 'api', 'action' => 'getCategoryPreview', 'scope' => '$1', 'id' => '$2', 'originalId' => '$3', 'authId' => '$4']);

        //routy skalera grafik
        $this->setRoute('cms-file-copy', '/^data\/copy\/([a-f0-9]{32})-([a-f0-9]{32}\.[a-z0-9]+)$/i', ['module' => 'cms', 'controller' => 'file', 'action' => 'copy', 'hash' => '$1', 'name' => '$2']);
        $this->setRoute('cms-file-server', '/^download\/([a-f0-9]{32}\.[a-z0-9]+)-(.*)$/i', ['module' => 'cms', 'controller' => 'file', 'action' => 'server', 'name' => '$1', 'encodedName' => '$2']);
        $this->setRoute('cms-file-default', '/^data\/(default)\/([a-f0-9]{32}\.[a-z0-9]+)-([a-f0-9]{32})\.webp$/i', ['module' => 'cms', 'controller' => 'file', 'action' => 'scaler', 'operation' => '$1', 'name' => '$2', 'hash' => '$3']);
        $this->setRoute('cms-file-scaler', '/^data\/([a-z]+)-([0-9]+)x?([0-9]+)?\/([a-f0-9]{32}\.[a-z0-9]+)-([a-f0-9]{32})\.webp$/i', ['module' => 'cms', 'controller' => 'file', 'action' => 'scaler', 'operation' => '$1', 'x' => '$2', 'y' => '$3', 'name' => '$4', 'hash' => '$5']);

        //routa do stron cms i kategorii
        $this->setRoute('cms-category-dispatch', '/^([a-zA-Z0-9\/\-]+)$/', ['module' => 'cms', 'controller' => 'category', 'action' => 'dispatch', 'uri' => '$1']);
    }

}
