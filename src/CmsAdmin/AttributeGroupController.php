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
 * Kontroler grup atrybutów
 */
class AttributeGroupController extends Mvc\Controller {

	/**
	 * Lista tagów
	 */
	public function indexAction() {
		$this->view->grid = new \CmsAdmin\Plugin\AttributeGroupGrid();
	}

	/**
	 * Edycja atrybutu
	 */
	public function editAction() {
		$form = new \CmsAdmin\Form\AttributeGroup(new \Cms\Orm\CmsAttributeGroupRecord($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Grupa atrybutów zapisana poprawnie', true);
			$this->getResponse()->redirect('cmsAdmin', 'attributeGroup', 'index');
		}
		$this->view->attributeGroupForm = $form;
	}

	/**
	 * Usuwanie atrybutu
	 */
	public function deleteAction() {
		$group = (new \Cms\Orm\CmsAttributeGroupQuery)->findPk($this->id);
		if ($group && $group->delete()) {
			$this->getMessenger()->addMessage('Grupa atrybutów usunięta poprawnie', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'attributeGroup', 'index');
	}

}
