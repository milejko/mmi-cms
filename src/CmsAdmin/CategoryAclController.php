<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Cms\App\CmsScopeConfig;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;

/**
 * Kontroler kontaktów
 */
class CategoryAclController extends Controller
{

    /**
     * @Inject
     */
    private CmsScopeConfig $scopeConfig;

    /**
     * Akcja ustawiania uprawnień na kategoriach
     */
    public function indexAction(Request $request)
    {
        $this->view->roles = (new \Cms\Orm\CmsRoleQuery)->find();
        //jeśli niewybrana rola - przekierowanie na pierwszą istniejącą
        if (!$request->roleId && count($this->view->roles)) {
            $this->getResponse()->redirect('cmsAdmin', 'categoryAcl', 'index', ['roleId' => $this->view->roles[0]->id]);
        }
        //formularz edycji uprawnień
        $form = new Form\CategoryAclForm(null, ['roleId' => $request->roleId, 'scope' => $this->scopeConfig->getName()]);
        //po zapisie
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.categoryAcl.permissions.saved', true);
            //przekierowanie na zapisaną stronę
            $this->getResponse()->redirect('cmsAdmin', 'categoryAcl', 'index', ['roleId' => $request->roleId]);
        }
        $this->view->categoryAclForm = $form;
    }

}
