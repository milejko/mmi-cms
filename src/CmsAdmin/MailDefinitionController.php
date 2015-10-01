<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

/**
 * Definicje szablonów maili
 */
class MailDefinitionController extends Mvc\Controller {

	/**
	 * Lista szablonów
	 */
	public function indexAction() {
		$grid = new \CmsAdmin\Plugin\MailDefinitionGrid();
		$this->view->grid = $grid;
	}

	/**
	 * Edycja szablonu
	 */
	public function editAction() {
		$form = new \CmsAdmin\FOrm\CmsMailDefinition(new \Cms\Orm\CmsMailDefinition\Record($this->id));
		if ($form->isSaved()) {
			$this->getMessenger()->addMessage('Poprawnie zapisano definicję maila', true);
			$this->getResponse()->redirect('cmsAdmin', 'mailDefinition');
		}
		$this->view->definitionForm = $form;
	}

	/**
	 * Usuwanie szablonu
	 */
	public function deleteAction() {
		$definition = \Cms\Orm\CmsMailDefinitionQuery::factory()->findPk($this->id);
		try {
			if ($definition && $definition->delete()) {
				$this->getMessenger()->addMessage('Poprawnie skasowano definicję maila');
			}
		} catch (\Mmi\Db\Exception $e) {
			$this->getMessenger()->addMessage('Nie można usunąć definicji maila, istnieją powiazane wiadomosci w kolejce', false);
		}
		$this->getResponse()->redirect('cmsAdmin', 'mailDefinition');
	}

}
