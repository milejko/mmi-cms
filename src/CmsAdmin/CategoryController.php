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
        //wyszukiwanie kategorii
        if (null === $cat = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->id)) {
            return $this->originalId ? $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $this->originalId]) : null;
        }
        //zapisywanie oryginalnego typu
        $originalType = $cat->cmsCategoryTypeId;
        $originalId = $cat->cmsCategoryOriginalId ? $cat->cmsCategoryOriginalId : $cat->id;
        //jeśli to nie był DRAFT
        if ($cat->status != \Cms\Orm\CmsCategoryRecord::STATUS_DRAFT) {
            //wymuszony świeży draft jeśli informacja przyszła w url, lub kategoria jest z archiwum
            $force = $this->force || ($cat->status == \Cms\Orm\CmsCategoryRecord::STATUS_HISTORY);
            //draft nie może być utworzony, ani wczytany
            if (null === $draft = (new \Cms\Model\CategoryDraft($cat))->createAndGetDraftForUser(\App\Registry::$auth->getId(), $force)) {
                $this->getMessenger()->addMessage('Nie udało się utworzyć wersji roboczej, spróbuj ponownie', false);
                return;
            }
            //przekierowanie do edycji DRAFTu - nowego ID
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $draft->id, 'originalId' => $originalId, 'uploaderId' => $draft->id]);
        }
        //draft ma obcego właściciela
        if ($cat->cmsAuthId != \App\Registry::$auth->getId()) {
            throw new \Mmi\Mvc\MvcForbiddenException('Category not allowed');
        }
        //znaleziono kategorię o tym samym uri
        if ($this->_isCategoryDuplicate($originalId)) {
            //alarm o duplikacie
            $this->view->duplicateAlert = true;
        }
        //sprawdzenie uprawnień do edycji węzła kategorii
        if (!(new \CmsAdmin\Model\CategoryAclModel)->getAcl()->isAllowed(\App\Registry::$auth->getRoles(), $originalId)) {
            $this->getMessenger()->addMessage('Nie posiadasz uprawnień do edycji wybranej strony', false);
            //redirect po zmianie (zmienią się atrybuty)
            $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //konfiguracja kategorii
        $form = (new \CmsAdmin\Form\Category($cat));
        //zapis
        if ($form->isMine() && !$form->isSaved()) {
            $this->getMessenger()->addMessage('Zmiany nie zostały zapisane, formularz zawiera błędy', false);
        }
        //po zapisie jeśli wybrany commit
        if ($form->isSaved() && $form->getElement('commit')->getValue()) {
            //zmiany zapisane
            $this->getMessenger()->addMessage('Zmiany zostały zapisane', true);
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $form->getRecord()->cmsCategoryOriginalId]);
        }
        //wybrano zapis i podgląd
        if ($form->isSaved() && $form->getElement('submit')->getValue()) {
            //przekierowanie na podgląd
            $this->getResponse()->redirectToUrl($cat->getUrl() . '?originalId=' . $cat->cmsCategoryOriginalId . '&versionId=' . $cat->id);
        }
        //zapisany form ze zmianą kategorii
        if ($form->isSaved() && $originalType != $form->getRecord()->cmsCategoryTypeId) {
            //zmiany zapisane
            $this->getMessenger()->addMessage('Szablon strony został zmieniony', true);
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $cat->id, 'originalId' => $cat->cmsCategoryOriginalId]);
        }
        //kategoria do widoku
        $this->view->category = $cat;
        //form do widoku
        $this->view->categoryForm = $form;
        //grid z listą wersji historycznych
        $this->view->historyGrid = new \CmsAdmin\Plugin\CategoryHistoryGrid(['originalId' => $cat->cmsCategoryOriginalId]);
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
            return json_encode(['status' => true, 'id' => $cat->id, 'message' => 'Strona została utworzona']);
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

}
