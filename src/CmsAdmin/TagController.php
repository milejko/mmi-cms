<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Mmi\Http\Request;
use Mmi\Mvc\Controller;

/**
 * Kontroler tagów
 */
class TagController extends Controller
{

    /**
     * Lista tagów
     */
    public function indexAction()
    {
        $this->view->grid = new \CmsAdmin\Plugin\TagGrid();
    }

    /**
     * Edycja tagów
     */
    public function editAction(Request $request)
    {
        $form = new \CmsAdmin\Form\Tag(new \Cms\Orm\CmsTagRecord($request->id));
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.tag.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'tag', 'index');
        }
        $this->view->tagForm = $form;
    }

    /**
     * Usuwanie tagu
     */
    public function deleteAction(Request $request)
    {
        $tag = (new \Cms\Orm\CmsTagQuery)->findPk($request->id);
        if ($tag && $tag->delete()) {
            $this->getMessenger()->addMessage('messenger.tag.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'tag', 'index');
    }

}
