<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Cms\App\CmsRouterConfig;
use Cms\App\CmsScopeConfig;
use Cms\App\CmsSkinsetConfig;
use Cms\Model\CategoryValidationModel;
use Cms\Model\SkinsetModel;
use Cms\Model\TemplateModel;
use Cms\Orm\CmsCategoryAclRecord;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use CmsAdmin\Form\CategoryForm;
use CmsAdmin\Form\CategoryMoveForm;
use CmsAdmin\Model\CategoryAclModel;
use Mmi\Cache\CacheInterface;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;
use Mmi\Security\AuthInterface;

/**
 * Kontroler kategorii - stron CMS
 */
class CategoryController extends Controller
{
    //przedrostek brakującego widgeta
    public const MISSING_WIDGET_MESSENGER_PREFIX = 'messenger.widget.missing.';

    /**
     * @Inject
     */
    private AuthInterface $auth;

    /**
     * @Inject
     */
    private CacheInterface $cache;

    /**
     * @Inject
     */
    private CmsScopeConfig $scopeConfig;

    /**
     * @Inject
     */
    private CmsSkinsetConfig $cmsSkinsetConfig;

    /**
     * Lista stron CMS - prezentacja w formie katalogów
     */
    public function indexAction(Request $request)
    {
        $parentCategory = null;
        //wyszukiwanie parenta
        if ($request->parentId && (null === $parentCategory = (new CmsCategoryQuery())
            ->whereTemplate()->like($this->scopeConfig->getName() . '%')
            ->findPk($request->parentId))) {
            //błędny parent
            $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //dostępne szablony
        $this->view->allowedTemplates = $this->getAllowedTemplates($parentCategory);
        //generowanie breadcrumbów
        $breadcrumbs = [];
        while ($parentCategory) {
            $breadcrumbs[] = $parentCategory;
            $parentCategory = $parentCategory->getParentRecord();
        }
        //breadcrumby
        $this->view->breadcrumbs = \array_reverse($breadcrumbs);
        //model skóry skinset do widoku
        $this->view->skinset = $skinsetModel = new SkinsetModel($this->cmsSkinsetConfig);
        //scope do widoku
        $this->view->scopeName = $this->scopeConfig->getName();
        //znalezione kategorie do widoku
        $this->view->categories = (new \Cms\Orm\CmsCategoryQuery())
            ->whereStatus()->equals(\Cms\Orm\CmsCategoryRecord::STATUS_ACTIVE)
            ->whereParentId()->equals($request->parentId ? $request->parentId : null)
            ->whereTemplate()->like($this->scopeConfig->getName() . '%')
            ->whereTemplate()->equals([$this->scopeConfig->getName() => $this->scopeConfig->getName()] + $skinsetModel->getAllowedTemplateKeysBySkinKey($this->scopeConfig->getName()))
            ->orderAscOrder()
            ->find();
    }

    /**
     * Lista stron CMS - podglad strony
     */
    public function previewAction(Request $request)
    {
        //wyszukiwanie kategorii
        if (null === $category = (new CmsCategoryQuery())
                ->whereTemplate()->like($this->scopeConfig->getName() . '%')
                ->findPk($request->id)) {
            //przekierowanie na originalId
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'redactorPreview', ['id' => $request->originalId]);
        }
        //pobranie przekierowania na front zdefiniowanego w skórce
        $skinBasedPreviewUrl = $this->cmsSkinsetConfig->getSkinByKey($this->scopeConfig->getName())->getPreviewUrl();
        //przekierowanie na skórkowy lub defaultowy adres
        $skinBasedPreviewUrl ?
            $this->getResponse()->redirectToUrl(
                $skinBasedPreviewUrl .
                '?apiUrl=' .
                urlencode(sprintf(CmsRouterConfig::API_METHOD_PREVIEW, $category->getScope(), $category->id, $category->cmsCategoryOriginalId ?? 0, $category->cmsAuthId)) .
                '&returnUrl=' .
                urlencode('/cmsAdmin/category/' . ($category->parentId ? '?parentId=' . $category->parentId : ''))
            ) :
            $this->getResponse()->redirect('cms', 'category', 'redactorPreview', ['originalId' => $category->cmsCategoryOriginalId, 'versionId' => $category->id]);
    }

