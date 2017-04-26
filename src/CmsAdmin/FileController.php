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

/**
 * Kontroler plików
 */
class FileController extends Mvc\Controller
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
    public function deleteAction()
    {
        $file = (new CmsFileQuery)->findPk($this->id);
        if ($file && $file->delete()) {
            $this->getMessenger()->addMessage('Poprawnie usunięto plik', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'file', 'index');
    }

    /**
     * Akcja ajaxowa - przypinanie pliku
     * @return string
     */
    public function stickAction()
    {
        $this->getResponse()->setTypePlain();
        //brak id
        if (!$this->id) {
            return '';
        }
        //brak pliku
        if (null === ($file = (new CmsFileQuery)->findPk($this->id)) || $this->hash != $file->name) {
            return $this->view->getTranslate()->_('Przypinanie nie powiodło się');
        }
        //przypina plik
        $file->setSticky();
        return '';
    }

    /**
     * Akcja ajaxowa - edycja pliku
     * @return string
     */
    public function editAction()
    {
        $this->getResponse()->setTypeJson();
        $error = json_encode(['error' => 'Edycja nie powiodła się, brak pliku']);
        //brak id
        if (!$this->id) {
            return $error;
        }
        //brak pliku
        if (null === ($file = (new CmsFileQuery)->findPk($this->id))) {
            return $error;
        }
        //błędny plik
        if ($this->hash != $file->getHashName()) {
            return $error;
        }
        //zapis
        $postData = $this->getPost()->toArray();
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
    public function removeAction()
    {
        $this->getResponse()->setTypePlain();
        if (!$this->id) {
            return $this->view->getTranslate()->_('Usuwanie nie powiodło się, brak pliku');
        }
        $file = (new CmsFileQuery)->findPk($this->id);
        if (!$file || $this->hash != $file->getHashName()) {
            return $this->view->getTranslate()->_('Usuwanie nie powiodło się');
        }
        $file->delete();
        return '';
    }

    /**
     * Akcja ajaxowa - sortowanie
     * @return string
     */
    public function sortAction()
    {
        $this->getResponse()->setTypePlain();
        if (!$this->getPost()->__get('item-file')) {
            return $this->view->getTranslate()->_('Przenoszenie nie powiodło się');
        }
        \Cms\Model\File::sortBySerial($this->getPost()->__get('item-file'));
        return '';
    }

}
