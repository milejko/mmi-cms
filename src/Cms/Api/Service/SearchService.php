<?php

namespace Cms\Api\Service;

use Cms\Api\LinkData;
use Cms\Api\RedirectTransport;
use Cms\App\CmsRouterConfig;
use Cms\App\CmsSkinsetConfig;
use Cms\Model\TemplateModel;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Mmi\Cache\CacheInterface;
use Mmi\Http\Request;

/**
 * Menu service
 */
class SearchService implements SearchServiceInterface
{
    public const CACHE_KEY_RESULT = 'cms-api-search-result';
    public const CACHE_KEY_TOTAL = 'cms-api-search-total';
    private const PATH_SEPARATOR = '/';

    private CacheInterface $cacheService;
    private CmsSkinsetConfig $cmsSkinsetConfig;

    public function __construct(CacheInterface $cacheService, CmsSkinsetConfig $cmsSkinsetConfig)
    {
        $this->cacheService = $cacheService;
        $this->cmsSkinsetConfig = $cmsSkinsetConfig;
    }

    public function getTotal(Request $request): int
    {
        //loading from cache
        $cacheKey = self::CACHE_KEY_TOTAL . '|' . implode('-', $request->filterBy);
        $count = $this->cacheService->load($cacheKey);
        if (null === $count) {
            //calculate result
            $count = (new CmsCategoryQuery())
                ->getFilteredQuery($request->filterBy, $request->sortBy)
                ->count();
            $this->cacheService->save($count, $cacheKey, 0);
        }
        return $count;
    }

    /**
     * Search getter
     */
    public function getResult(Request $request): array
    {
        //loading from cache
        $cacheKey = self::CACHE_KEY_RESULT . '|' . implode('-', $request->filterBy) . '|' . implode('-', $request->sortBy) . '|' . $request->offset . '|' . $request->limit;
        $list = $this->cacheService->load($cacheKey);
        if (null === $list) {
            //generate list
            $items = $this->searchCategory($request->filterBy, $request->sortBy, $request->offset, $request->limit);
            $list = [];
            foreach ($items as $item) {
                //record is created here (from array), it helps optimizing memory usage for large structures
                $cmsCategoryRecord = new CmsCategoryRecord();
                $cmsCategoryRecord->setFromArray($item);
                $list[] = $this->formatItem($cmsCategoryRecord);
            }
            //cache save
            $this->cacheService->save($list, $cacheKey, 0);
        }
        return $list;
    }

    protected function searchCategory(array $filterBy = [], array $sortBy = [], $offset = 0, $limit = null): array
    {
        return (new CmsCategoryQuery())
            ->getFilteredQuery($filterBy, $sortBy)
            ->offset($offset)
            ->limit($limit)
            ->findFields(
                [
                    'id',
                    'template',
                    'name',
                    'uri',
                    'blank',
                    'visible',
                    'configJson',
                    'customUri',
                    'redirectUri',
                    'path',
                    'order'
                ]
            );
    }

    protected function formatItem(CmsCategoryRecord $cmsCategoryRecord): array
    {
        return [
            'id' => $cmsCategoryRecord->id,
            'name' => $cmsCategoryRecord->name,
            'template' => $cmsCategoryRecord->template,
            'blank' => (bool)$cmsCategoryRecord->blank,
            'visible' => (bool)$cmsCategoryRecord->visible,
            'attributes' => (new TemplateModel($cmsCategoryRecord, $this->cmsSkinsetConfig))->getAttributes(),
            'order' => (int)$cmsCategoryRecord->order,
            '_links' => $this->getLinks($cmsCategoryRecord),
        ];
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
