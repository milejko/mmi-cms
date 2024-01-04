<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

use Cms\Api\ErrorTransport;
use Cms\Api\LinkData;
use Cms\Api\MenuDataTransport;
use Cms\Api\RedirectTransport;
use Cms\Api\Service\MenuServiceInterface;
use Cms\Api\Service\StructureServiceInterface;
use Cms\Api\SkinConfigTransport;
use Cms\Api\SkinData;
use Cms\Api\SkinsetDataTransport;
use Cms\Api\TransportInterface;
use Cms\App\CmsRouterConfig;
use Cms\App\CmsSkinNotFoundException;
use Cms\App\CmsSkinsetConfig;
use Cms\App\CmsTemplateConfig;
use Cms\Model\SkinsetModel;
use Cms\Model\TemplateModel;
use Cms\Orm\CmsCategoryPreviewRecord;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Cms\Orm\CmsCategoryRepository;
use Mmi\Cache\CacheInterface;
use Mmi\Http\Request;
use Mmi\Http\Response;
use Mmi\Mvc\Controller;

/**
 * Kontroler kategorii
 */
class ApiController extends Controller
{
    /**
     * @Inject
     */
    private CmsSkinsetConfig $cmsSkinsetConfig;

    /**
     * @Inject
     */
    private CacheInterface $cache;

    /**
     * @Inject
     */
    private MenuServiceInterface $menuService;

    /**
     * @Inject
     */
    private StructureServiceInterface $structureService;

    /**
     * @Inject
     */
    private CmsCategoryRepository $cmsCategoryRepository;

    /**
     * Index action (available skins)
     */
    public function indexAction()
    {
        $skins = [];
        //iterating skins
        foreach ($this->cmsSkinsetConfig->getSkins() as $skin) {
            $skinData = new SkinData();
            $skinData->key = $skin->getKey();
            $skinData->name = $skin->getName();
            //config link
            $skinData->_links[] = (
                (new LinkData())
                ->setHref(sprintf(CmsRouterConfig::API_METHOD_CONFIG, $skin->getKey()))
                ->setRel(LinkData::REL_CONFIG)
            );
            //menu link
            $skinData->_links[] = (
                (new LinkData())
                ->setHref(sprintf(CmsRouterConfig::API_METHOD_CONTENTS, $skin->getKey()))
                ->setRel(LinkData::REL_CONTENTS)
            );
            //structure link
            $skinData->_links[] = (
                (new LinkData())
                ->setHref(sprintf(CmsRouterConfig::API_METHOD_STRUCTURE, $skin->getKey()))
                ->setRel(LinkData::REL_STRUCTURE)
            );
            $skins[] = $skinData;
        }
        //serves transport object
        $skinsetTransport = (new SkinsetDataTransport())->setSkins($skins);
        return $this->getResponse()->setTypeJson()
            ->setCode($skinsetTransport->getCode())
            ->setContent($skinsetTransport->toString());
    }

    /**
     * Config action (skin configuration)
     */
    public function configAction(Request $request)
    {
        try {
            //search for skin
            $skinConfig = $this->cmsSkinsetConfig->getSkinByKey($request->scope);
        } catch (CmsSkinNotFoundException $e) {
            //404 - skin not found
            return $this->getNotFoundResponse($e->getMessage());
        }
        //setting transport object
        $skinConfigTransport = new SkinConfigTransport();
        $skinConfigTransport->key = $skinConfig->getKey();
        $skinConfigTransport->attributes = $skinConfig->getAttributes();
        //available templates
        $skinConfigTransport->templates = array_map(function (CmsTemplateConfig $config) {
            return $config->getKey();
        }, $skinConfig->getTemplates());
        //links
        $skinConfigTransport->_links = [
            (new LinkData())
            ->setHref(sprintf(CmsRouterConfig::API_METHOD_CONTENTS, $skinConfig->getKey()))
            ->setRel(LinkData::REL_CONTENTS),
            (new LinkData())
            ->setHref(sprintf(CmsRouterConfig::API_METHOD_STRUCTURE, $skinConfig->getKey()))
            ->setRel(LinkData::REL_STRUCTURE),
        ];
        return $this->getResponse()->setTypeJson()
            ->setCode($skinConfigTransport->getCode())
            ->setContent($skinConfigTransport->toString());
    }

    public function getStructureAction(Request $request)
    {
        try {
            //search for skin
            $skinConfig = $this->cmsSkinsetConfig->getSkinByKey($request->scope);
        } catch (CmsSkinNotFoundException $e) {
            //404 - skin not found
            return $this->getNotFoundResponse($e->getMessage());
        }
        $menuTransport = (new MenuDataTransport())->setMenu($this->structureService->getStructure($skinConfig->getKey()));
        $menuTransport->_links = [
            (new LinkData())
            ->setHref(sprintf(CmsRouterConfig::API_METHOD_CONTENTS, $skinConfig->getKey()))
            ->setRel(LinkData::REL_CONTENTS),
            (new LinkData())
            ->setHref(sprintf(CmsRouterConfig::API_METHOD_CONFIG, $skinConfig->getKey()))
            ->setRel(LinkData::REL_CONFIG)
        ];
        return $this->getResponse()->setTypeJson()
            ->setCode($menuTransport->getCode())
            ->setContent($menuTransport->toString());
    }

