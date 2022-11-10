<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Cms\App\CmsScopeConfig;
use CmsAdmin\Plugin\TagGrid;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;

/**
 * Kontroler tagów
 */
class TagController extends Controller
{
    /**
     * @Inject
     */
    private CmsScopeConfig $cmsScopeConfig;

    /**
     * Lista tagów
     */
    public function indexAction()
    {
        $this->view->grid = new TagGrid([TagGrid::SCOPE_OPTION_NAME => $this->cmsScopeConfig->getName()]);
    }

    /**
     * Usuwanie tagu
     */
    public function deleteAction(Request $request)
    {
        $tag = (new \Cms\Orm\CmsTagQuery())->findPk($request->id);
        if ($tag && $tag->delete()) {
            $this->getMessenger()->addMessage('messenger.tag.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'tag', 'index');
    }
}
