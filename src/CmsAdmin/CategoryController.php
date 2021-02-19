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
use Cms\Model\SkinsetModel;
use Cms\Model\TemplateModel;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use CmsAdmin\Form\CategoryForm;
use CmsAdmin\Form\CategoryMoveForm;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;
use Mmi\Mvc\Router;
use Mmi\Security\AuthInterface;

/**
 * Kontroler kategorii - stron CMS
 */
class CategoryController extends Controller
{
    //przedrostek brakującego widgeta
    const MISSING_WIDGET_MESSENGER_PREFIX = 'messenger.widget.missing.';

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
    public function indexAction(Request $request)
    {
        $parentCategory = null;
        //wyszukiwanie parenta
        if ($request->parentId && (null === $parentCategory = (new CmsCategoryQuery)->findPk($request->parentId))) {
            //błędny parent
            $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        $breadcrumbs = [];
        //generowanie breadcrumbów
        while ($parentCategory) {
            $breadcrumbs[] = $parentCategory;
            $parentCategory = $parentCategory->getParentRecord();
        }
        $this->view->breadcrumbs = \array_reverse($breadcrumbs);
        //model skóry skinset do widoku
        $this->view->skinset = new SkinsetModel($this->cmsSkinsetConfig);
        //znalezione kategorie do widoku
        $this->view->categories = (new \Cms\Orm\CmsCategoryQuery)
            ->whereStatus()->equals(\Cms\Orm\CmsCategoryRecord::STATUS_ACTIVE)
            ->whereParentId()->equals($request->parentId ? $request->parentId : null)
            ->orderAscOrder()
            ->find();
    }

    /**
     * Lista stron CMS - edycja
     */
    public function editAction(Request $request)
    {
        //brak id - tworzenie nowej kategorii
        if (!$request->id) {
            $category = new CmsCategoryRecord();
            $category->status = CmsCategoryRecord::STATUS_ACTIVE;
            $category->template = $request->template;
            $category->parentId = $request->parentId ? $request->parentId : null;
            $category->cmsAuthId = $this->auth->getId();
            $category->save();
            $request->id = $category->id;
        }
        //wyszukiwanie kategorii
        if (null === $category = (new CmsCategoryQuery)->findPk($request->id)) {
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
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'index', ['parentId' => $category->parentId]);
        }
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
        if (!$form->isMine() && (null === $category = (new CmsCategoryQuery)->findPk($request->id))) {
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
            //zmiany zapisane
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
        //przekierowanie na podgląd
        $this->getResponse()->redirect('cms', 'category', 'redactorPreview', ['originalId' => $category->cmsCategoryOriginalId, 'versionId' => $category->id]);
    }

    /**
     * Przenoszenie strony w drzewie
     */
    public function moveAction(Request $request)
    {
        if (null === $category = (new CmsCategoryQuery)->findPk($request->id)) {
            //brak strony
            $this->getMessenger()->addMessage('controller.category.delete.error', false);
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        $form = new CategoryMoveForm($category);
        if ($form->isSaved()) {
            //brak strony
            $this->getMessenger()->addMessage('controller.category.delete.error', true);
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'index', ['parentId' => $category->parentId]);
        }
        $this->view->form = $form;
    }

    /**
     * Usuwanie strony
     */
    public function deleteAction(Request $request)
    {
        if (null === $category = (new CmsCategoryQuery)->findPk($request->id)) {
            //brak strony
            $this->getMessenger()->addMessage('controller.category.delete.error', false);
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //usuwanie - logika szablonu
        (new TemplateModel($category, $this->cmsSkinsetConfig))->invokeDeleteAction();
        //usuwanie rekordu
        $category->status = CmsCategoryRecord::STATUS_DELETED;
        $category->save();
        $this->getMessenger()->addMessage('controller.category.delete.message', true);
        $this->getResponse()->redirect('cmsAdmin', 'category', 'index', ['parentId' => $category->parentId]);
    }

    /**
     * Kopiowanie strony - kategorii
     */
    public function copyAction(Request $request)
    {
        if (null === $category = (new CmsCategoryQuery)->findPk($request->id)) {
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
        //sprawdzanie istnienia danych sortujących
        if (null === ($order = $this->getRequest()->getPost()->value)) {
            return 'Sortowanie nie powiodło się';
        }
        //weryfikacja danych sortujących
        if (!is_array($order) || empty($order)) {
            return 'Sortowanie nie powiodło się';
        }
        //sortowanie
        foreach ($order as $order => $id) {
            //brak rekordu o danym ID
            if (null === ($record = (new CmsCategoryQuery())->findPk($id))) {
                continue;
            }
            //ustawianie kolejności i zapis
            $record->order = $order;
            $record->save();
        }
        return '';
    }

    /**
     * Sprawdzanie czy kategoria ma duplikat
     * @param integer $originalId
     * @return boolean
     */
    protected function _isCategoryDuplicate($originalId)
    {
        $category = (new CmsCategoryQuery)->findPk($originalId);
        //znaleziono kategorię o tym samym uri
        return (null !== (new CmsCategoryQuery)
            ->whereId()->notEquals($category->id)
            ->andFieldRedirectUri()->equals(null)
            ->andFieldStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
            ->andQuery((new CmsCategoryQuery)->searchByUri($category->uri))
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

}
