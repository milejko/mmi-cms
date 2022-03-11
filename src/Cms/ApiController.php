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
use Cms\Api\SkinConfigTransport;
use Cms\Api\SkinData;
use Cms\Api\SkinsetDataTransport;
use Cms\Api\TransportInterface;
use Cms\App\CmsSkinNotFoundException;
use Cms\App\CmsSkinsetConfig;
use Cms\Model\SkinsetModel;
use Cms\Model\TemplateModel;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Mmi\Cache\CacheInterface;
use Mmi\Http\Request;
use Mmi\Http\Response;
use Mmi\Validator\Integer;

/**
 * Kontroler kategorii
 */
class ApiController extends \Mmi\Mvc\Controller
{
    private const API_PATH_SEPARATOR = '/';
    private const API_HOME = self::API_PATH_SEPARATOR . 'api';
    public const API_PREFIX = self::API_HOME . self::API_PATH_SEPARATOR . 'category' . self::API_PATH_SEPARATOR;
    private const API_CONFIG_PREFIX = '/api/config/';

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
     * Index action (available skins)
     */
    public function indexAction()
    {
        $skins = [];
        //iterating skins
        foreach ($this->cmsSkinsetConfig->getSkins() as $skin) {
            $skinData = new SkinData;
            $skinData->key = $skin->getKey();
            $skinData->name = $skin->getName();
            //config link
            $skinData->_links[] = ((new LinkData())
                ->setHref(self::API_CONFIG_PREFIX . $skin->getKey())
                ->setRel(LinkData::REL_CONFIG)
            );
            //menu link
            $skinData->_links[] = ((new LinkData())
                ->setHref(self::API_PREFIX . $skin->getKey())
                ->setRel(LinkData::REL_MENU)
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
        $skinConfigTransport->_links[] = ((new LinkData())
            ->setHref(self::API_PREFIX . $skinConfig->getKey())
            ->setRel(LinkData::REL_MENU)
        );
        return $this->getResponse()->setTypeJson()
            ->setCode($skinConfigTransport->getCode())
            ->setContent($skinConfigTransport->toString());
    }

    /**
     * Akcja pobrania menu 
     */
    public function getMenuAction(Request $request)
    {
        //scope not found - redirect to home
        if (!$request->scope) {
            $redirectTransportObject = new RedirectTransport(self::API_HOME);
            return $this->getResponse()->setTypeJson()
                ->setCode($redirectTransportObject->getCode())
                ->setContent($redirectTransportObject->toString());
        }
        //checking scope availability
        try {
            $this->cmsSkinsetConfig->getSkinByKey($request->scope);
        } catch (CmsSkinNotFoundException $e) {
            //404 - skin not found
            return $this->getNotFoundResponse($e->getMessage());
        }
        $menuTransport = (new MenuDataTransport())->setMenu($this->menuService->getMenus($request->scope));
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
        //search for a category
        if (
            null === $category = (new CmsCategoryQuery())
            ->whereCmsCategoryOriginalId()->equals($request->originalId)
            ->whereCmsAuthId()->equals($request->authId)
            ->whereDateModify()->greater(date('Y-m-d H:i:s', strtotime('-8 hours')))
            ->findPk($request->id)
        ) {
            return $this->getNotFoundResponse();
        }
        //returning transport object
        $transportObject = (new TemplateModel($category, $this->cmsSkinsetConfig))->getTransportObject($request);
        return $this->getResponse()->setTypeJson()
            ->setCode($transportObject->getCode())
            ->setContent($transportObject->toString());
    }

    /**
     * Akcja przekierowania z ID na scope/uri
     */
    public function redirectIdAction(Request $request)
    {
        $this->getResponse()->setTypeJson();
        $cacheKey = CmsCategoryRecord::CATEGORY_CACHE_PREFIX . $request->id;
        //wyszukiwanie kategorii w cache
        if (null === $categoryRecord = $this->cache->load($cacheKey)) {
            //wyszukiwanie kategorii w db
            if (null === $categoryRecord = (new CmsCategoryQuery())->publishedActive()->findPk($request->id)) {
                //zapis informacji o braku kategorii
                $this->cache->save(false, $cacheKey, 0);
            }
            //jeśli znaleziony rekord
            if ($categoryRecord) {
                //zapis pobranej kategorii w cache i mapowania uri->id
                $this->cache->save($categoryRecord, $cacheKey, 0);
                $this->cache->save($categoryRecord->id, CmsCategoryRecord::URI_ID_CACHE_PREFIX . md5($categoryRecord->getScope() . $categoryRecord->getUri()));
            }
        }
        //brak kategorii lub szablonu
        if (!$categoryRecord || $categoryRecord->template == $categoryRecord->getScope()) {
            //404
            return $this->getNotFoundResponse();
        }
        //obiekt transportowy
        $redirectTransportObject = new RedirectTransport(self::API_PREFIX . $categoryRecord->getScope() . self::API_PATH_SEPARATOR . $categoryRecord->getUri());
        return $this->getResponse()->setTypeJson()
            ->setCode($redirectTransportObject->getCode())
            ->setContent($redirectTransportObject->toString());
    }

    /**
     * Pobiera opublikowaną kategorię po uri
     * @param string $uri
     * @return CmsCategoryRecord
     * @throws \Mmi\Mvc\MvcNotFoundException
     */
    private function getTransportObject(Request $request): TransportInterface
    {
        //inicjalizacja zmiennej
        $category = null;
        //próba mapowania uri na ID kategorii z cache
        if (null === $categoryId = $this->cache->load($cacheKey = CmsCategoryRecord::URI_ID_CACHE_PREFIX . md5($request->scope . $request->uri))) {
            //próba pobrania kategorii po URI
            if (null === $category = (new Orm\CmsCategoryQuery)->getCategoryByUri($request->uri, $request->scope)) {
                //zapis informacji o braku kategorii w cache
                $this->cache->save(false, $cacheKey, 0);
                //301 (o ile możliwe) lub 404
                return $this->getRedirectOrErrorTransport($request->scope, $request->uri);
            }
            //id kategorii
            $categoryId = $category->id;
            //zapis id kategorii i kategorii w cache 
            $this->cache->save($categoryId, $cacheKey, 0) && $this->cache->save($category, CmsCategoryRecord::CATEGORY_CACHE_PREFIX . $categoryId, 0);
        }
        //w buforze jest informacja o braku strony
        if (false === $categoryId) {
            //301 (o ile możliwe) lub 404
            return $this->getRedirectOrErrorTransport($request->scope, $request->uri);
        }
        //kategoria
        if ($category) {
            return $this->getCategoryTransport($category, $request);
        }
        //pobranie kategorii z bufora
        if (null === $category = $this->cache->load($cacheKey = CmsCategoryRecord::CATEGORY_CACHE_PREFIX . $categoryId)) {
            //zapis pobranej kategorii w cache
            $this->cache->save($category = (new Orm\CmsCategoryQuery)->findPk($categoryId), $cacheKey, 0);
        }
        //sprawdzanie kategorii
        return $this->getCategoryTransport($category, $request);
    }

    /**
     * Pobiera obiekt transportowy kategorii
     */
    private function getCategoryTransport(CmsCategoryRecord $category, Request $request): TransportInterface
    {
        //kategoria to przekierowanie
        if ($category->redirectUri) {
            //przekierowanie na uri
            return new RedirectTransport($category->redirectUri);
        }
        //kategoria posiada customUri, a wejście jest na natywny uri
        if ($category->customUri && $request->uri != $category->customUri && $request->uri == $category->uri) {
            //przekierowanie na customUri
            return new RedirectTransport(self::API_PREFIX . $request->scope . self::API_PATH_SEPARATOR . $category->customUri);
        }
        //kategoria posiada niewłaściwy (niewspierany) template
        if (null === $templateConfig = (new SkinsetModel($this->cmsSkinsetConfig))->getTemplateConfigByKey($category->template)) {
            return (new ErrorTransport)
                ->setMessage('Page unsupported')
                ->setCode(ErrorTransport::CODE_NOT_FOUND);;
        }
        //ładowanie obiektu transportowego z bufora
        if (null === $transportObject = $this->cache->load($cacheKey = CmsCategoryRecord::CATEGORY_CACHE_TRANSPORT_PREFIX . $category->id)) {
            //generowanie obiektu transportowego i zapis do cache
            $transportObject = (new TemplateModel($category, $this->cmsSkinsetConfig))->getTransportObject($request);
            $this->cache->save($transportObject, $cacheKey, $templateConfig->getCacheLifeTime());
        }
        return $transportObject;
    }

    /**
     * Zwraca odpowiedź 404 z podanym messagem
     */
    private function getNotFoundResponse(string $message = 'Page not found'): Response
    {
        $errorTransport = (new ErrorTransport)
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
            return (new ErrorTransport)
                ->setMessage('Page not found')
                ->setCode(ErrorTransport::CODE_NOT_FOUND);
        }
        //przekierowanie 301
        if (null !== $redirectUri) {
            return new RedirectTransport(self::API_PREFIX . $redirectUri);
        }
        //wyszukiwanie bieżącej kategorii (aktywnej)
        if (
            null === $category = (new CmsCategoryQuery())
            ->byHistoryUri($uri, $scope)->findFirst()
        ) {
            //brak kategorii w historii - buforowanie informacji
            $this->cache->save(false, $cacheKey, 0);
            //404
            return (new ErrorTransport)
                ->setMessage('Page not found')
                ->setCode(ErrorTransport::CODE_NOT_FOUND);
        }
        //zapis uri przekierowania do bufora
        $this->cache->save($scope . '/' . $category->uri, $cacheKey, 0);
        //przekierowanie 301
        return new RedirectTransport(self::API_PREFIX . $scope . self::API_PATH_SEPARATOR . $category->uri);
    }
}
