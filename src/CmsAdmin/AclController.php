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
 * Kontroler listy uprawnień do modułów
 */
class AclController extends Mvc\Controller {

	/**
	 * Lista uprawnień
	 */
	public function indexAction() {
		$this->view->roles = (new \Cms\Orm\CmsRoleQuery)->find();
		if (!$this->roleId && count($this->view->roles)) {
			$this->getResponse()->redirect('cmsAdmin', 'acl', 'index', ['roleId' => $this->view->roles[0]->id]);
		}
		if ($this->roleId) {
			$this->view->rules = (new \Cms\Orm\CmsAclQuery)->whereCmsRoleId()->equals($this->roleId)->find();
			$this->view->options = [null => '---'] + \CmsAdmin\Model\Reflection::getOptionsWildcard();
		}
		$roleForm = new \CmsAdmin\Form\Role($roleRecord = new \Cms\Orm\CmsRoleRecord());
		if ($roleForm->isMine() && $roleForm->isSaved()) {
			$this->getMessenger()->addMessage('Poprawnie zapisano rolę', true);
			$this->getResponse()->redirect('cmsAdmin', 'acl', 'index', ['roleId' => $roleRecord->id]);
		}
		$aclForm = new \CmsAdmin\Form\Acl(new \Cms\Orm\CmsAclRecord());
		if ($aclForm->isMine() && $aclForm->isSaved()) {
			$this->getMessenger()->addMessage('Poprawnie zapisano regułę', true);
			$this->getResponse()->redirect('cmsAdmin', 'acl', 'index', ['roleId' => $this->roleId]);
		}
		$this->view->roleForm = $roleForm;
		$this->view->aclForm = $aclForm;
	}

	/**
	 * Akcja usuwania roli
	 */
	public function deleteRoleAction() {
		//wyszukiwanie i usuwanie roli
		if ((null !== $role = (new \Cms\Orm\CmsRoleQuery)->findPk($this->id))) {
			$this->getMessenger()->addMessage(($deleteResult = (bool)$role->delete()) ? 'Poprawnie usunięto rolę' : 'Rola przypisana do użytkownika - usunięcie niemożliwe', $deleteResult);
		}
		//redirect
		$this->getResponse()->redirect('cmsAdmin', 'acl', 'index', ['roleId' => $this->id]);
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
		$rule = (new \Cms\Orm\CmsAclQuery)->findPk($this->id);
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
		if (!($this->getPost()->selected) || count($params) != 3) {
			return $msg;
		}
		$record = (new \Cms\Orm\CmsAclQuery)->findPk($params[2]);
		if (!$record) {
			return $msg;
		}
		//zmiana zasobu
		if ($params[1] == 'resource') {
			$resource = [];
			parse_str($this->getPost()->selected, $resource);
			$record->module = strtolower($resource['module']);
			$record->controller = isset($resource['controller']) ? strtolower($resource['controller']) : null;
			$record->action = isset($resource['action']) ? strtolower($resource['action']) : null;
		} else {
			//zmiana uprawnienia z allow na deny lub odwrotnie
			$record->access = $this->getPost()->selected == 'allow' ? 'allow' : 'deny';
		}
		$record->save();
		return 1;
	}

}
