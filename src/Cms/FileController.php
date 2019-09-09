<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

/**
 * Kontroler plików
 */
class FileController extends \Mmi\Mvc\Controller
{

    /**
     * Lista obrazów (na potrzeby tinymce)
     * @return view layout
     */
    public function listLayoutAction()
    {
        $this->view->setLayoutDisabled();
        if (!$this->object || !$this->objectId || !$this->hash || !$this->t) {
            return '';
        }
        if ($this->hash != md5(\Mmi\Session\Session::getId() . '+' . $this->t . '+' . $this->objectId)) {
            return '';
        }
        $files = [];
        $thumb = new \Cms\Mvc\ViewHelper\Thumb();
        foreach (\Cms\Orm\CmsFileQuery::byObjectAndClass($this->object, $this->objectId, $this->class)->find() as $file) {
            switch ($file->class) {
                case 'image':
                    $full = $thumb->thumb($file, 'default');
                    $small = $file->getUrl('scaley', '60', false);
                    $poster = null;
                    break;
                case 'audio':
                case 'video':
                    $full = $file->getUrl();
                    $small = '';
                    $poster = $file->data->posterFileName ? (new FileSystemModel($file->data->posterFileName))->getPublicPath() : null;
                    break;
            }

            $files[] = [
                'id' => $file->id,
                'title' => $file->original,
                'full' => $full,
                'thumb' => $small,
                'poster' => $poster,
                'class' => $file->class,
                'mime' => $file->mimeType,
            ];
        }

        //przekazanie danych
        $this->view->files = $files;
    }

}
