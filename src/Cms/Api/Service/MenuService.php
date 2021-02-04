<?php

namespace Cms\Api\Service;

use Cms\Api\BreadcrumbData;
use Cms\Api\LinkData;
use Cms\ApiController;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Mmi\Cache\CacheInterface;

/**
 * Redirect transport object
 */
class MenuService implements MenuServiceInterface
{
    const CACHE_KEY = 'cms-api-navigation';

    private CacheInterface $cacheService;

    public function __construct(CacheInterface $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Pobiera breadcrumby
     */
    public function getBreadcrumbs(CmsCategoryRecord $cmsCategoryRecord): array
    {
        $breadcrumbs = [];
        $category = $cmsCategoryRecord;
        $order = count(explode('/', $cmsCategoryRecord->path));
        while (null !== $category) {
            $breadcrumbs[] = (new BreadcrumbData)
                ->setTitle($category->name)
                ->setOrder($order--)
                ->setLinks($category->template ? [
                    (new LinkData)
                        ->setHref(ApiController::API_PREFIX . ($category->customUri ?: $category->uri))
                        ->setRel($cmsCategoryRecord === $category ? LinkData::REL_SELF : LinkData::REL_BACK)
                ] : []);
            $category = $category->getParentRecord();
        }
        return array_reverse($breadcrumbs);
    }

    public function getMenus(?CmsCategoryRecord $activeCategoryRecord = null): array
    {
        $menu = [];
        foreach ($this->getFromInfrastructure() as $item) {
            $fullPath = trim($item['path'] . '/' . $item['id'], '/');
            $activatedFullPath = $activeCategoryRecord ? trim($activeCategoryRecord->path . '/' . $activeCategoryRecord->id, '/') : null;
            $this->injectIntoMenu($menu, $fullPath, [
                'id'        => $item['id'],
                'name'      => $item['name'],
                'order'     => $item['order'],
                'active'    => 0 === strpos($activatedFullPath . '/', $fullPath . '/'),
                '_links'    => $item['template'] ? [
                    (new LinkData)
                        ->setHref(ApiController::API_PREFIX . ($item['customUri'] ?: $item['uri']))
                        ->setRel(LinkData::REL_NEXT)
                ] : [],
                'children'  => [],
            ]);
        }
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
        if (null !== $menuStructure = $this->cacheService->load(self::CACHE_KEY)) {
            return $menuStructure;
        }
        $this->cacheService->save($menuStructure = (new CmsCategoryQuery)
            ->whereStatus()->equals(10)
            ->whereActive()->equals(1)
            ->orderAscParentId()
            ->orderAscOrder()
            ->findFields(['id', 'template', 'name', 'uri', 'customUri', 'path', 'order']), self::CACHE_KEY, 0);
        return $menuStructure;
    }
}
