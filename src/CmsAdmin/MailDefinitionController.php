<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

class MailDefinitionController extends Mvc\Controller {

	public function indexAction() {
		$grid = new \Cms\Plugin\MailDefinitionGrid();
		$this->view->grid = $grid;
	}

	public function editAction() {
		$form = new \CmsAdmin\Form\Mail\Definition(new \Cms\Orm\Mail\Definition\Record($this->id));
		if ($form->isSaved()) {
			$this->getHelperMessenger()->addMessage('Poprawnie zapisano definicję maila', true);
			$this->getResponse()->redirect('cmsAdmin', 'mailDefinition');
		}
		$this->view->definitionForm = $form;
	}

	public function deleteAction() {
		$definition = \Cms\Orm\Mail\Definition\Query::factory()->findPk($this->id);
		try {
			if ($definition && $definition->delete()) {
				$this->getHelperMessenger()->addMessage('Poprawnie skasowano definicję maila');
			}
		} catch (\Mmi\Db\Exception $e) {
			$this->getHelperMessenger()->addMessage('Nie można usunąć definicji maila, istnieją powiazane wiadomosci w kolejce', false);
		}
		$this->getResponse()->redirect('cmsAdmin', 'mailDefinition');
	}

}
