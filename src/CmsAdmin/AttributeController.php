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
 * Kontroler atrybutów
 */
class AttributeController extends Mvc\Controller {

	/**
	 * Lista tagów
	 */
	public function indexAction() {
		$this->view->grid = new \CmsAdmin\Plugin\AttributeGrid();
	}

	/**
	 * Edycja atrybutu
	 */
	public function editAction() {
		$form = new \CmsAdmin\Form\Attribute(new \Cms\Orm\CmsAttributeRecord($this->id));
		//form zapisany
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Atrybut zapisany poprawnie', true);
			$this->getResponse()->redirect('cmsAdmin', 'attribute', 'index');
		}
		//ograniczona lista
		if ($form->getRecord()->isRestricted()) {
			$this->view->valueGrid = new Plugin\AttributeValueGrid(['id' => $form->getRecord()->id]);
		}
		$this->view->attributeForm = $form;
	}

	/**
	 * Usuwanie atrybutu
	 */
	public function deleteAction() {
		$attribute = (new \Cms\Orm\CmsAttributeQuery)->findPk($this->id);
		if ($attribute && $attribute->delete()) {
			$this->getMessenger()->addMessage('Atrybut usunięty', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'attribute', 'index');
	}

}