    /**
     * Akcja listowania contentu
     */
    public function getContentsAction(Request $request)
    {
        //checking scope availability
        try {
            $skinConfig = $this->cmsSkinsetConfig->getSkinByKey($request->scope);
        } catch (CmsSkinNotFoundException $e) {
            //404 - skin not found
            return $this->getNotFoundResponse($e->getMessage());
        }
        $menuTransport = (new MenuDataTransport())->setMenu($this->menuService->getMenus(
            $request->scope,
            $skinConfig->getMenuMaxDepthReturned()
        ));
        $menuTransport->_links = [
            (new LinkData())
            ->setHref(sprintf(CmsRouterConfig::API_METHOD_STRUCTURE, $skinConfig->getKey()))
            ->setRel(LinkData::REL_STRUCTURE),
            (new LinkData())
            ->setHref(sprintf(CmsRouterConfig::API_METHOD_CONFIG, $skinConfig->getKey()))
            ->setRel(LinkData::REL_CONFIG)
        ];
        return $this->getResponse()->setTypeJson()
            ->setCode($menuTransport->getCode())
            ->setContent($menuTransport->toString());
    }

    /**
     * Akcja dispatchera kategorii
     */
    public function getCategoryAction(Request $request)
    {
        try {
            $transportObject = $this->getTransportObject($request);
            return $this->getResponse()->setTypeJson()
                ->setCode($transportObject->getCode())
                ->setContent($transportObject->toString());
        } catch (\Exception $e) {
            return $this->getNotFoundResponse($e->getMessage());
        }
    }

    /**
     * Podgląd nieopublikowanych kategorii
     */
    public function getCategoryPreviewAction(Request $request)
    {
        //direct uri is given so preview equals reqular getCategory()
        if ($request->uri) {
            $categoryResponse = $this->getCategoryAction($request);
            return $categoryResponse->setContent($this->filterPreviewContent($categoryResponse->getContent()));
        }
        //search for a category
        $query = (new CmsCategoryQuery())
            ->whereCmsAuthId()->equals($request->authId)
            ->whereTemplate()->like($request->scope . '%')
            ->whereCmsCategoryOriginalId()->equals($request->originalId ?: null);
        if ($request->originalId) {
            $query->andQuery(
                (new CmsCategoryQuery())
                    ->whereDateModify()->greater(date('Y-m-d H:i:s', strtotime('-8 hours')))
                    ->orFieldDateModify()->equals(null)
            );
        }
        //findPk it is all that is needed, but other conditions secures the request
        if (null === $category = $query->findPk($request->id)) {
            return $this->getNotFoundResponse();
        }
        $preview = new CmsCategoryPreviewRecord();
        $preview->setFromCmsCategoryRecord($category);
        //returning transport object
        $transportObject = (new TemplateModel($preview, $this->cmsSkinsetConfig))->getTransportObject($request);
        return $this->getResponse()->setTypeJson()
            ->setCode($transportObject->getCode())
            ->setContent($this->filterPreviewContent($transportObject->toString()));
    }

    /**
     * Akcja przekierowania z ID na scope/uri
     */
    public function redirectIdAction(Request $request)
    {
        $this->getResponse()->setTypeJson();
        $categoryRecord = $this->cmsCategoryRepository->getCategoryRecordById($request->id);
        if ($categoryRecord && $categoryRecord->isActive()) {
            $this->cache->save($categoryRecord->id, CmsCategoryRecord::URI_ID_CACHE_PREFIX . md5($categoryRecord->getScope() . $categoryRecord->getUri()));
        }
        //brak kategorii lub szablonu
        if (!$categoryRecord || !$categoryRecord->isActive() || $categoryRecord->template == $categoryRecord->getScope()) {
            //404
            return $this->getNotFoundResponse();
        }
        //kategoria posiada niewłaściwy (niewspierany) template
        if (null === (new SkinsetModel($this->cmsSkinsetConfig))->getTemplateConfigByKey($categoryRecord->template)) {
            return $this->getNotFoundResponse('Page unsupported');
        }
        //obiekt transportowy
        $redirectTransportObject = new RedirectTransport(sprintf(CmsRouterConfig::API_METHOD_CONTENT, $categoryRecord->getScope(), $categoryRecord->getUri()));
        return $this->getResponse()->setTypeJson()
            ->setCode($redirectTransportObject->getCode())
            ->setContent($redirectTransportObject->toString());
    }

