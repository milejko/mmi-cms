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
use Cms\App\CmsSkinsetConfig;
use Cms\Model\SkinsetModel;
use CmsAdmin\Form\CategoryAclForm;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;
use Mmi\Security\AclInterface;

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
     * @Inject
     */
    private CmsSkinsetConfig $cmsSkinsetConfig;

    /**
     * @Inject
     */
    private AclInterface $acl;

    /**
     * Akcja ustawiania uprawnień na kategoriach
     */
    public function indexAction(Request $request)
    {
        $this->view->roles = $this->acl->getRoles();
        //jeśli niewybrana rola - przekierowanie na pierwszą istniejącą
        if (!$request->role && count($this->view->roles)) {
            $this->getResponse()->redirect('cmsAdmin', 'categoryAcl', 'index', ['role' => $this->view->roles[0]]);
        }
        //formularz edycji uprawnień
        $form = new CategoryAclForm(null, [
            'role' => $request->role,
            CategoryAclForm::SCOPE_CONFIG_OPTION_NAME => $this->scopeConfig->getName(),
            SkinsetModel::class => new SkinsetModel($this->cmsSkinsetConfig)
        ]);
        //po zapisie
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.categoryAcl.permissions.saved', true);
            //przekierowanie na zapisaną stronę
            $this->getResponse()->redirect('cmsAdmin', 'categoryAcl', 'index', ['roleId' => $request->roleId]);
        }
        $this->view->categoryAclForm = $form;
    }
}
