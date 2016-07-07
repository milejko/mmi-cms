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
 * Kontroler kategorii
 */
class CategoryController extends Mvc\Controller {

	/**
	 * Lista kategorii - prezentacja w formie drzewa
	 */
	public function indexAction() {
		//w szablonie podłączenie ajaxowego ładowania drzewka
		//zapis forma edycji
		if (null === $cat = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->id)) {
			return;
		}
		$form = (new \CmsAdmin\Form\Category($cat));
		$this->view->categoryForm = $form;
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Zmiany w kategorii zostały zapisane', true);
		}
	}
	
	/**
	 * Renderowanie fragmentu drzewa kategorii na podstawie parentId
	 */
	public function nodeAction() {
		//wyłączenie layout
		$this->view->setLayoutDisabled();
		//id węzła rodzica
		$this->view->parentId = ($this->parentId > 0)? $this->parentId : null;
		//pobranie drzewiastej struktury kategorii
		$this->view->categoryTree = (new \Cms\Model\CategoryModel)->getCategoryTree($this->view->parentId);
	}

	/**
	 * Edycja kategorii - formularz ładowany ajaxem
	 */
	public function editAction() {
		//wyłączenie layout
		$this->view->setLayoutDisabled();
		if (null !== $cat = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->getPost()->id)) {
			$this->view->categoryForm = (new \CmsAdmin\Form\Category($cat));
		}
	}
	
	/**
	 * Tworzenie nowej kategorii
	 */
	public function createAction() {
		$this->getResponse()->setTypeJson();
		$cat = new \Cms\Orm\CmsCategoryRecord();
		$cat->name = $this->getPost()->name;
		$cat->parentId = ($this->getPost()->parentId > 0)? $this->getPost()->parentId : null;
		$cat->order = $this->getPost()->order;
		$cat->active = true;
		if ($cat->save()) {
			return json_encode(['status' => true, 'id' => $cat->id, 'message' => 'Kategoria została utworzona']);
		}
		return json_encode(['status' => false, 'error' => 'Nie udało się utworzyć kategorii']);
	}
	
	/**
	 * Zmiana nazwy kategorii
	 */
	public function renameAction() {
		$this->getResponse()->setTypeJson();
		if (null !== $cat = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->getPost()->id)) {
			$name = trim($this->getPost()->name);
			if (mb_strlen($name) < 2) {
				return json_encode(['status' => false, 'error' => 'Nazwa kategorii jest zbyt krótka - wymagane minimum to 2 znaki']);
			}
			if (mb_strlen($name) > 64) {
				return json_encode(['status' => false, 'error' => 'Nazwa kategorii jest zbyt długa - maksimum to 64 znaki']);
			}
			$cat->name = $name;
			if ($cat->save() !== false) {
				return json_encode(['status' => true, 'id' => $cat->id, 'name' => $name, 'message' => 'Nazwa kategorii została zmieniona']);
			}
		}
		return json_encode(['status' => false, 'error' => 'Nie udało się zmienić nazwy kategorii']);
	}
	
	/**
	 * Przenoszenie kategorii w drzewie
	 */
	public function moveAction() {
		$this->getResponse()->setTypeJson();
		if (null !== $cat = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->getPost()->id)) {
			$cat->parentId = ($this->getPost()->parentId > 0)? $this->getPost()->parentId : null;
			$cat->order = $this->getPost()->order;
			if ($cat->save() !== false) {
				return json_encode(['status' => true, 'id' => $cat->id, 'message' => 'Kategoria została przeniesiona']);
			}
		}
		return json_encode(['status' => false, 'error' => 'Nie udało się przenieść kategorii']);
	}

	/**
	 * Usuwanie kategorii
	 */
	public function deleteAction() {
		$this->getResponse()->setTypeJson();
		$cat = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->getPost()->id);
		if ($cat && $cat->delete()) {
			return json_encode(['status' => true, 'message' => 'Kategoria została usunięta']);
		}
		return json_encode(['status' => false, 'error' => 'Nie udało się usunąć kategorii']);
	}

}
