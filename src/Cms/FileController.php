<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

use Cms\Model\FileSystemModel;
use Mmi\Http\Request;
use Mmi\Session\SessionInterface;

/**
 * Kontroler plików
 */
class FileController extends \Mmi\Mvc\Controller
{
    /**
     * @Inject
     * @var SessionInterface
     */
    private $session;

    /**
     * Akcja skalera
     */
    public function scalerAction(Request $request)
    {
        $this->getResponse()->setType('image/webp');
        $im = \imagecreatetruecolor(100, 100);
        return imagewebp($im);
    }

    /**
     * Lista obrazów json (na potrzeby tinymce)
     * @return string
     */
    public function listAction(Request $request)
    {
        $this->getResponse()->setHeader('Content-type', 'application/json');
        if (!$request->object || !$request->objectId || !$request->hash || !$request->t) {
            return '';
        }
        if ($request->hash != md5($this->session->getId() . '+' . $request->t . '+' . $request->objectId)) {
            return '';
        }
        $files = [];
        foreach (\Cms\Orm\CmsFileQuery::imagesByObject($request->object, $request->objectId)->find() as $file) {
            $files[] = ['title' => $file->original, 'value' => $file->getUrl('default', '')];
        }
        return json_encode($files);
    }
    
    /**
     * Lista obrazów (na potrzeby tinymce)
     * @return view layout
     */
    public function listLayoutAction(Request $request)
    {
        $this->view->setLayoutDisabled();
        if (!$request->object || !$request->objectId || !$request->hash || !$request->t) {
            return '';
        }
        if ($request->hash != md5($this->session->getId() . '+' . $request->t . '+' . $request->objectId)) {
            return '';
        }
        $files = [];
        $thumb = new \Cms\Mvc\ViewHelper\Thumb($this->view);
        foreach (\Cms\Orm\CmsFileQuery::byObjectAndClass($request->object, $request->objectId, $request->class)->find() as $file) {
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
