<?php

namespace Cms\Api\Service;

use Cms\Api\LinkData;
use Cms\ApiController;
use Cms\Orm\CmsCategoryQuery;
use Mmi\Cache\CacheInterface;

/**
 * Menu service
 */
class MenuService implements MenuServiceInterface
{
    const CACHE_KEY = 'cms-api-navigation';

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
                'name'      => $item['name'],
                'template'  => substr($item['template'], strpos($item['template'], '/') + 1),
                'order'     => $item['order'],
                '_links'    => $this->getLinks($item),
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
            $current = &$current['children'][$id];
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
            ->findFields(['id', 'template', 'name', 'uri', 'customUri', 'redirectUri', 'path', 'order']);
    }

    private function getLinks(array $item): array
    {
        if ($item['redirectUri']) {
            return [(new LinkData)
                ->setHref($item['redirectUri'])
                ->setMethod(LinkData::METHOD_REDIRECT)
                ->setRel('external')];
        }
        if ($item['template']) {
            return [(new LinkData)
                ->setHref(ApiController::API_PREFIX . ($item['customUri'] ?: $item['uri']))];
        }
        return [];
    }
}
