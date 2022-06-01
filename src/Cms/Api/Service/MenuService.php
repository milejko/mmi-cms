<?php

namespace Cms\Api\Service;

use Cms\Api\LinkData;
use Cms\ApiController;
use Cms\App\CmsSkinsetConfig;
use Cms\Model\SkinsetModel;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Mmi\Cache\CacheInterface;

/**
 * Menu service
 */
class MenuService implements MenuServiceInterface
{
    public const CACHE_KEY = 'cms-api-navigation-';
    private const PATH_SEPARATOR = '/';

    private CacheInterface $cacheService;
    private CmsSkinsetConfig $cmsSkinsetConfig;
    private array $orderMap = [];

    public function __construct(CacheInterface $cacheService, CmsSkinsetConfig $cmsSkinsetConfig)
    {
        $this->cacheService = $cacheService;
        $this->cmsSkinsetConfig = $cmsSkinsetConfig;
    }

    /**
     * Public menu getter
     */
    public function getMenus(?string $scope): array
    {
        //loading from cache
        if (null !== $menuStructure = $this->cacheService->load(self::CACHE_KEY . $scope)) {
            return $menuStructure;
        }
        //getting from infrastructure + writing down item order
        foreach ($items = $this->getFromInfrastructure($scope) as $item) {
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
        $this->cacheService->save($orderedMenu, self::CACHE_KEY . $scope, 0);
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
            //invalid item
            if (!isset($item['id'])) {
                continue;
            }
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
            '_links'    => $this->getLinks($item),
            'children'  => [],
        ];
    }

    protected function getFromInfrastructure(?string $scope): array
    {
        $query = (new CmsCategoryQuery)
            ->whereStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
            ->whereActive()->equals(true)
            ->whereTemplate()->like($scope . '%');
        //scope is defined (filtering templates)        
        if (null !== $scope) {
            $query->whereTemplate()->equals([$scope => $scope] + (new SkinsetModel($this->cmsSkinsetConfig))->getAllowedTemplateKeysBySkinKey($scope));
        }
        return $query->findFields(['id', 'template', 'name', 'uri', 'blank', 'customUri', 'redirectUri', 'path', 'order']);
    }

    protected function getLinks(array $item): array
    {
        if ($item['redirectUri']) {
            return [(new LinkData)
                ->setHref($item['redirectUri'])
                ->setMethod(LinkData::METHOD_REDIRECT)
                ->setRel('external')];
        }
        $scope = substr($item['template'], 0, strpos($item['template'], self::PATH_SEPARATOR)) ?: $item['template'];
        if ($scope) {
            return [(new LinkData)
                ->setHref(ApiController::API_PREFIX . $scope . self::PATH_SEPARATOR . ($item['customUri'] ?: $item['uri']))];
        }
        return [];
    }

}
