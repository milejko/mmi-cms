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
use Cms\Api\MenuDataTransport;
use Cms\Api\RedirectTransport;
use Cms\Api\Service\MenuServiceInterface;
use Cms\Api\TransportInterface;
use Cms\App\CmsSkinNotFoundException;
use Cms\App\CmsSkinsetConfig;
use Cms\Model\TemplateModel;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Mmi\Cache\CacheInterface;
use Mmi\Http\Request;

/**
 * Kontroler kategorii
 */
class ApiController extends \Mmi\Mvc\Controller
{

    const API_PREFIX = '/api/category/';

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
     * Akcja pobrania menu 
     */
    public function getMenuAction(Request $request)
    {
        //checking scope availability
        try {
            $request->scope && $this->cmsSkinsetConfig->getSkinByKey($request->scope);
        } catch (CmsSkinNotFoundException $e) {
            //error
            $errorTransportObject = new ErrorTransport();
            $errorTransportObject->message = $e->getMessage();
            return $this->getResponse()->setTypeJson()
                ->setCode($errorTransportObject->getCode())
                ->setContent($errorTransportObject->toString());
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
            $errorTransportObject = new ErrorTransport();
            $errorTransportObject->message = $e->getMessage();
        }
        return $this->getResponse()->setTypeJson()
            ->setCode($errorTransportObject->getCode())
            ->setContent($errorTransportObject->toString());
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
        if (null === $categoryId = $this->cache->load($cacheKey = CmsCategoryRecord::URI_ID_CACHE_PREFIX . md5($request->uri))) {
            //próba pobrania kategorii po URI
            if (null === $category = (new Orm\CmsCategoryQuery)->getCategoryByUri($request->uri)) {
                //zapis informacji o braku kategorii w cache
                $this->cache->save(false, $cacheKey, 0);
                //301 (o ile możliwe) lub 404
                return $this->getRedirectOrErrorTransport($request->uri);
            }
            //id kategorii
            $categoryId = $category->id;
            //zapis id kategorii i kategorii w cache 
            $this->cache->save($categoryId, $cacheKey, 0) && $this->cache->save($category, CmsCategoryRecord::CATEGORY_CACHE_PREFIX . $categoryId, 0);
        }
        //w buforze jest informacja o braku strony
        if (false === $categoryId) {
            //301 (o ile możliwe) lub 404
            return $this->getRedirectOrErrorTransport($request->uri);
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

    private function getCategoryTransport(CmsCategoryRecord $category, Request $request): TransportInterface
    {
        //kategoria to przekierowanie
        if ($category->redirectUri) {
            //przekierowanie na uri
            return new RedirectTransport(self::API_PREFIX . $category->redirectUri);
        }
        //kategoria posiada customUri, a wejście jest na natywny uri
        if ($category->customUri && $this->uri != $category->customUri && $this->uri == $category->uri) {
            //przekierowanie na customUri
            return new RedirectTransport(self::API_PREFIX . $category->customUri);
        }
        //opublikowana kategoria
        return (new TemplateModel($category, $this->cmsSkinsetConfig))->getTransportObject($request);
    }

    /**
     * Przekierowanie 301 (poszukiwanie w historii), lub 404
     */
    private function getRedirectOrErrorTransport(string $uri): TransportInterface
    {
        //klucz bufora
        $cacheKey = CmsCategoryRecord::REDIRECT_CACHE_PREFIX . md5($uri);
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
        if (null === $category = (new CmsCategoryQuery())->byHistoryUri($uri)->findFirst()) {
            //brak kategorii w historii - buforowanie informacji
            $this->cache->save(false, $cacheKey, 0);
            //404
            return (new ErrorTransport)
                ->setMessage('Page not found')
                ->setCode(ErrorTransport::CODE_NOT_FOUND);
        }
        //zapis uri przekierowania do bufora
        $this->cache->save($category->uri, $cacheKey, 0);
        //przekierowanie 301
        return new RedirectTransport(self::API_PREFIX . $category->uri);
    }
}