    /**
     * Lista stron CMS - edycja
     * //TODO: refactor!!!!!!!!!
     */
    public function editAction(Request $request)
    {
        //brak id i szablonu
        if (!$request->id && !$request->template) {
            //nowy artykuł bez template
            $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        if ($request->parentId && (null === (new CmsCategoryQuery())
            ->whereTemplate()->like($this->scopeConfig->getName() . '%')
            ->findPk($request->parentId))) {
            //nowy artykuł bez template
            $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //brak id - tworzenie nowej kategorii
        if (!$request->id && $request->template) {
            $category = new CmsCategoryRecord();
            $category->status = CmsCategoryRecord::STATUS_DRAFT;
            $category->template = $request->template;
            $category->parentId = $request->parentId ? $request->parentId : null;
            $category->cmsAuthId = $this->auth->getId();
            $category->save();
            $request->id = $category->id;
            if (null === $category->parentId) {
                $aclRecord = new CmsCategoryAclRecord();
                $aclRecord->cmsCategoryId = $category->id;
                $aclRecord->role = 'admin';
                $aclRecord->access = 'allow';
                $aclRecord->save();
                $this->cache->remove(CategoryAclModel::CACHE_KEY);
            }
        }
        //wyszukiwanie kategorii
        if (null === $category = (new CmsCategoryQuery())
            ->whereTemplate()->like($this->scopeConfig->getName() . '%')
            ->findPk($request->id)) {
            //przekierowanie na originalId
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $request->originalId]);
        }
        //sprawdzanie kompatybilności templata
        $requestedTemplateAllowed = false;
        foreach ($this->getAllowedTemplates($category->getParentRecord()) as $allowedTemplateConfig) {
            if ($category->template == $this->scopeConfig->getName() . '/' . $allowedTemplateConfig->getKey()) {
                $requestedTemplateAllowed = true;
                break;
            }
        }
        //template niekompatybilny
        if (!$requestedTemplateAllowed) {
            $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //zapisywanie oryginalnego id
        $originalId = $category->cmsCategoryOriginalId ? $category->cmsCategoryOriginalId : $category->id;
        //przygotowanie draftu (lub przekierowanie)
        $this->_prepareDraft($category, $request, $originalId);
        //draft ma obcego właściciela
        if ($category->cmsAuthId != $this->auth->getId()) {
            throw new \Mmi\Mvc\MvcForbiddenException('Category not allowed');
        }
        //sprawdzenie uprawnień do edycji węzła kategorii
        if (!(new \CmsAdmin\Model\CategoryAclModel())->getAcl()->isAllowed($this->auth->getRoles(), $originalId)) {
            $this->getMessenger()->addMessage('messenger.category.permission.denied', false);
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'index', ['parentId' => $category->parentId]);
        }
        //likwidacja potencjalnie uszkodzonego cache
        //$category->clearCache();
        //modyfikacja breadcrumbów
        $this->view->adminNavigation()
            ->removeLastBreadcrumb()
            ->modifyLastBreadcrumb('menu.category.index', $this->view->url(['module' => 'cmsAdmin', 'controller' => 'category', 'action' => 'index', 'parentId' => $category->parentId]))
            ->appendBreadcrumb('menu.category.edit', '#');
        //pobranie listy widgetów koniecznych do dodania przed zapisem
        $minOccurrenceWidgets = (new CategoryValidationModel($category, $this->cmsSkinsetConfig))->getMinOccurenceWidgets();
        //konfiguracja kategorii
        $form = new CategoryForm($category);
        //form do widoku
        $this->view->categoryForm = $form;
        //model szablonu
        $templateModel = new TemplateModel($category, $this->cmsSkinsetConfig);
        //szablon strony istnieje
        if ($category->template) {
            $this->view->template = $templateModel->getTemplateConfg();
            //dekoracja formularza
            $templateModel->invokeDecorateEditForm($form);
            //ustawienie danych z rekordu (po dekoracji szablonem)
            $form->setFromRecord($category);
        }
        //ustawienie post
        if ($form->isMine()) {
            $form->setFromPost($request->getPost());
        }
        //walidacja sekcji i ilości widgetów
        if ($form->isMine() && $form->getElement('commit')->getValue() && !empty($minOccurrenceWidgets)) {
            //ustawianie walidacji błędnej
            $form->setValid(false);
            //dodawanie komunikatów o niewypełnionych sekcjach
            foreach ($minOccurrenceWidgets as $widgetKey) {
                $this->getMessenger()->addMessage(self::MISSING_WIDGET_MESSENGER_PREFIX . $widgetKey, false);
            }
            return;
        }
        //ustawianie z POST
        if ($form->isMine()) {
            //przed zapisem formularza
            $templateModel->invokeBeforeSaveEditForm($form);
            //zapis formularza
            $form->save();
        }
        //szablon nadal istnieje
        if ($form->isSaved() && $category->template) {
            //po zapisie forma
            $templateModel->invokeAfterSaveEditForm($form);
        }
        //sprawdzenie czy kategoria nadal istnieje (form robi zapis - to trwa)
        if (!$form->isMine() && (null === $category = (new CmsCategoryQuery())->findPk($request->id))) {
            //przekierowanie na originalId
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $request->originalId]);
        }
        //jeśli nie było posta
        if (!$form->isMine()) {
            //grid z listą wersji historycznych
            $this->view->historyGrid = new \CmsAdmin\Plugin\CategoryHistoryGrid(['originalId' => $category->cmsCategoryOriginalId]);
            return;
        }
        //błędy zapisu
        if ($form->isMine() && !$form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.category.form.errors', false);
            //grid z listą wersji historycznych
            $this->view->historyGrid = new \CmsAdmin\Plugin\CategoryHistoryGrid(['originalId' => $category->cmsCategoryOriginalId]);
            return;
        }
        //zatwierdzenie zmian - commit
        if ($form->isSaved() && $form->getElement('commit')->getValue()) {
            //messenger + redirect
            $this->getMessenger()->addMessage('messenger.category.category.saved', true);
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'index', ['parentId' => $category->parentId]);
        }
        //zapisany form ze zmianą kategorii
        if ($form->isSaved() && 'type' == $form->getElement('submit')->getValue()) {
            //zmiany zapisane
            $this->getMessenger()->addMessage('messenger.category.categoryType.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $category->id, 'originalId' => $category->cmsCategoryOriginalId, 'uploaderId' => $category->id]);
        }
        //przekierowanie po zapisie kopii roboczej
        //format redirect:url
        if ($form->isSaved() && 'redirect' == substr($form->getElement('submit')->getValue(), 0, 8)) {
            //zmiany zapisane
            $this->getResponse()->redirectToUrl(substr($form->getElement('submit')->getValue(), 9));
        }
        //pobranie przekierowania na front zdefiniowanego w skórce
        $skinBasedPreviewUrl = $this->cmsSkinsetConfig->getSkinByKey($this->scopeConfig->getName())->getPreviewUrl();
        //przekierowanie na skórkowy lub defaultowy adres
        $skinBasedPreviewUrl ?
            $this->getResponse()->redirectToUrl($skinBasedPreviewUrl .
                '?apiUrl=' .
                urlencode(sprintf(CmsRouterConfig::API_METHOD_PREVIEW, $category->getScope(), $category->id, $category->cmsCategoryOriginalId, $category->cmsAuthId)) .
                '&returnUrl=' .
                urlencode($this->view->url([], false))) :
            $this->getResponse()->redirect('cms', 'category', 'redactorPreview', ['originalId' => $category->cmsCategoryOriginalId, 'versionId' => $category->id]);
    }

    /**
     * Przenoszenie strony w drzewie
     */
    public function moveAction(Request $request)
    {
        if (null === $category = (new CmsCategoryQuery())
            ->whereTemplate()->like($this->scopeConfig->getName() . '%')
            ->findPk($request->id)) {
            //brak strony
            $this->getMessenger()->addMessage('controller.category.move.error', false);
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //powołanie formularza
        $form = new CategoryMoveForm($category, [AuthInterface::class => $this->auth, CategoryMoveForm::SCOPE_CONFIG_OPTION_NAME => $this->scopeConfig->getName(), SkinsetModel::class => new SkinsetModel($this->cmsSkinsetConfig)]);
        if ($form->isSaved()) {
            //messenger + redirct
            $this->getMessenger()->addMessage('controller.category.move.message', true);
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'index', ['parentId' => $form->getRecord()->parentId]);
        }
        $this->view->form = $form;
    }

    /**
     * Usuwanie strony
     */
    public function deleteAction(Request $request)
    {
        if (null === $category = (new CmsCategoryQuery())
            ->whereTemplate()->like($this->scopeConfig->getName() . '%')
            ->findPk($request->id)) {
            //brak strony
            $this->getMessenger()->addMessage('controller.category.delete.error', false);
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //usuwanie - logika szablonu
        (new TemplateModel($category, $this->cmsSkinsetConfig))->invokeDeleteAction();
        //miękkie usuwanie rekordu
        $category->softDelete();
        //messenger + redirect
        $this->getMessenger()->addMessage('controller.category.delete.message', true);
        $this->getResponse()->redirect('cmsAdmin', 'category', 'index', ['parentId' => $category->parentId]);
    }

    /**
     * Kopiowanie strony - kategorii
     */
    public function copyAction(Request $request)
    {
        if (null === $category = (new CmsCategoryQuery())
            ->whereTemplate()->like($this->scopeConfig->getName() . '%')
            ->findPk($request->id)) {
            //brak strony
            $this->getMessenger()->addMessage('controller.category.copy.error', false);
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //model do kopiowania kategorii
        $copyModel = new \Cms\Model\CategoryCopy($category);
        //kopiowanie z transakcją
        $copyModel->copyWithTransaction() ?
            $this->getMessenger()->addMessage('controller.category.copy.message', true) :
            $this->getMessenger()->addMessage('controller.category.copy.error', false);
        return $this->getResponse()->redirect('cmsAdmin', 'category', 'index', ['parentId' => $category->parentId]);
    }

    public function sortAction(Request $request)
    {
        $this->getResponse()->setTypePlain();
        /*
        *   $orderMap = [
        *       'order'      => (int) Order number ie. 1 or 2
        *       'categoryId' => (int) Category Id
        *   ]
        */
        //sprawdzanie istnienia danych sortujących
        if (null === ($orderMap = $this->getRequest()->getPost()->value)) {
            return 'Sortowanie nie powiodło się';
        }
        //weryfikacja danych sortujących
        if (!is_array($orderMap) || empty($orderMap)) {
            return 'Sortowanie nie powiodło się';
        }
        //sorting
        foreach ($orderMap as $order => $categoryId) {
            //record not found or order is already OK
            if (null === ($record = (new CmsCategoryQuery())
                ->whereTemplate()->like($this->scopeConfig->getName() . '%')
                ->whereOrder()->notEquals($order)
                ->findPk($categoryId))) {
                continue;
            }
            //setting order and simpleUpdate (it is enough, doesn't change paths)
            $record->order = $order;
            $record->simpleUpdate();
        }
        return '';
    }

    /**
     * Przygotowanie drafta
     * @param integer $originalId
     */
    protected function _prepareDraft(CmsCategoryRecord $category, Request $request, $originalId)
    {
        //jeśli to nie był DRAFT
        if (null !== $category->cmsCategoryOriginalId && CmsCategoryRecord::STATUS_DRAFT == $category->status) {
            return;
        }
        //wymuszony świeży draft jeśli informacja przyszła w url, lub kategoria jest z archiwum
        $force = $request->force || (\Cms\Orm\CmsCategoryRecord::STATUS_HISTORY == $category->status);
        //draft nie może być utworzony, ani wczytany
        if (null === $draft = (new \Cms\Model\CategoryDraft($category))->createAndGetDraftForUser($this->auth->getId(), $force)) {
            $this->getMessenger()->addMessage('messenger.category.draft.fail', false);
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'index', ['parentId' => $category->parentId]);
        }
        //przekierowanie do edycji DRAFTu - nowego ID
        $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $draft->id, 'originalId' => $originalId, 'uploaderId' => $draft->id]);
    }

    /**
     * Pobiera dozwolone szablony do dodania pod podaną kategorią
     */
    protected function getAllowedTemplates(?CmsCategoryRecord $parentCategory): array
    {
        $skinsetModel = new SkinsetModel($this->cmsSkinsetConfig);
        $allowedTemplates = [];
        foreach ($skinsetModel->getSkinConfigByKey($this->scopeConfig->getName())->getTemplates() as $templateConfig) {
            if (null === $parentCategory && $templateConfig->getAllowedOnRoot()) {
                $allowedTemplates[] = $templateConfig;
                continue;
            }
            if (null === $parentCategory) {
                continue;
            }
            $parentTemplateConfig = $skinsetModel->getTemplateConfigByKey($parentCategory->template);
            if (in_array($templateConfig->getKey(), $parentTemplateConfig->getCompatibleChildrenKeys())) {
                $allowedTemplates[] = $templateConfig;
            }
        }
        return $allowedTemplates;
    }
}
