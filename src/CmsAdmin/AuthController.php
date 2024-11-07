<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Cms\Orm\CmsAuthQuery;
use Cms\Orm\CmsAuthRecord;
use CmsAdmin\Form\Auth;
use Mmi\Http\Request;
use Mmi\Ldap\LdapConfig;
use Mmi\Mvc\Controller;
use Mmi\Security\AclInterface;
use Mmi\Security\AuthProviderInterface;

/**
 * Kontroler użytkowników
 */
class AuthController extends Controller
{
    /**
     * @Inject
     */
    private LdapConfig $ldapConfig;

    /**
     * @Inject
     */
    private AclInterface $acl;

    /**
     * @Inject
     */
    private AuthProviderInterface $authProvider;

    /**
     * @Inject("cms.language.list")
     */
    private string $cmsLanguageList;

    /**
     * Lista użytkowników
     */
    public function indexAction()
    {
        $this->view->grid = new \CmsAdmin\Plugin\AuthGrid();
    }

    /**
     * Edycja użytkownika
     */
    public function editAction(Request $request)
    {
        $this->view->ldap = $this->ldapConfig;

        $form = new Auth(new CmsAuthRecord($request->id), [AclInterface::class => $this->acl, 'cmsLanguageList' => $this->cmsLanguageList]);
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.auth.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'auth');
        }
        $this->view->authForm = $form;
    }

    /**
     * Kasowanie użytkownika
     */
    public function deleteAction(Request $request)
    {
        $auth = (new CmsAuthQuery())->findPk($request->id);
        if ($auth && $auth->delete()) {
            $this->getMessenger()->addMessage('messenger.auth.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'auth');
    }

    /**
     * Akcja jsonowa wyszukująca użytkowników w LDAP
     */
    public function autocompleteAction(Request $request): string
    {
        $term = str_replace([' ', '*'], '', $request->term);
        //typ odpowiedzi
        $this->getResponse()->setTypeJson();
        //za krótki ciąg
        if (strlen($term) < 3) {
            return json_encode([]);
        }
        //zwraca odpowiedz JSON
        return json_encode($this->authProvider->ldapAutocomplete($term . '*'));
    }
}
