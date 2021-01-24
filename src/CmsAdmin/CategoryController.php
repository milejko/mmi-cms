<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Cms\App\CmsSkinsetConfig;
use Cms\Model\CategoryValidationModel;
use Cms\Model\TemplateModel;
use Cms\Orm\CmsCategoryQuery;
use CmsAdmin\Form\CategoryForm;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;
use Mmi\Mvc\Router;
use Mmi\Security\AuthInterface;
use Mmi\Session\SessionSpace;

/**
 * Kontroler kategorii - stron CMS
 */
class CategoryController extends Controller
{

    //prefiks przestrzeni nazw w sesji
    const SESSION_SPACE_PREFIX = 'category-edit-';
    //parametry edycja kategorii
    const EDIT_MVC_PARAMS = 'cmsAdmin/category/edit';
    //przedrostek brakującego widgeta
    const MISSING_WIDGET_MESSENGER_PREFIX = 'messenger.widget.missing.';
    //suffix admina
    const ADMIN_MODULE_SUFFIX = 'Admin';

    /**
     * @Inject
     * @var AuthInterface
     */
    private $auth;

    /**
     * @Inject
     * @var Router
     */
    private $router;

    /**
     * @Inject
     * @var CmsSkinsetConfig
     */
    private $cmsSkinsetConfig;

    /**
     * Lista stron CMS - prezentacja w formie grida
     */
    public function indexAction()
    {
        $this->view->grid = new Plugin\CategoryGrid();
    }

