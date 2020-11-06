<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use CmsAdmin\Model\Reflection;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;

/**
 * Kontroler listy uprawnień do modułów
 */
class AclController extends Controller
{

    /**
     * Lista uprawnień
     */
    public function indexAction(Request $request)
    {
        $this->view->roles = (new \Cms\Orm\CmsRoleQuery)->find();
        if (!$request->roleId && count($this->view->roles)) {
            $this->getResponse()->redirect('cmsAdmin', 'acl', 'index', ['roleId' => $this->view->roles[0]->id]);
        }
        if ($request->roleId) {
            $this->view->rules = (new \Cms\Orm\CmsAclQuery)->whereCmsRoleId()->equals($request->roleId)->find();
            $this->view->options = [null => '---'] + (new Reflection)->getOptionsWildcard();
        }
        $roleForm = new \CmsAdmin\Form\Role($roleRecord = new \Cms\Orm\CmsRoleRecord());
        if ($roleForm->isMine() && $roleForm->isSaved()) {
            $this->getMessenger()->addMessage('messenger.acl.role.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'acl', 'index', ['roleId' => $roleRecord->id]);
        }
        $aclForm = new \CmsAdmin\Form\Acl(new \Cms\Orm\CmsAclRecord());
        if ($aclForm->isMine() && $aclForm->isSaved()) {
            $this->getMessenger()->addMessage('messenger.acl.rule.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'acl', 'index', ['roleId' => $request->roleId]);
        }
        $this->view->roleForm = $roleForm;
        $this->view->aclForm = $aclForm;
    }

    /**
     * Akcja usuwania roli
     */
    public function deleteRoleAction(Request $request)
    {
        //wyszukiwanie i usuwanie roli
        if ((null !== $role = (new \Cms\Orm\CmsRoleQuery)->findPk($request->id))) {
            $this->getMessenger()->addMessage(($deleteResult = (bool) $role->delete()) ? 'messenger.acl.role.deleted' : 'messenger.acl.role.delete.error', $deleteResult);
        }
        //redirect
        $this->getResponse()->redirect('cmsAdmin', 'acl', 'index', ['roleId' => $request->id]);
    }

    /**
     * Kasowanie uprawnienia (do AJAXA)
     * @return int
     */
    public function deleteAction(Request $request)
    {
        $this->getResponse()->setTypePlain();
        //nie można skasować
        if (!($request->id > 0)) {
            return 0;
        }
        $rule = (new \Cms\Orm\CmsAclQuery)->findPk($request->id);
        //skasowane
        if ($rule && $rule->delete()) {
            return 1;
        }
    }

    /**
     * Aktualizacja uprawnień (do AJAXA)
     * @return int
     */
    public function updateAction(Request $request)
    {
        $this->getResponse()->setTypePlain();
        $params = explode('-', $request->id);

        //błędne dane wejściowe
        if (!($request->getPost()->selected) || count($params) != 3) {
            return $this->view->_('controller.acl.update.error');
        }
        $record = (new \Cms\Orm\CmsAclQuery)->findPk($params[2]);
        if (!$record) {
            return $this->view->_('controller.acl.update.error');
        }
        //zmiana zasobu
        if ($params[1] == 'resource') {
            $resource = [];
            parse_str($request->getPost()->selected, $resource);
            $record->module = strtolower($resource['module']);
            $record->controller = isset($resource['controller']) ? strtolower($resource['controller']) : null;
            $record->action = isset($resource['action']) ? strtolower($resource['action']) : null;
        } else {
            //zmiana uprawnienia z allow na deny lub odwrotnie
            $record->access = $request->getPost()->selected == 'allow' ? 'allow' : 'deny';
        }
        $record->save();
        return 1;
    }

}
