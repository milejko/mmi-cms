<?php

use Cms\Api\Service\MenuService;
use Cms\Api\Service\MenuServiceInterface;

use function DI\autowire;

return [

    MenuServiceInterface::class => autowire(MenuService::class)

];
