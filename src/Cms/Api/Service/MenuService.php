<?php

namespace Cms\Api\Service;

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

    private CacheInterface $cacheService;
    private array $orderMap = [];

    public function __construct(CacheInterface $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Public menu getter
     */
    public function getMenus(): array
    {
        //loading from cache
        if (null !== $menuStructure = $this->cacheService->load(self::CACHE_KEY)) {
            return $menuStructure;
        }
        //getting from infrastructure + writing down item order
        foreach ($items = $this->getFromInfrastructure() as $item) {
            $this->orderMap[$item['id']] = str_pad($item['order'], 10, "0", \STR_PAD_LEFT);
        }
        //initializing empty menu structure
        $menuStructure = [];
        //adding items into menu 
        foreach ($items as $item) {
            $this->addItem($item, $menuStructure);
        }
        //sorting menu
        $orderedMenu = isset($menuStructure['children']) ? $this->sortMenu($menuStructure['children']): [];
        //cache save
        $this->cacheService->save($orderedMenu, self::CACHE_KEY, 0);
        return $orderedMenu;
    }

    /**
     * Adding item with direct nesting (unfortunately not sorted)
     */
    protected function addItem(array $item, array &$menu): void
    {
        //using orderMap and id to determine target table nesting
        foreach (explode('/', trim($item['path'] . '/' . $item['id'], '/')) as $id) {
            //some objects in the path are deleted
            if (!isset($this->orderMap[$id])) {
                return;
            }
            $menu = &$menu['children'][$this->orderMap[$id] . '-' . $id];
        }
        //adding formatted item to menu
        $menu = array_merge($this->formatItem($item), $menu ? : []);
    }

    /**
     * Menu sorter
     */
    protected function sortMenu(array $menu): array
    {
        $orderedMenu = [];
        //sorting menu by key
        ksort($menu);
        foreach ($menu as $item) {
            //sorting children
            $item['children'] = $this->sortMenu($item['children']);
            //adding item with sorted children
            $orderedMenu[] = $item;
        }
        return $orderedMenu;
    }
    
    protected function formatItem(array $item): array
    {
        return [
            'id'        => $item['id'],
            'name'      => $item['name'],
            'template'  => $item['template'],
            'blank'     => (bool) $item['blank'],
            'order'     => (int) $item['order'],
            'active'    => (bool) $item['active'],
            '_links'    => (bool) $item['active'] ? $this->getLinks($item) : [],
            'children'  => [],
        ];
    }

    protected function getFromInfrastructure(): array
    {
        return (new CmsCategoryQuery)
            ->whereStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
            ->findFields(['id', 'template', 'name', 'uri', 'blank', 'customUri', 'redirectUri', 'path', 'order', 'active']);
    }

    protected function getLinks(array $item): array
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
