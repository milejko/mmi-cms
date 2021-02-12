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

    public function getMenus(): array
    {
        if (null !== $menuStructure = $this->cacheService->load(self::CACHE_KEY)) {
            return $menuStructure;
        }
        foreach ($items = $this->getFromInfrastructure() as $item) {
            $this->orderMap[$item['id']] = $item['order'] . '-' . $item['id'];
        }
        foreach ($items as $item) {
            $this->injectIntoMenu($menu, $item);
        }
        $this->cacheService->save($orderedMenu = $this->sortMenu($menu['children']), self::CACHE_KEY, 0);
        return $orderedMenu;
    }

    protected function sortMenu(array $menu): array
    {
        $orderedMenu = [];
        ksort($menu);
        foreach ($menu as $item) {
            if (!empty($item['children'])) {
                $item['children'] = $this->sortMenu($item['children']);
            }
            $orderedMenu[] = $item;
        }
        return $orderedMenu;
    }

    protected function injectIntoMenu(&$menu, $item): void
    {
        foreach (explode('/', trim($item['path'] . '/' . $item['id'], '/')) as $id) {
            $menu = &$menu['children'][isset($this->orderMap[$id]) ? $this->orderMap[$id] : '0-' . $id];
        }
        $menu = array_merge($this->formatItem($item), $menu ? : []);
    }
    
    protected function formatItem(array $item): array
    {
        return [
            'id'        => $item['id'],
            'name'      => $item['name'],
            'blank'     => (bool) $item['blank'],
            'template'  => $item['template'],
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
