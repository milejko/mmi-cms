<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

/**
 * Kontroler kategorii - stron CMS
 */
class CategoryController extends Mvc\Controller
{

    //prefiks przestrzeni nazw w sesji
    CONST SESSION_SPACE_PREFIX = 'category-edit-';
    //parametry edycja kategorii
    CONST EDIT_MVC_PARAMS = 'cmsAdmin/category/edit';

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
            $this->getMessenger()->addMessage('Nie posiadasz uprawnień do edycji wybranej strony', false);
            $this->getResponse()->redirect('cmsAdmin', 'category', 'tree');
        }
        //konfiguracja kategorii
        $form = (new \CmsAdmin\Form\Category($category));
        //sprawdzenie czy kategoria nadal istnieje (form robi zapis - to trwa)
        if (!$form->isMine() && (null === $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->id))) {
            //przekierowanie na originalId (lub na tree według powyższego warunku)
            return $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $this->originalId]);
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
            $this->getMessenger()->addMessage('Zmiany nie zostały zapisane, formularz zawiera błędy', false);
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $category->id, 'originalId' => $category->cmsCategoryOriginalId, 'uploaderId' => $category->id]);
        }
        //zatwierdzenie zmian - commit
        if ($form->isSaved() && $form->getElement('commit')->getValue()) {
            //zmiany zapisane
            $this->getMessenger()->addMessage('Strona została zapisana', true);
            $this->_redirectAfterEdit($category);
        }
        //zapisany form ze zmianą kategorii
        if ($form->isSaved() && 'type' == $form->getElement('submit')->getValue()) {
            //zmiany zapisane
            $this->getMessenger()->addMessage('Szablon strony został zmieniony', true);
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $category->id, 'originalId' => $category->cmsCategoryOriginalId, 'uploaderId' => $category->id]);
        }
        //przekierowanie na podgląd
        $this->getResponse()->redirect('cms', 'category', 'redactorPreview', ['originalId' => $category->cmsCategoryOriginalId, 'versionId' => $category->id]);
    }

    /**
     * Akcja zarządzania drzewem
     */
    public function treeAction()
    {

    }

    /**
     * Akcja podglądu widgeta
     */
    public function widgetAction()
    {
        return (new \Cms\CategoryController($this->getRequest()))->widgetAction();
    }

    /**
     * Renderowanie fragmentu drzewa stron na podstawie parentId
     */
    public function nodeAction()
    {
        //wyłączenie layout
        $this->view->setLayoutDisabled();
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
                'message' => 'Strona została utworzona'
            ]);
        }
        return json_encode(['status' => false, 'error' => 'Nie udało się utworzyć strony']);
    }

    /**
     * Zmiana nazwy strony
     */
    public function renameAction()
    {
        $this->getResponse()->setTypeJson();
        if (null !== $cat = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->getPost()->id)) {
            $name = trim($this->getPost()->name);
            if (mb_strlen($name) < 2) {
                return json_encode(['status' => false, 'error' => 'Nazwa strony jest zbyt krótka - wymagane minimum to 2 znaki']);
            }
            if (mb_strlen($name) > 64) {
                return json_encode(['status' => false, 'error' => 'Nazwa strony jest zbyt długa - maksimum to 64 znaki']);
            }
            $cat->name = $name;
            if ($cat->save() !== false) {
                return json_encode(['status' => true, 'id' => $cat->id, 'name' => $name, 'message' => 'Nazwa strony została zmieniona']);
            }
        }
        return json_encode(['status' => false, 'error' => 'Nie udało się zmienić nazwy strony']);
    }

    /**
     * Przenoszenie strony w drzewie
     */
    public function moveAction()
    {
        $this->getResponse()->setTypeJson();
        if (null !== $cat = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->getPost()->id)) {
            $cat->parentId = ($this->getPost()->parentId > 0) ? $this->getPost()->parentId : null;
            $cat->order = $this->getPost()->order;
            if ($cat->save() !== false) {
                return json_encode(['status' => true, 'id' => $cat->id, 'message' => 'Strona została przeniesiona']);
            }
        }
        return json_encode(['status' => false, 'error' => 'Nie udało się przenieść strony']);
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
            return json_encode(['status' => false, 'error' => 'Strona nie może być usunięta, gdyż posiada wersje archiwalne']);
        }
        try {
            if ($cat && $cat->delete()) {
                return json_encode(['status' => true, 'message' => 'Strona została usunięta']);
            }
        } catch (\Cms\Exception\ChildrenExistException $e) {
            return json_encode(['status' => false, 'error' => 'Nie można usunąć strony zawierającej strony podrzędne']);
        }
        return json_encode(['status' => false, 'error' => 'Nie udało się usunąć strony']);
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
            return json_encode(['status' => true, 'id' => $copyModel->getCopyRecord()->id, 'message' => 'Strona została skopiowana']);
        }
        return json_encode(['status' => false, 'error' => 'Nie udało się skopiować strony']);
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
                ->findFirst()) && !$category->redirectUri;
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
            $this->getMessenger()->addMessage('Nie udało się utworzyć wersji roboczej, spróbuj ponownie', false);
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
