<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Controller;

/**
 * Kontroler listy uprawnień do modułów
 */
class Acl extends Action {

	/**
	 * Lista uprawnień
	 */
	public function indexAction() {
		$this->view->roles = \Cms\Orm\Role\Query::factory()->find();
		if ($this->roleId) {
			$this->view->rules = \Cms\Model\Acl::getMultioptionsByRoleId($this->roleId);
			$this->view->options = [null => '---'] + \CmsAdmin\Model\Reflection::getOptionsWildcard();
		}
		$roleForm = new \CmsAdmin\Form\Role($roleRecord = new \Cms\Orm\Role\Record());
		if ($roleForm->isMine() && $roleForm->isSaved()) {
			$this->getHelperMessenger()->addMessage('Poprawnie zapisano rolę', true);
			$this->getResponse()->redirect('cmsAdmin', 'acl', 'index', ['roleId' => $roleRecord->id]);
		}
		$aclForm = new \CmsAdmin\Form\Acl(new \Cms\Orm\Acl\Record());
		if ($aclForm->isMine() && $aclForm->isSaved()) {
			$this->getHelperMessenger()->addMessage('Poprawnie zapisano regułę', true);
			$this->getResponse()->redirect('cmsAdmin', 'acl', 'index', ['roleId' => $this->roleId]);
		}
		$this->view->roleForm = $roleForm;
		$this->view->aclForm = $aclForm;
	}

	/**
	 * Kasowanie uprawnienia (do AJAXA)
	 * @return int
	 */
	public function deleteAction() {
		$this->getResponse()->setTypePlain();
		//nie można skasować
		if (!($this->id > 0)) {
			return 0;
		}
		$rule = \Cms\Orm\Acl\Query::factory()->findPk($this->id);
		//skasowane
		if ($rule && $rule->delete()) {
			return 1;
		}
	}

	/**
	 * Aktualizacja uprawnień (do AJAXA)
	 * @return int
	 */
	public function updateAction() {
		$msg = $this->view->getTranslate()->_('Zmiana właściwości nie powiodła się.');
		$this->getResponse()->setTypePlain();
		$params = explode('-', $this->id);
		//błędne dane wejściowe
		if (!($this->value) || count($params) != 3) {
			return $msg;
		}
		$record = \Cms\Orm\Acl\Query::factory()->findPk($params[2]);
		if (!$record) {
			return;
		}
		//zmiana zasobu
		if ($params[1] == 'resource') {
			$resource = explode(':', $this->value);
			$record->module = strtolower($resource[0]);
			$record->controller = isset($resource[1]) ? strtolower($resource[1]) : null;
			$record->action = isset($resource[2]) ? strtolower($resource[2]) : null;
		} else {
			//zmiana uprawnienia z allow na deny lub odwrotnie
			$record->access = $this->value == 'allow' ? 'allow' : 'deny';
		}
		$record->save();
		return 1;
	}

}
