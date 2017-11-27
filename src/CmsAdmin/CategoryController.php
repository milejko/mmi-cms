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
            return;
        }
        //jeśli to nie był DRAFT
        if ($cat->status != \Cms\Orm\CmsCategoryRecord::STATUS_DRAFT) {
            //tworzymy wersję roboczą - DRAFT
            $draftModel = new \Cms\Model\CategoryDraft($cat);
            if (!$draftModel->createWithTransaction()) {
                $this->getMessenger()->addMessage('Nie udało się utworzyć wersji roboczej, spróbuj ponownie', false);
                return;
            }
            //przekierowanie do edycji DRAFTu - nowego ID
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $draftModel->getCopyRecord()->getPk()]);
        }
        //znaleziono kategorię o tym samym uri
        if (null !== (new \Cms\Orm\CmsCategoryQuery)
                ->whereId()->notEquals($cat->id)
                ->andFieldRedirectUri()->equals(null)
                ->andFieldStatus()->equals(\Cms\Orm\CmsCategoryRecord::STATUS_ACTIVE)
                ->andQuery((new \Cms\Orm\CmsCategoryQuery)->searchByUri($cat->uri))
                ->findFirst() && !$cat->redirectUri) {
            $this->view->duplicateAlert = true;
        }
        //sprawdzenie uprawnień do edycji węzła kategorii
        if (!(new \CmsAdmin\Model\CategoryAclModel)->getAcl()->isAllowed(\App\Registry::$auth->getRoles(), $cat->cmsCategoryOriginalId ? $cat->cmsCategoryOriginalId : $cat->id)) {
            $this->getMessenger()->addMessage('Nie posiadasz uprawnień do edycji wybranej strony', false);
            //redirect po zmianie (zmienią się atrybuty)
            $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //zapis początkowej kategorii
        $cmsCategoryTypeId = $cat->cmsCategoryTypeId;
        //konfiguracja kategorii
        $form = (new \CmsAdmin\Form\Category($cat));
        //zapis
        if ($form->isMine() && !$form->isSaved()) {
            $this->getMessenger()->addMessage('Zmiany nie zostały zapisane, formularz zawiera błędy', false);
        }
        //zmiana kategorii
        if ($cmsCategoryTypeId != $form->getRecord()->cmsCategoryTypeId) {
            //redirect po zmianie (zmienią się atrybuty)
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $form->getRecord()->id]);
        }
        //kategoria do widoku
        $this->view->category = $cat;
        //form do widoku
        $this->view->categoryForm = $form;
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
            return json_encode(['status' => true, 'id' => $copyModel->getCopyRecord()->getPk(), 'message' => 'Strona została skopiowana']);
        }
        return json_encode(['status' => false, 'error' => 'Nie udało się skopiować strony']);
    }

}