    /**
     * Pobiera opublikowaną kategorię po uri
     * @param string $uri
     * @throws \Mmi\Mvc\MvcNotFoundException
     */
    private function getTransportObject(Request $request): TransportInterface
    {
        $categoryId = $this->getCategoryId($request->scope, $request->uri);
        //brak ID dla danego uri/scope
        if (!$categoryId) {
            //301 (o ile możliwe) lub 404
            return $this->getRedirectOrErrorTransport($request->scope, $request->uri);
        }
        $category = $this->cmsCategoryRepository->getCategoryRecordById($categoryId);
        if ($request->uri != $category->getUri()) {
            return new RedirectTransport(sprintf(CmsRouterConfig::API_METHOD_CONTENT, $request->scope, $category->getUri()));
        }
        //kategoria to przekierowanie
        if ($category->redirectUri) {
            //przekierowanie na uri
            return new RedirectTransport($category->redirectUri);
        }
        //kategoria posiada customUri, a wejście jest na natywny uri
        if ($category->getUri() != $category->uri && $request->uri == $category->uri) {
            //przekierowanie na customUri
            return new RedirectTransport(sprintf(CmsRouterConfig::API_METHOD_CONTENT, $request->scope, $category->getUri()));
        }
        //sprawdzanie kategorii
        return $this->getCategoryTransport($category);
    }

    /**
     * Pobiera obiekt transportowy kategorii
     */
    private function getCategoryTransport(CmsCategoryRecord $category): TransportInterface
    {
        //kategoria posiada niewłaściwy (niewspierany) template
        if (null === $templateConfig = (new SkinsetModel($this->cmsSkinsetConfig))->getTemplateConfigByKey($category->template)) {
            return (new ErrorTransport())
                ->setMessage('Page unsupported')
                ->setCode(ErrorTransport::CODE_NOT_FOUND);
        }
        //ładowanie obiektu transportowego z bufora
        if (null === $transportObject = $this->cache->load($cacheKey = CmsCategoryRecord::CATEGORY_CACHE_TRANSPORT_PREFIX . $category->id)) {
            //generowanie obiektu transportowego i zapis do cache
            $transportObject = (new TemplateModel($category, $this->cmsSkinsetConfig))->getTransportObject();
            $this->cache->save($transportObject, $cacheKey, $templateConfig->getCacheLifeTime());
        }
        return $transportObject;
    }

    /**
     * Zwraca odpowiedź 404 z podanym messagem
     */
    private function getNotFoundResponse(string $message = 'Page not found'): Response
    {
        $errorTransport = (new ErrorTransport())
            ->setMessage($message)
            ->setCode(ErrorTransport::CODE_NOT_FOUND);
        return $this->getResponse()->setTypeJson()
            ->setCode($errorTransport->getCode())
            ->setContent($errorTransport->toString());
    }

    /**
     * Przekierowanie 301 (poszukiwanie w historii), lub 404
     */
    private function getRedirectOrErrorTransport(string $scope, string $uri): TransportInterface
    {
        //klucz bufora
        $cacheKey = CmsCategoryRecord::REDIRECT_CACHE_PREFIX . md5($scope . $uri);
        //zbuforowany brak uri w historii
        if (false === ($redirectUri = $this->cache->load($cacheKey))) {
            //404
            return (new ErrorTransport())
                ->setMessage('Page not found')
                ->setCode(ErrorTransport::CODE_NOT_FOUND);
        }
        //przekierowanie 301
        if (null !== $redirectUri) {
            return new RedirectTransport(sprintf(CmsRouterConfig::API_METHOD_CONTENT, $scope, $redirectUri));
        }
        //wyszukiwanie bieżącej kategorii (aktywnej)
        if (null === $category = (new CmsCategoryQuery())
            ->byHistoryUri($uri, $scope)->findFirst()
        ) {
            //brak kategorii w historii - buforowanie informacji
            $this->cache->save(false, $cacheKey, 0);
            //404
            return (new ErrorTransport())
                ->setMessage('Page not found')
                ->setCode(ErrorTransport::CODE_NOT_FOUND);
        }
        //zapis uri przekierowania do bufora
        $this->cache->save($scope . '/' . $category->uri, $cacheKey, 0);
        //przekierowanie 301
        return new RedirectTransport(LinkData::INTERNAL_REDIRECT_PREFIX . $category->id);
    }

    /**
     * Pobiera ID categorii
     */
    private function getCategoryId($scope, $uri)
    {
        //próba mapowania uri na ID kategorii z cache
        if ($categoryId = $this->cache->load($cacheKey = CmsCategoryRecord::URI_ID_CACHE_PREFIX . md5($scope . $uri))) {
            return $categoryId;
        }
        if (false === $categoryId) {
            return null;
        }
        //próba pobrania kategorii po URI
        if (null === $category = (new Orm\CmsCategoryQuery())->getCategoryByUri($uri, $scope)) {
            //zapis informacji o braku kategorii w cache
            $this->cache->save(false, $cacheKey, 0);
            return null;
        }
        //kategoria jest nieaktywna
        if (!$category->isActive()) {
            //zapis informacji o braku kategorii w cache
            $this->cache->save(false, $cacheKey, 0);
            $this->cache->save(false, CmsCategoryRecord::REDIRECT_CACHE_PREFIX . md5($scope . $uri), 0);
            return null;
        }
        $this->cache->save($category->id, $cacheKey, 0);
        return $category->id;
    }

    /**
     * Preview url filter
     */
    private function filterPreviewContent(string $content): string
    {
        return preg_replace('%\\\/api\\\/([a-z0-9]+)\\\/contents\\\/%', '/api/$1/contents/preview/', $content);
    }
}
