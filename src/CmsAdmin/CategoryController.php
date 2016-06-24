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
		//pobranie drzewiastej struktury kategorii
		$this->view->categoryTree = (new \Cms\Model\CategoryModel)->getCategoryTree();
	}

	/**
	 * Edycja kategorii
	 */
	public function editAction() {
		$form = new \CmsAdmin\Form\Category(new \Cms\Orm\CmsCategoryRecord($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Kategoria zapisana poprawnie', true);
			$this->getResponse()->redirect('cmsAdmin', 'category', 'index');
		}
		$this->view->categoryForm = $form;
	}

	/**
	 * Usuwanie kategorii
	 */
	public function deleteAction() {
		$cat = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->id);
		if ($cat && $cat->delete()) {
			$this->getMessenger()->addMessage('Kategoria usunięta', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'category', 'index');
	}

}
