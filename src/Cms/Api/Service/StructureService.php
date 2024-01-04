<?php

namespace Cms\Api\Service;

use Cms\Api\LinkData;
use Cms\Api\RedirectTransport;
use Cms\App\CmsRouterConfig;
use Cms\App\CmsSkinsetConfig;
use Cms\Model\SkinsetModel;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Mmi\Cache\CacheInterface;

/**
 * Menu service
 */
class StructureService implements StructureServiceInterface
{
    private const CACHE_TTL = 0;
    private const PATH_SEPARATOR = '/';

    private CacheInterface $cacheService;
    private CmsSkinsetConfig $cmsSkinsetConfig;

    public function __construct(CacheInterface $cacheService, CmsSkinsetConfig $cmsSkinsetConfig)
    {
        $this->cacheService = $cacheService;
        $this->cmsSkinsetConfig = $cmsSkinsetConfig;
    }

    /**
     * Public menu getter
     */
    public function getStructure(?string $scope): array
    {
        //$menuStructure = [];
        //loading from cache
        //$cacheKey = CmsCategoryRecord::CATEGORY_CACHE_TRANSPORT_PREFIX . $scope;
        //$menuStructure = $this->cacheService->load($cacheKey);
        $flatArray = $this->getFromInfrastructure($scope);
        $treeData = [];
        foreach ($flatArray[''] as $categoryRow) {
            $treeData[] = $this->formatItem($categoryRow, $flatArray);
        }
        return $treeData;
    }

    private function formatItem(array $cmsCategoryRow, array $flatArray = []): array
    {
        $formattedItem = [
            'id'            => $cmsCategoryRow['id'],
            'name'          => $cmsCategoryRow['name'],
            'template'      => $cmsCategoryRow['template'],
            'visible'       => (bool) $cmsCategoryRow['visible'],
            '_links'        => $this->getLinks($cmsCategoryRow),
        ];
        $formattedItem['children'] = [];
        $directChildren = isset($flatArray[$cmsCategoryRow['id']]) ? $flatArray[$cmsCategoryRow['id']] : [];
        foreach ($directChildren as $childRow) {
            $formattedItem['children'][] = $this->formatItem($childRow, $flatArray);
        }
        return $formattedItem;
    }

    private function getFromInfrastructure(?string $scope): array
    {
        $rawData = (new CmsCategoryQuery())
            ->whereStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
            ->whereActive()->equals(true)
            ->whereTemplate()->equals([$scope => $scope] + (new SkinsetModel($this->cmsSkinsetConfig))->getAllowedTemplateKeysBySkinKey($scope))
            ->orderAscParentId()
            ->orderAscOrder()
            ->orderAscId()
            ->findFields(['id', 'parent_id', 'template', 'name', 'uri', 'customUri', 'redirectUri', 'visible']);
        $directParentData = [];
        foreach ($rawData as $categoryRow) {
            $directParentData[$categoryRow['parent_id']][] = $categoryRow;
        }
        return $directParentData;
    }

    private function getLinks(array $cmsCategoryRow): array
    {
        if ($cmsCategoryRow['redirectUri']) {
            return (new RedirectTransport($cmsCategoryRow['redirectUri']))->_links;
        }
        $scope = substr($cmsCategoryRow['template'], 0, strpos($cmsCategoryRow['template'], self::PATH_SEPARATOR)) ?: $cmsCategoryRow['template'];
        if ($scope) {
            return [
                (new LinkData())
                    ->setHref(sprintf(CmsRouterConfig::API_METHOD_CONTENT, $scope, $cmsCategoryRow['customUri'] ?: $cmsCategoryRow['uri']))
            ];
        }
        return [];
    }
}
