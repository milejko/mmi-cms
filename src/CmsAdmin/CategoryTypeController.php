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
 * Kontroler szablonów (typów artykułów)
 */
class CategoryTypeController extends Mvc\Controller {

	/**
	 * Lista typów artykułów
	 */
	public function indexAction() {
		$this->view->grid = new \CmsAdmin\Plugin\CategoryTypeGrid;
	}

	/**
	 * Edycja typu artykułu
	 */
	public function editAction() {
		$form = new \CmsAdmin\Form\CategoryType(new \Cms\Orm\CmsCategoryTypeRecord($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Szablon artykułu zapisany poprawnie', true);
			$this->getResponse()->redirect('cmsAdmin', 'categoryType');
		}
		$this->view->categoryTypeForm = $form;
		//brak id (brak atrybutów)
		if (!$this->id) {
			return;
		}
		//grid atrybutów
		$this->view->relationGrid = new \CmsAdmin\Plugin\CategoryTypeAttributeRelationGrid(['objectId' => $this->id]);
		//rekord nowej, lub edytowanej relacji
		$relationRecord = new \Cms\Orm\CmsAttributeRelationRecord($this->relationId);
		$relationRecord->object = 'cmsCategoryType';
		$relationRecord->objectId = $this->id;
		//formularz edycji
		$relationForm = new Form\CategoryTypeAttributeRelationForm($relationRecord);
		if ($relationForm->isSaved()) {
			$this->getMessenger()->addMessage('Wiązanie atrybutu zapisane poprawnie', true);
			$this->getResponse()->redirect('cmsAdmin', 'categoryType', 'edit', ['id' => $this->id]);
		}
		$this->view->relationForm = $relationForm;
	}

	/**
	 * Usuwanie szablonu
	 */
	public function deleteAction() {
		$record = (new \Cms\Orm\CmsCategoryTypeQuery)->findPk($this->id);
		if ($record && $record->delete()) {
			$this->getMessenger()->addMessage('Poprawnie usunięto typ artykułu', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'categoryType');
	}

	/**
	 * Usuwanie relacji szablon atrybut
	 */
	public function deleteAttributeRelationAction() {
		//wyszukiwanie rekordu relacji
		$record = (new \Cms\Orm\CmsAttributeRelationQuery)
			->whereObjectId()->equals($this->id)
			->findPk($this->relationId);
		//jeśli znaleziono rekord
		if ($record && $record->delete()) {
			//wyszukiwanie stron w zmienionym szablonie
			foreach ((new \Cms\Orm\CmsCategoryQuery)->whereCmsCategoryTypeId()
				->equals($this->id)
				->findPairs('id', 'id') as $categoryId) {
				//usuwanie wartości usuniętych atrybutów
				(new \Cms\Model\AttributeValueRelationModel('category', $categoryId))
					->deleteAttributeValueRelationsByAttributeId($record->cmsAttributeId);
			}
			$this->getMessenger()->addMessage('Poprawnie relację atrybutu z szablonem', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'categoryType', 'edit', ['id' => $this->id]);
	}

}
