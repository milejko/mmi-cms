<?php

namespace Cms\Api\Service;

use Cms\Api\BreadcrumbData;
use Cms\Api\LinkData;
use Cms\ApiController;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Mmi\Cache\CacheInterface;

/**
 * Menu service
 */
class MenuService implements MenuServiceInterface
{
    const CACHE_KEY = 'cms-api-navigation';
    const MENU_ITEM_PREFIX = 'item-';

    private CacheInterface $cacheService;

    public function __construct(CacheInterface $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getMenus(): array
    {
        if (null !== $menuStructure = $this->cacheService->load(self::CACHE_KEY)) {
            return $menuStructure;
        }
        $menu = [];
        foreach ($this->getFromInfrastructure() as $item) {
            $fullPath = trim($item['path'] . '/' . $item['id'], '/');
            $this->injectIntoMenu($menu, $fullPath, [
                'id'        => $item['id'],
                'name'      => $item['name'],
                'order'     => $item['order'],
                '_links'    => $item['template'] ? [
                    (new LinkData)
                        ->setHref(ApiController::API_PREFIX . ($item['customUri'] ?: $item['uri']))
                ] : [],
                'children'  => [],
            ]);
        }
        $this->cacheService->save($menu['children'], self::CACHE_KEY, 0);
        return $menu['children'];
    }

    private function injectIntoMenu(&$menu, $path, $value): void
    {
        $ids = explode('/', $path);
        $current = &$menu;
        foreach ($ids as $id) {
            $current = &$current['children'][self::MENU_ITEM_PREFIX . $id];
        }
        $current = is_array($current) ? array_merge($value, $current) : $value;
    }

    private function getFromInfrastructure(): array
    {
        return (new CmsCategoryQuery)
            ->whereStatus()->equals(10)
            ->whereActive()->equals(1)
            ->orderAscParentId()
            ->orderAscOrder()
            ->findFields(['id', 'template', 'name', 'uri', 'customUri', 'path', 'order']);
    }
}
