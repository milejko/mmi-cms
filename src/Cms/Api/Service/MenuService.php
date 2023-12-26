<?php

namespace Cms\Api\Service;

use Cms\Api\LinkData;
use Cms\Api\RedirectTransport;
use Cms\App\CmsRouterConfig;
use Cms\App\CmsSkinsetConfig;
use Cms\Model\JsonObjectTruncate;
use Cms\Model\SkinsetModel;
use Cms\Model\TemplateModel;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Mmi\Cache\CacheInterface;
use Mmi\Orm\RecordCollection;

/**
 * Menu service
 */
class MenuService implements MenuServiceInterface
{
    public const MENU_TOP_LEVEL_PREFIX = 'category-menu-top-level-';
    public const MENU_CATEGORY_CACHE_PREFIX = 'category-menu-';
    private const CACHE_TTL = 0;
    private const PATH_SEPARATOR = '/';
    private const EXTENDED_FORMATTER = 'extended';

    private ?string $formatter = self::EXTENDED_FORMATTER;

    private CacheInterface $cacheService;
    private CmsSkinsetConfig $cmsSkinsetConfig;

    public function __construct(CacheInterface $cacheService, CmsSkinsetConfig $cmsSkinsetConfig)
    {
        $this->cacheService = $cacheService;
        $this->cmsSkinsetConfig = $cmsSkinsetConfig;
    }

    public function setFormatter(?string $formatterName = null): MenuServiceInterface
    {
        $this->formatter = $formatterName;
        return $this;
    }

    /**
     * Public menu getter
     */
    public function getMenus(?string $scope, int $maxLevel = 0): array
    {
        $menuStructure = [];
        //getting from infrastructure + writing down item order
        foreach ($this->getTopLevelFromInfrastructure($scope) as $cmsCategoryRecord) {
            $menuStructure[] = $this->formatItem($cmsCategoryRecord, 0, $maxLevel);
        }
        return $menuStructure;
    }

    private function formatItem(CmsCategoryRecord $cmsCategoryRecord, int $currentLevel, int $maxLevel): array
    {
        //loading from cache
        $cacheKey = self::MENU_CATEGORY_CACHE_PREFIX . $cmsCategoryRecord->id;
        $formattedItem = $this->cacheService->load($cacheKey);
        if (null !== $formattedItem) {
            return $formattedItem;
        }
        $formattedItem = [
            'id'        => $cmsCategoryRecord->id,
            'name'      => $cmsCategoryRecord->name,
            'template'  => $cmsCategoryRecord->template,
            'visible'   => (bool) $cmsCategoryRecord->visible,
        ];
        if (self::EXTENDED_FORMATTER == $this->formatter) {
            $attributes = (new TemplateModel($cmsCategoryRecord, $this->cmsSkinsetConfig))->getAttributes();
            $truncatedAttributes = (new JsonObjectTruncate())->setInputFromJsonArray($attributes)->getAsArray();
            $formattedItem['order'] = $cmsCategoryRecord->getAbsoluteOrder();
            $formattedItem['path'] = $cmsCategoryRecord->getUri();
            $formattedItem['blank'] = (bool) $cmsCategoryRecord->blank;
            $formattedItem['attributes'] = (array) $truncatedAttributes;
        }
        $formattedItem['_links'] = $this->getLinks($cmsCategoryRecord);
        //max level reached
        if ($currentLevel < $maxLevel) {
            $formattedItem['children'] = [];
            foreach ($cmsCategoryRecord->getChildrenRecords() as $childRecord) {
                if (!$childRecord->active) {
                    continue;
                }
                $formattedItem['children'][] = $this->formatItem($childRecord, $currentLevel + 1, $maxLevel);
            }
        }
        $this->cacheService->save($formattedItem, $cacheKey, self::CACHE_TTL);
        return $formattedItem;
    }

    private function getTopLevelFromInfrastructure(?string $scope): RecordCollection
    {
        //loading from cache
        $cacheKey = self::MENU_TOP_LEVEL_PREFIX . $scope;
        $topLevelCategories = $this->cacheService->load($cacheKey);
        if (null !== $topLevelCategories) {
            return $topLevelCategories;
        }
        $topLevelCategories = (new CmsCategoryQuery())
            ->whereStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
            ->whereActive()->equals(true)
            ->whereParentId()->equals(null)
            ->whereTemplate()->equals([$scope => $scope] + (new SkinsetModel($this->cmsSkinsetConfig))->getAllowedTemplateKeysBySkinKey($scope))
            ->orderAscOrder()
            ->orderAscId()
            ->find();
        $this->cacheService->save($topLevelCategories, $cacheKey, self::CACHE_TTL);
        return $topLevelCategories;
    }

    private function getLinks(CmsCategoryRecord $cmsCategoryRecord): array
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
