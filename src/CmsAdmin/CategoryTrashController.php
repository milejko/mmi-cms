<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use CmsAdmin\Model\CategoryAclModel;
use CmsAdmin\Plugin\CategoryGrid;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;
use Mmi\Security\AuthInterface;

/**
 * Kontroler kategorii - stron CMS
 */
class CategoryTrashController extends Controller
{
    /**
     * @Inject
     */
    private AuthInterface $auth;

    /**
     * Lista usuniętych stron CMS - prezentacja w formie grida
     */
    public function indexAction()
    {
        $this->view->grid = new CategoryGrid;
    }

    public function restoreAction(Request $request)
    {
        //wyszukiwanie kategorii
        if (null === $category = (new CmsCategoryQuery())->findPk($request->id)) {
            //przekierowanie na trash
            return $this->getResponse()->redirect('cmsAdmin', 'categoryTrash', 'index');
        }
        //niedozwolona
        if (!(new CategoryAclModel)->getAcl()->isAllowed($this->auth->getRoles(), $category->id)) {
            $this->getMessenger()->addMessage('messenger.category.permission.denied', false);
            return $this->getResponse()->redirect('cmsAdmin', 'categoryTrash', 'index');
        }
        //aktywacja
        $category->status = CmsCategoryRecord::STATUS_ACTIVE;
        $category->save();
        $this->getMessenger()->addMessage('messenger.categoryTrash.success', true);
        return $this->getResponse()->redirect('cmsAdmin', 'category', 'index', ['parentId' => $category->parentId]);
    }
}
