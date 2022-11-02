<?php

use Cms\Api\Service\MenuService;
use Cms\Api\Service\MenuServiceInterface;
use Cms\Api\Service\SearchService;
use Cms\Api\Service\SearchServiceInterface;

use function DI\autowire;

return [
    MenuServiceInterface::class => autowire(MenuService::class),
    SearchServiceInterface::class => autowire(SearchService::class),
];
