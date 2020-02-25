<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use App\Registry;
use Cms\Model\SkinModel;
use Cms\Model\SkinsetModel;
use Cms\Orm\CmsCategoryWidgetQuery;
use Mmi\App\FrontController;

/**
 * Kontroler kategorii - stron CMS
 */
class CategoryController extends Mvc\Controller
{

    //prefiks przestrzeni nazw w sesji
    const SESSION_SPACE_PREFIX = 'category-edit-';
    //parametry edycja kategorii
    const EDIT_MVC_PARAMS = 'cmsAdmin/category/edit';

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
    public function editAction()
    {
        //brak id przekierowanie na drzewo
        if (!$this->id) {
            $this->getResponse()->redirect('cmsAdmin', 'category', 'tree');
        }
        //wyszukiwanie kategorii
        if (null === $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->id)) {
            //przekierowanie na originalId (lub na tree według powyższego warunku)
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $this->originalId]);
        }
        //zapisywanie oryginalnego id
        $originalId = $category->cmsCategoryOriginalId ? $category->cmsCategoryOriginalId : $category->id;
        //przygotowanie draftu (lub przekierowanie)
        $this->_prepareDraft($category, $originalId);
        //draft ma obcego właściciela
        if ($category->cmsAuthId != \App\Registry::$auth->getId()) {
            throw new \Mmi\Mvc\MvcForbiddenException('Category not allowed');
        }
        //sprawdzanie czy nie duplikat
        $this->view->duplicateAlert = $this->_isCategoryDuplicate($originalId);
        //sprawdzenie uprawnień do edycji węzła kategorii
        if (!(new \CmsAdmin\Model\CategoryAclModel)->getAcl()->isAllowed(\App\Registry::$auth->getRoles(), $originalId)) {
            $this->getMessenger()->addMessage('messenger.category.permission.denied', false);
            $this->getResponse()->redirect('cmsAdmin', 'category', 'tree');
        }
        //modyfikacja breadcrumbów
        $this->view->adminNavigation()->modifyLastBreadcrumb('menu.category.edit', '#');
        //konfiguracja kategorii
        $form = (new \CmsAdmin\Form\Category($category));
        //sprawdzenie czy kategoria nadal istnieje (form robi zapis - to trwa)
        if (!$form->isMine() && (null === $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->id))) {
            //przekierowanie na originalId (lub na tree według powyższego warunku)
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $this->originalId]);
        }
        //dekoracja formularza na bazie wybranego szablonu
        if ($category->template) {
            $this->view->template = (new SkinsetModel(Registry::$config->skinset))
                ->getTemplateConfigByKey($category->template);
        }
        //form do widoku
        $this->view->categoryForm = $form;
        //jeśli nie było posta
        if (!$form->isMine()) {
            //grid z listą wersji historycznych
            $this->view->historyGrid = new \CmsAdmin\Plugin\CategoryHistoryGrid(['originalId' => $category->cmsCategoryOriginalId]);
            return;
        }
        //błędy zapisu
        if ($form->isMine() && !$form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.category.form.errors', false);
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $category->id, 'originalId' => $category->cmsCategoryOriginalId, 'uploaderId' => $category->id]);
        }
        //zatwierdzenie zmian - commit
        if ($form->isSaved() && $form->getElement('commit')->getValue()) {
            //zmiany zapisane
            $this->getMessenger()->addMessage('messenger.category.category.saved', true);
            $this->_redirectAfterEdit($category);
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
     * Akcja podglądu widgeta
     */
    public function widgetAction()
    { }

    /**
     * Renderowanie fragmentu drzewa stron na podstawie parentId
     */
    public function nodeAction()
    {
        //wyłączenie layout
        FrontController::getInstance()->getView()->setLayoutDisabled();
        //id węzła rodzica
        $this->view->parentId = ($this->parentId > 0) ? $this->parentId : null;
        //pobranie drzewiastej struktury stron CMS
        $this->view->categoryTree = (new \Cms\Model\CategoryModel)->getCategoryTree($this->view->parentId);
    }

    /**
     * Tworzenie nowej strony
     */
    public function createAction()
    {
        $this->getResponse()->setTypeJson();
        $cat = new \Cms\Orm\CmsCategoryRecord();
        $cat->name = $this->getPost()->name;
        $cat->parentId = ($this->getPost()->parentId > 0) ? $this->getPost()->parentId : null;
        $cat->order = $this->getPost()->order;
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
            if (!$acl->isAllowed(\App\Registry::$auth->getRoles(), $cat->id)) {
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
    public function renameAction()
    {
        $this->getResponse()->setTypeJson();
        if (null !== $cat = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->getPost()->id)) {
            $name = trim($this->getPost()->name);
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
    public function moveAction()
    {
        $this->getResponse()->setTypeJson();
        //brak kategorii
        if (null === $masterCategory = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->getPost()->id)) {
            return json_encode(['status' => false, 'error' => $this->view->_('controller.category.move.error.missing')]);
        }
        //domyślnie nie ma drafta - alias
        $draft = $masterCategory;
        //zmiana parenta tworzy draft
        if ($this->getPost()->parentId != $masterCategory->parentId) {
            //tworzenie draftu
            $draft = (new \Cms\Model\CategoryDraft($masterCategory))->createAndGetDraftForUser(\App\Registry::$auth->getId(), true);
        }
        //zapis kolejności
        $draft->parentId = ($this->getPost()->parentId > 0) ? $this->getPost()->parentId : null;
        $draft->order = $this->getPost()->order;
        //próba zapisu
        return ($draft->save() && $draft->commitVersion()) ? json_encode(['status' => true, 'id' => $masterCategory->id, 'message' => $this->view->_('controller.category.move.message')]) : json_encode(['status' => false, 'error' => $this->view->_('controller.category.move.error')]);
    }

    /**
     * Usuwanie strony
     */
    public function deleteAction()
    {
        $this->getResponse()->setTypeJson();
        $cat = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->getPost()->id);
        //ma historię, nie możemy usunąć
        if ($cat->hasHistoricalEntries()) {
            return json_encode(['status' => false, 'error' => $this->view->_('controller.category.delete.error.history')]);
        }
        try {
            if ($cat && $cat->delete()) {
                return json_encode(['status' => true, 'message' => $this->view->_('controller.category.delete.message')]);
            }
        } catch (\Cms\Exception\ChildrenExistException $e) {

            return json_encode(['status' => false, 'error' => $this->view->_('controller.category.delete.error.children')]);
        }
        return json_encode(['status' => false, 'error' => $this->view->_('controller.category.delete.error')]);
    }

    /**
     * Kopiowanie strony - kategorii
     */
    public function copyAction()
    {
        $this->getResponse()->setTypeJson();
        if (null === $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->getPost()->id)) {
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
    private function _isCategoryDuplicate($originalId)
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

    private function _prepareDraft(\Cms\Orm\CmsCategoryRecord $category, $originalId)
    {
        //jeśli to nie był DRAFT
        if (\Cms\Orm\CmsCategoryRecord::STATUS_DRAFT == $category->status) {
            return;
        }
        //sprawdzenie referera
        $referer = $this->getRequest()->getReferer();
        //wymuszony świeży draft jeśli informacja przyszła w url, lub kategoria jest z archiwum
        $force = $this->force || (\Cms\Orm\CmsCategoryRecord::STATUS_HISTORY == $category->status);
        //draft nie może być utworzony, ani wczytany
        if (null === $draft = (new \Cms\Model\CategoryDraft($category))->createAndGetDraftForUser(\App\Registry::$auth->getId(), $force)) {
            $this->getMessenger()->addMessage('messenger.category.draft.fail', false);
            $this->getResponse()->redirectToUrl($referer);
        }
        //ustawienie referera
        $this->_setReferrer($referer, $originalId);
        //przekierowanie do edycji DRAFTu - nowego ID
        $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $draft->id, 'originalId' => $originalId, 'uploaderId' => $draft->id]);
    }

    /**
     * Przekierowanie po zakończeniu edycji
     */
    private function _redirectAfterEdit(\Cms\Orm\CmsCategoryRecord $category)
    {
        //referer
        $referer = $this->_getReferrer();
        //jeśli istnieje referer
        if ($referer) {
            $this->getResponse()->redirectToUrl($referer);
        }
        //przekierowanie na stronę frontową kategorii jeśli wskazana
        if ($category) {
            $this->getResponse()->redirectToUrl($category->getUrl());
        }
        $this->getResponse()->redirect('cmsAdmin', 'category', 'tree');
    }

    /**
     * Pobranie referera
     * @return string
     */
    private function _getReferrer()
    {
        //powoływanie przestrzeni nazw
        $space = new \Mmi\Session\SessionSpace(self::SESSION_SPACE_PREFIX . $this->originalId);
        //pobranie referera
        $referer = $space->referer;
        //usunięcie
        $space->unsetAll();
        return $referer;
    }

    /**
     * Ustawianie referer'a do sesji
     * @param string $referer
     */
    private function _setReferrer($referer, $id)
    {
        //brak referera lub referer kieruje na stronę edycji
        if (!$referer || strpos($referer, self::EDIT_MVC_PARAMS)) {
            return;
        }
        if (strpos($referer, 'cms-content-preview')) {
            return;
        }
        $space = new \Mmi\Session\SessionSpace(self::SESSION_SPACE_PREFIX . $id);
        $space->referer = $referer;
    }
}
