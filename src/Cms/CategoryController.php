<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

use Cms\App\CmsSkinsetConfig;
use Cms\Model\TemplateModel;
use Cms\Model\WidgetModel;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Cms\Orm\CmsCategoryRepository;
use Mmi\Cache\CacheInterface;
use Mmi\Http\Request;
use Mmi\Mvc\ActionHelper;
use Mmi\Security\AclInterface;
use Mmi\Security\AuthInterface;

/**
 * Kontroler kategorii
 * @property string $uri
 */
class CategoryController extends \Mmi\Mvc\Controller
{
    //akcja weryfikująca czy użytkownik jest redaktorem
    public const REDACTOR_VERIFY_ACTION = 'cmsAdmin:category:index';

    /**
     * @Inject
     * @var CacheInterface
     */
    private $cache;

    /**
     * @Inject
     * @var AuthInterface
     */
    private $auth;

    /**
     * @Inject
     * @var AclInterface
     */
    private $acl;

    /**
     * @Inject
     * @var CmsSkinsetConfig
     */
    private $cmsSkinsetConfig;

    /**
     * @Inject
     * @var ActionHelper
     */
    private $actionHelper;

    /**
     * @Inject
     */
    private CmsCategoryRepository $cmsCategoryRepository;

    /**
     * Akcja dispatchera kategorii
     */
    public function dispatchAction(Request $request)
    {
        //pobranie kategorii
        $category = $this->_getPublishedCategoryByUri($request->uri, (string) $request->scope);
        //renderowanie docelowej akcji
        $html = $this->_renderHtml($category);
        //zwrot html
        return $this->_decorateHtmlWithEditButton($html, $category);
    }

    /**
     * Podgląd redaktora
     * @return string html
     * @throws \Mmi\Mvc\MvcForbiddenException
     * @throws \Mmi\Mvc\MvcNotFoundException
     */
    public function redactorPreviewAction(Request $request)
    {
        //żądanie o wersję artykułu (rola redaktora)
        if (!$this->_hasRedactorRole()) {
            throw new \Mmi\Mvc\MvcForbiddenException('Preview not allowed');
        }
        //wyszukiwanie kategorii
        if (null === $category = (new Orm\CmsCategoryQuery())
            ->whereCmsCategoryOriginalId()->equals($request->originalId ? $request->originalId : null)
            ->findPk($request->versionId)) {
            //404
            throw new \Mmi\Mvc\MvcNotFoundException('Version not found');
        }
        //kategoria do widoku
        $this->view->category = $category;
        //nadpisanie tytułu i opisu (gdyż inaczej brany jest z kategorii oryginalnej)
        $this->view->navigation()->setTitle($category->title)
            ->setDescription($category->description);
        //renderowanie docelowej akcji
        return $this->_decorateHtmlWithEditButton($this->_renderHtml($category), $category);
    }

    /**
     * Akcja renderująca guzik edycji
     */
    public function editButtonAction(Request $request)
    {
        $this->view->categoryId = $request->categoryId;
        $this->view->originalId = $request->originalId;
    }

    /**
     * Pobiera opublikowaną kategorię po uri
     * @param string $uri
     * @return CmsCategoryRecord
     * @throws \Mmi\Mvc\MvcNotFoundException
     */
    protected function _getPublishedCategoryByUri($uri, $scope)
    {
        $category = $this->cmsCategoryRepository->getCategoryRecordByUri($uri, $scope);
        if (!$category) {
            $this->_redirectOrNotFound($uri, $scope);
        }
        return $this->_checkCategory($category);
    }

    /**
     * Sprawdza aktywność kategorii do wyświetlenia
     * przekierowuje na 404 i na inne strony (zgodnie z redirectUri)
     * @param CmsCategoryRecord $category
     * @return Orm\CmsCategoryRecord
     * @throws \Mmi\Mvc\MvcForbiddenException
     * @throws \Mmi\Mvc\MvcNotFoundException
     */
    protected function _checkCategory(CmsCategoryRecord $category)
    {
        //kategoria to przekierowanie
        if ($category->redirectUri) {
            //przekierowanie na uri
            $this->getResponse()->redirectToUrl($category->redirectUri);
        }
        //kategoria posiada customUri, a wejście jest na natywny uri
        if ($category->customUri && $this->uri != $category->customUri && $this->uri == $category->uri) {
            //przekierowanie na customUri
            $this->getResponse()->redirect('cms', 'category', 'dispatch', ['uri' => $category->customUri]);
        }
        //opublikowana kategoria
        return $category;
    }

