<?php

namespace Cms\Orm;

use Mmi\Cache\CacheInterface;

class CmsCategoryRepository
{
    public function __construct(private CacheInterface $cache)
    {
    }

    public function getCategoryRecordById(?int $id): ?CmsCategoryRecord
    {
        if (null === $id) {
            return null;
        }
        $cacheKey = CmsCategoryRecord::CATEGORY_CACHE_PREFIX . $id;
        $cmsCategoryRecord = $this->cache->load($cacheKey);
        if (false === $cmsCategoryRecord) {
            return null;
        }
        if (null !== $cmsCategoryRecord) {
            return $cmsCategoryRecord;
        }
        $cmsCategoryRecord = (new CmsCategoryQuery())->findPk($id);
        if (null === $cmsCategoryRecord) {
            $this->cache->save(false, $cacheKey, 0);
            return null;
        }
        $this->cache->save($cmsCategoryRecord, $cacheKey, 0);
        return $cmsCategoryRecord;
    }

    public function getCategoryRecordByUri(string $uri, string $scope): ?CmsCategoryRecord
    {
        if (null === $uri) {
            return null;
        }
        $cacheKey = CmsCategoryRecord::URI_ID_CACHE_PREFIX . md5($scope . $uri);
        $cmsCategoryId = $this->cache->load($cacheKey);
        if (false === $cmsCategoryId) {
            return null;
        }
        if (null !== $cmsCategoryId) {
            return $this->getCategoryRecordById($cmsCategoryId);
        }
        $cmsCategoryRecord = (new CmsCategoryQuery())->getCategoryByUri($uri, $scope);
        if (null === $cmsCategoryRecord || !$cmsCategoryRecord->isActive()) {
            $this->cache->save(false, $cacheKey);
            $this->cache->save(false, CmsCategoryRecord::REDIRECT_CACHE_PREFIX . md5($scope . $uri), 0);
            return null;
        }
        $this->cache->save($cmsCategoryRecord, CmsCategoryRecord::CATEGORY_CACHE_PREFIX . $cmsCategoryRecord->id);
        $this->cache->save($cmsCategoryRecord->id, $cacheKey);
        return $cmsCategoryRecord;
    }

    public function getChildrenCategoryIds(?int $id): array
    {
        $categoryRecord = $this->getCategoryRecordById($id);
        if (null === $categoryRecord) {
            return [];
        }
        $childrenIds = $this->cache->load($cacheKey = CmsCategoryRecord::CATEGORY_CHILDREN_CACHE_PREFIX . $id);
        if (null !== $childrenIds) {
            return $childrenIds;
        }
        $childrenIds = array_values((new CmsCategoryQuery())
            ->whereParentId()->equals($id)
            ->whereTemplate()->like($categoryRecord->getScope() . '%')
            ->whereStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
            ->orderAscOrder()
            ->orderAscId()
            ->findPairs('id', 'id'));
        $this->cache->save($childrenIds, $cacheKey, 0);
        return $childrenIds;
    }

    public function getChildrenMaxOrder(?int $id): int
    {
        if (null === $id) {
            return 0;
        }
        $categoryRecord = $this->getCategoryRecordById($id);
        $maxOrder = (new CmsCategoryQuery())
                ->whereParentId()->equals($id)
                ->whereActive()->equals(true)
                ->whereStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
                ->whereTemplate()->like($categoryRecord->getScope() . '%')
                ->findMax('order');
        return is_int($maxOrder) ? $maxOrder : 0;
    }
}
