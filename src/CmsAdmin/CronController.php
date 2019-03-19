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
 * Kontroler harmonogramu zadań
 */
class CronController extends Mvc\Controller
{

    /**
     * Lista zadań
     */
    public function indexAction()
    {
        $grid = new \CmsAdmin\Plugin\CronGrid;
        $this->view->grid = $grid;
    }

    /**
     * Edycja zadania
     */
    public function editAction()
    {
        $form = new \CmsAdmin\Form\Cron(new \Cms\Orm\CmsCronRecord($this->id));
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.cron.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'cron');
        }
        $this->view->cronForm = $form;
    }

    /**
     * Usuwanie zadania
     */
    public function deleteAction()
    {
        $record = (new \Cms\Orm\CmsCronQuery)->findPk($this->id);
        if ($record && $record->delete()) {
            $this->getMessenger()->addMessage('messenger.cron.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'cron');
    }

}