    /**
     * Lista stron CMS - edycja w formie drzewa
     */
    public function editAction(Request $request)
    {
        //brak id przekierowanie na drzewo
        if (!$request->id) {
            throw new \Mmi\Mvc\MvcNotFoundException('Category not found');
        }
        //wyszukiwanie kategorii
        if (null === $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($request->id)) {
            //przekierowanie na originalId
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $request->originalId]);
        }
        //zapisywanie oryginalnego id
        $originalId = $category->cmsCategoryOriginalId ? $category->cmsCategoryOriginalId : $category->id;
        //przygotowanie draftu (lub przekierowanie)
        $this->_prepareDraft($category, $request, $originalId);
        //draft ma obcego właściciela
        if ($category->cmsAuthId != $this->auth->getId()) {
            throw new \Mmi\Mvc\MvcForbiddenException('Category not allowed');
        }
        //sprawdzanie czy nie duplikat
        $this->view->duplicateAlert = $this->_isCategoryDuplicate($originalId);
        //sprawdzenie uprawnień do edycji węzła kategorii
        if (!(new \CmsAdmin\Model\CategoryAclModel)->getAcl()->isAllowed($this->auth->getRoles(), $originalId)) {
            $this->getMessenger()->addMessage('messenger.category.permission.denied', false);
            return $this->_redirectToRefererOrTree($originalId);
        }
        //modyfikacja breadcrumbów
        $this->view->adminNavigation()->modifyLastBreadcrumb('menu.category.edit', '#');
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
        if (!$form->isMine() && (null === $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($request->id))) {
            //przekierowanie na originalId (lub na tree według powyższego warunku)
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
            //zmiany zapisane
            $this->getMessenger()->addMessage('messenger.category.category.saved', true);
            return $this->_redirectToRefererOrTree($originalId);
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
        //przekierowanie na podgląd
        $this->getResponse()->redirect('cms', 'category', 'redactorPreview', ['originalId' => $category->cmsCategoryOriginalId, 'versionId' => $category->id]);
    }

    /**
     * Akcja zarządzania drzewem
     */
    public function treeAction()
    { }

    /**
     * Renderowanie fragmentu drzewa stron na podstawie parentId
     */
    public function nodeAction(Request $request)
    {
        //wyłączenie layout
        $this->view->setLayoutDisabled();
        //id węzła rodzica
        $this->view->parentId = ($request->parentId > 0) ? $request->parentId : null;
        //pobranie drzewiastej struktury stron CMS
        $this->view->categoryTree = (new \Cms\Model\CategoryModel(new CmsCategoryQuery()))->getCategoryTree($this->view->parentId);
    }

    /**
     * Tworzenie nowej strony
     */
    public function createAction(Request $request)
    {
        $this->getResponse()->setTypeJson();
        $cat = new \Cms\Orm\CmsCategoryRecord();
        $cat->name = $request->getPost()->name;
        $cat->parentId = ($request->getPost()->parentId > 0) ? $request->getPost()->parentId : null;
        $cat->order = $request->getPost()->order;
        $cat->active = false;
        $cat->status = \Cms\Orm\CmsCategoryRecord::STATUS_ACTIVE;
        if ($cat->save()) {
            $icon = '';
            $disabled = false;
            //ikona nieaktywnego wezla gdy nieaktywny
            if (!$cat->active) {
                $icon = $this->view->baseUrl . '/resource/cmsAdmin/images/folder-inactive.png';
            }
            //sprawdzenie uprawnień do węzła
            $acl = (new \CmsAdmin\Model\CategoryAclModel)->getAcl();
            if (!$acl->isAllowed($this->auth->getRoles(), $cat->id)) {
                $disabled = true;
                //ikona zablokowanego wezla gdy brak uprawnien
                $icon = $this->view->baseUrl . '/resource/cmsAdmin/images/folder-disabled.png';
            }
            return json_encode([
                'status' => true,
                'id' => $cat->id,
                'icon' => $icon,
                'disabled' => $disabled,
                'message' => $this->view->_('controller.category.create.message')
            ]);
        }
        return json_encode(['status' => false, 'error' => $this->view->_('controller.category.create.error')]);
    }

    /**
     * Zmiana nazwy strony
     */
    public function renameAction(Request $request)
    {
        $this->getResponse()->setTypeJson();
        if (null !== $cat = (new \Cms\Orm\CmsCategoryQuery)->findPk($request->getPost()->id)) {
            $name = trim($request->getPost()->name);
            if (mb_strlen($name) < 2 || mb_strlen($name) > 64) {
                return json_encode(['status' => false, 'error' => $this->view->_('controller.category.rename.validator')]);
            }
            $cat->name = $name;
            if ($cat->save()) {
                return json_encode(['status' => true, 'id' => $cat->id, 'name' => $name, 'message' => $this->view->_('controller.category.rename.message')]);
            }
        }
        return json_encode(['status' => false, 'error' => $this->view->_('controller.category.rename.error')]);
    }

    /**
     * Przenoszenie strony w drzewie
     */
    public function moveAction(Request $request)
    {
        $this->getResponse()->setTypeJson();
        //brak kategorii
        if (null === $categoryRecord = (new \Cms\Orm\CmsCategoryQuery)->findPk($request->getPost()->id)) {
            return json_encode(['status' => false, 'error' => $this->view->_('controller.category.move.error.missing')]);
        }
        //draft nie może być utworzony, ani wczytany
        if (null === $draft = (new \Cms\Model\CategoryDraft($categoryRecord))->createAndGetDraftForUser($this->auth->getId(), true)) {
            return json_encode(['status' => false, 'error' => $this->view->_('controller.category.move.error.missing')]);
        }
        //zatwierdzenie draftu
        $draft->commitVersion();
        //zmiana położenia aktywnej kategorii
        $categoryRecord->parentId = ($request->getPost()->parentId > 0) ? $request->getPost()->parentId : null;
        $categoryRecord->order = $request->getPost()->order;
        //próba zapisu
        return $categoryRecord->save() ? json_encode(['status' => true, 'id' => $categoryRecord->id, 'message' => $this->view->_('controller.category.move.message')]) : json_encode(['status' => false, 'error' => $this->view->_('controller.category.move.error')]);
    }

    /**
     * Usuwanie strony
     */
    public function deleteAction(Request $request)
    {
        $this->getResponse()->setTypeJson();
        //brak kategorii
        if (null === $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($request->getPost()->id)) {
            return json_encode(['status' => false, 'error' => $this->view->_('controller.category.delete.error')]);
        }
        //ma historię, nie możemy usunąć
        if ($category->hasHistoricalEntries()) {
            return json_encode(['status' => false, 'error' => $this->view->_('controller.category.delete.error.history')]);
        }
        try {
            //usuwanie - logika szablonu
            (new TemplateModel($category, $this->cmsSkinsetConfig))->invokeDeleteAction();
            //usuwanie rekordu
            $category->delete();
            return json_encode(['status' => true, 'message' => $this->view->_('controller.category.delete.message')]);
        } catch (\Cms\Exception\ChildrenExistException $e) { }
        return json_encode(['status' => false, 'error' => $this->view->_('controller.category.delete.error.children')]);
    }

    /**
     * Kopiowanie strony - kategorii
     */
    public function copyAction(Request $request)
    {
        $this->getResponse()->setTypeJson();
        if (null === $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($request->getPost()->id)) {
            return json_encode(['status' => false, 'error' => 'Strona nie istnieje']);
        }
        //model do kopiowania kategorii
        $copyModel = new \Cms\Model\CategoryCopy($category);
        //kopiowanie z transakcją
        if ($copyModel->copyWithTransaction()) {
            return json_encode(['status' => true, 'id' => $copyModel->getCopyRecord()->id, 'message' => $this->view->_('controller.category.copy.message')]);
        }
        return json_encode(['status' => false, 'error' => $this->view->_('controller.category.copy.error')]);
    }

    /**
     * Sprawdzanie czy kategoria ma duplikat
     * @param integer $originalId
     * @return boolean
     */
    protected function _isCategoryDuplicate($originalId)
    {
        $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($originalId);
        //znaleziono kategorię o tym samym uri
        return (null !== (new \Cms\Orm\CmsCategoryQuery)
            ->whereId()->notEquals($category->id)
            ->andFieldRedirectUri()->equals(null)
            ->andFieldStatus()->equals(\Cms\Orm\CmsCategoryRecord::STATUS_ACTIVE)
            ->andQuery((new \Cms\Orm\CmsCategoryQuery)->searchByUri($category->uri))
            ->findFirst()) && !$category->redirectUri && !$category->customUri;
    }

    /**
     * Przygotowanie drafta
     * @param \Cms\Orm\CmsCategoryRecord $category
     * @param integer $originalId
     */
    protected function _prepareDraft(\Cms\Orm\CmsCategoryRecord $category, Request $request, $originalId)
    {
        //jeśli to nie był DRAFT
        if (\Cms\Orm\CmsCategoryRecord::STATUS_DRAFT == $category->status) {
            return;
        }
        //zapis referera
        $this->_saveReferer($request, $originalId);
        //wymuszony świeży draft jeśli informacja przyszła w url, lub kategoria jest z archiwum
        $force = $request->force || (\Cms\Orm\CmsCategoryRecord::STATUS_HISTORY == $category->status);
        //draft nie może być utworzony, ani wczytany
        if (null === $draft = (new \Cms\Model\CategoryDraft($category))->createAndGetDraftForUser($this->auth->getId(), $force)) {
            $this->getMessenger()->addMessage('messenger.category.draft.fail', false);
            return $this->_redirectToRefererOrTree($originalId);
        }
        //przekierowanie do edycji DRAFTu - nowego ID
        $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $draft->id, 'originalId' => $originalId, 'uploaderId' => $draft->id]);
    }

    /**
     * Przekierowaniena na referer lub tree
     * @param integer $categoryId
     */
    protected function _redirectToRefererOrTree($categoryId)
    {
        $sessionSpace = new SessionSpace(self::SESSION_SPACE_PREFIX . $categoryId);
        //posiada zapisany referer
        if (null !== ($referer = $sessionSpace->referer)) {
            //czyszczenie sesji
            $sessionSpace->unsetAll();
            $this->getResponse()->redirectToUrl($referer);
        }
        //czyszczenie sesji
        $sessionSpace->unsetAll();
        $this->getResponse()->redirect('cmsAdmin', 'category', 'tree');
    }

    protected function _saveReferer(Request $request, $originalId)
    {
        //czyszczenie przestrzeni sesji
        $sessionSpace = new SessionSpace(self::SESSION_SPACE_PREFIX . $originalId);
        $sessionSpace->unsetAll();
        //brak refererea
        if ('' == ($referer = $request->getReferer())) {
            return;
        }
        //request referera
        $refererRequest = new Request($this->router->decodeUrl($referer));
        //moduł nieadminowy - nie zapisujemy referera
        if (false === strpos($refererRequest->getModuleName(), self::ADMIN_MODULE_SUFFIX)) {
            return;
        }
        //zgodny moduł, kontroler, akcja - nie zapisujemy referera
        if (
            $refererRequest->getModuleName() == $request->getModuleName() &&
            $refererRequest->getControllerName() == $request->getControllerName() &&
            $refererRequest->getActionName() == $request->getActionName()
        ) {
            return;
        }
        //zapis referera do sesji
        $sessionSpace->referer = $referer;
    }

}
