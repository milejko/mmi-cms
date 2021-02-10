<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Cms\Orm\CmsFileQuery;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;

/**
 * Kontroler plików
 */
class FileController extends Controller
{

    /**
     * Lista plików
     */
    public function indexAction()
    {
        $this->view->grid = new \CmsAdmin\Plugin\FileGrid;
    }

    /**
     * Usuwanie pliku (z listy)
     */
    public function deleteAction(Request $request)
    {
        $file = (new CmsFileQuery)->findPk($request->id);
        if ($file && $file->delete()) {
            $this->getMessenger()->addMessage('messenger.file.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'file', 'index');
    }

    /**
     * Akcja ajaxowa - edycja pliku
     * @return string
     */
    public function editAction(Request $request)
    {
        $this->getResponse()->setTypeJson();
        $error = json_encode(['error' => 'controller.fileController.edit.error']);
        //brak id
        if (!$this->id) {
            return $error;
        }
        //brak pliku
        if (null === ($file = (new CmsFileQuery)->findPk($request->id))) {
            return $error;
        }
        //błędny plik
        if ($this->hash != $file->getHashName()) {
            return $error;
        }
        //zapis
        $postData = $request->getPost()->toArray();
        if (!empty($postData)) {
            $file->setFromArray($postData)
                ->save();
        }
        //json na potrzeby wyświetlenia formularza
        return $file->toJson();
    }

    /**
     * Akcja ajaxowa - usuwanie pliku
     * @return string
     */
    public function removeAction(Request $request)
    {
        $this->getResponse()->setTypePlain();
        if (!$request->id) {
            return $this->view->_('controller.fileController.delete.error');
        }
        $file = (new CmsFileQuery)->findPk($request->id);
        if (!$file || $this->hash != $file->getHashName()) {
            return $this->view->_('controller.fileController.delete.error');
        }
        $file->delete();
        return '';
    }

    /**
     * Akcja ajaxowa - sortowanie
     * @return string
     */
    public function sortAction(Request $request)
    {
        $this->getResponse()->setTypePlain();
        if (!$request->getPost()->__get('item-file')) {
            return $this->view->_('controller.fileController.sort.error');
        }
        \Cms\Model\File::sortBySerial($request->getPost()->__get('item-file'));
        return '';
    }

}