    /**
     * Pobiera request do przekierowania
     * @param CmsCategoryRecord $category
     * @return string
     * @throws \Mmi\App\KernelException
     */
    protected function _renderHtml(CmsCategoryRecord $category)
    {
        //render szablonu
        return (new TemplateModel($category, $this->cmsSkinsetConfig))->renderDisplayAction($this->view);
    }

    /**
     * Pobiera request do renderowania akcji
     * @param CmsCategoryRecord $category
     * @return string
     * @throws \Mmi\App\KernelException
     */
    protected function _decorateHtmlWithEditButton($html, CmsCategoryRecord $category)
    {
        //brak roli redaktora
        if (!$this->_hasRedactorRole()) {
            return $html;
        }
        //zwraca wyrenderowany HTML
        return str_replace('</body>', $this->actionHelper->action(new \Mmi\Http\Request(['module' => 'cms', 'controller' => 'category', 'action' => 'editButton', 'originalId' => $category->cmsCategoryOriginalId, 'categoryId' => $category->id])) . '</body>', $html);
    }

    /**
     * Zwraca czas buforowania kategorii
     * @return integer
     */
    protected function _getCategoryCacheLifetime(CmsCategoryRecord $category)
    {
        //model szablonu
        $templateModel = new TemplateModel($category, $this->cmsSkinsetConfig);
        //czas buforowania (na podstawie typu kategorii i pojedynczej kategorii
        $cacheLifetime = $templateModel->getTemplateConfg()->getCacheLifeTime();
        //jeśli bufor wyłączony (na poziomie typu kategorii, lub pojedynczej kategorii)
        if (0 == $cacheLifetime) {
            //brak bufora
            return 0;
        }
        //iteracja po widgetach
        foreach ((new \Cms\Model\CategoryWidgetModel($category->id))->getWidgetRelations() as $widgetRelation) {
            //model widgeta
            $widgetModel = new WidgetModel($widgetRelation, $this->cmsSkinsetConfig);
            //bufor wyłączony przez widget
            if (0 == $widgetCacheLifetime = $widgetModel->getWidgetConfig()->getCacheLifeTime()) {
                //brak bufora
                return 0;
            }
            //wpływ widgeta na czas buforowania kategorii
            $cacheLifetime = ($cacheLifetime > $widgetCacheLifetime) ? $widgetCacheLifetime : $cacheLifetime;
        }
        //zwrot długości bufora
        return $cacheLifetime;
    }

    /**
     * Przekierowanie 301 (poszukiwanie w historii), lub 404
     * @param $uri
     * @throws \Exception
     * @throws \Mmi\Mvc\MvcNotFoundException
     */
    protected function _redirectOrNotFound(string $uri, string $scope)
    {
        //klucz bufora
        $cacheKey = CmsCategoryRecord::REDIRECT_CACHE_PREFIX . md5($scope . $uri);
        //zbuforowany brak uri w historii
        if (false === ($redirectUri = $this->cache->load($cacheKey))) {
            //404
            throw new \Mmi\Mvc\MvcNotFoundException('Category not found: ' . $scope . '/' . $uri);
        }
        //przekierowanie 301
        if (null !== $redirectUri) {
            return $this->getResponse()->setCode(301)->redirect('cms', 'category', 'dispatch', ['uri' => $redirectUri]);
        }
        //wyszukiwanie bieżącej kategorii (aktywnej)
        if (null === $category = (new CmsCategoryQuery())->byHistoryUri($uri, $scope)->findFirst()) {
            //brak kategorii w historii - buforowanie informacji
            $this->cache->save(false, $cacheKey, 0);
            //404
            throw new \Mmi\Mvc\MvcNotFoundException('Category not found: ' . $scope . '/' . $uri);
        }
        //zapis uri przekierowania do bufora
        $this->cache->save($category->uri, $cacheKey, 0);
        //przekierowanie 301
        return $this->getResponse()->setCode(301)->redirect('cms', 'category', 'dispatch', ['uri' => $category->uri]);
    }

    /**
     * Sprawdzenie czy jest redaktorem
     * @return boolean
     */
    protected function _hasRedactorRole()
    {
        return $this->acl->isAllowed($this->auth->getRoles(), 'cmsAdmin:category:index');
    }
}
