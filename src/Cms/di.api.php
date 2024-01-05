<?php

use Cms\Api\Service\MenuService;
use Cms\Api\Service\MenuServiceInterface;
use Cms\Api\Service\StructureService;
use Cms\Api\Service\StructureServiceInterface;

use function DI\autowire;

return [

    MenuServiceInterface::class => autowire(MenuService::class),
    StructureServiceInterface::class => autowire(StructureService::class)

];
