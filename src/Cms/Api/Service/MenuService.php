<?php

namespace Cms\Api\Service;

use Cms\Api\LinkData;
use Cms\Api\RedirectTransport;
use Cms\App\CmsRouterConfig;
use Cms\App\CmsSkinsetConfig;
use Cms\Model\SkinsetModel;
use Cms\Model\TemplateModel;
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
            //record is created here (from array), it helps optimizing memory usage for large structures
            $cmsCategoryRecord = new CmsCategoryRecord();
            $cmsCategoryRecord->setFromArray($item);
            $this->addItem($cmsCategoryRecord, $menuStructure);
        }
        //sorting menu
        $orderedMenu = isset($menuStructure['children']) ? $this->sortMenu($menuStructure['children']) : [];
        //cache save
        $this->cacheService->save($orderedMenu, self::CACHE_KEY . $scope, 0);
        return $orderedMenu;
    }

    /**
     * Adding item with direct nesting (unfortunately not sorted)
     */
    protected function addItem(CmsCategoryRecord $cmsCategoryRecord, array &$menu): void
    {
        //using orderMap and id to determine target table nesting
        foreach (explode('/', trim($cmsCategoryRecord->path . '/' . $cmsCategoryRecord->id, '/')) as $id) {
            //some objects in the path are deleted
            if (!isset($this->orderMap[$id])) {
                return;
            }
            $menu = &$menu['children'][$this->orderMap[$id] . '-' . $id];
        }
        //adding formatted item to menu
        $menu = array_merge($this->formatItem($cmsCategoryRecord), $menu ?: []);
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

    protected function formatItem(CmsCategoryRecord $cmsCategoryRecord): array
    {
        return [
            'id'         => $cmsCategoryRecord->id,
            'name'       => $cmsCategoryRecord->name,
            'template'   => $cmsCategoryRecord->template,
            'blank'      => (bool) $cmsCategoryRecord->blank,
            'visible'    => (bool) $cmsCategoryRecord->visible,
            'attributes' => (new TemplateModel($cmsCategoryRecord, $this->cmsSkinsetConfig))->getAttributes(),
            'order'      => (int) $cmsCategoryRecord->order,
            '_links'     => $this->getLinks($cmsCategoryRecord),
            'children'   => [],
        ];
    }

    protected function getFromInfrastructure(?string $scope): array
    {
        $query = (new CmsCategoryQuery())
            ->whereStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
            ->whereActive()->equals(true)
            ->whereTemplate()->like($scope . '%');
        //scope is defined (filtering templates)
        if (null !== $scope) {
            $query->whereTemplate()->equals([$scope => $scope] + (new SkinsetModel($this->cmsSkinsetConfig))->getAllowedTemplateKeysBySkinKey($scope));
        }
        return $query->findFields(['id', 'template', 'name', 'uri', 'blank', 'visible', 'configJson', 'customUri', 'redirectUri', 'path', 'order']);
    }

    protected function getLinks(CmsCategoryRecord $cmsCategoryRecord): array
    {
        if ($cmsCategoryRecord->redirectUri) {
            return (new RedirectTransport($cmsCategoryRecord->redirectUri))->_links;
        }
        $scope = substr($cmsCategoryRecord->template, 0, strpos($cmsCategoryRecord->template, self::PATH_SEPARATOR)) ?: $cmsCategoryRecord->template;
        if ($scope) {
            return [
                (new LinkData())
                    ->setHref(sprintf(CmsRouterConfig::API_METHOD_CONTENT, $scope, $cmsCategoryRecord->customUri ?: $cmsCategoryRecord->uri))
                    ->setRel(LinkData::REL_CONTENT)
            ];
        }
        return [];
    }
}
