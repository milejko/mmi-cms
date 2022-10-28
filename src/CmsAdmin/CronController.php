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
 * Kontroler harmonogramu zadań
 */
class CronController extends Controller
{
    /**
     * Lista zadań
     */
    public function indexAction()
    {
        $grid = new \CmsAdmin\Plugin\CronGrid();
        $this->view->grid = $grid;
    }

    /**
     * Edycja zadania
     */
    public function editAction(Request $request)
    {
        $form = new \CmsAdmin\Form\Cron(new \Cms\Orm\CmsCronRecord($request->id));
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.cron.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'cron');
        }
        $this->view->cronForm = $form;
    }

    /**
     * Usuwanie zadania
     */
    public function deleteAction(Request $request)
    {
        $record = (new \Cms\Orm\CmsCronQuery())->findPk($request->id);
        if ($record && $record->delete()) {
            $this->getMessenger()->addMessage('messenger.cron.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'cron');
    }
}
