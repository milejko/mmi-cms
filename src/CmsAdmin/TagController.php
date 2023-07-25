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
use Cms\Orm\CmsTagQuery;
use Cms\Orm\CmsTagRecord;
use CmsAdmin\Form\Tag;
use CmsAdmin\Plugin\TagGrid;
use DI\Annotation\Inject;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;
use Psr\Container\ContainerInterface;

/**
 * Kontroler tagów
 * @property int $id
 */
class TagController extends Controller
{
    /**
     * @Inject
     */
    private ContainerInterface $container;

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
     * Edycja tagów
     */
    public function editAction()
    {
        $tag = new CmsTagRecord($this->id);
        $tag->scope = $this->cmsScopeConfig->getName();
        $form = new Tag($tag, [ContainerInterface::class => $this->container]);
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
        $tag = (new CmsTagQuery())->findPk($request->id);
        if ($tag && $tag->delete()) {
            $this->getMessenger()->addMessage('messenger.tag.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'tag', 'index');
    }
}
