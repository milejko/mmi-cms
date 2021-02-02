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
use Mmi\Mvc\MvcForbiddenException;
use Mmi\Session\SessionInterface;
use Psr\Container\ContainerInterface;

/**
 * Kontroler plików
 */
class FileController extends \Mmi\Mvc\Controller
{
    /**
     * @Inject
     */
    private SessionInterface $session;

    /**
     * @Inject
     */
    private ContainerInterface $container;

    /**
     * Akcja skalera
     */
    public function scalerAction(Request $request)
    {
        $fs = new FileSystemModel($request->name);
        //public path
        $publicPath = $fs->getPublicPath($request->operation, trim($request->x . 'x' . $request->y, 'x'));
        //hash check
        if (false === strpos($publicPath, $request->hash)) {
            throw new MvcForbiddenException('Scaler hash invalid');
        }
        //target file calculation
        $targetFilePath = BASE_PATH . '/web' . $publicPath;
        try {
            mkdir(dirname($targetFilePath), 0777, true);
        } catch (\Exception $e) {}
        //kopiowanie
        if ('download' == $request->operation) {
            copy($fs->getRealPath(), $targetFilePath);
            return $this->getResponse()->redirectToUrl($this->view->cdn . $publicPath);
        }
        //wybór skalowania do wykonania
        switch ($request->operation) {
            case 'scale':
                $resource = \Mmi\Image\Image::scalex($fs->getRealPath(), $request->x, $request->y ? : $request->x);
                break;
            case 'scalex':
                $resource = \Mmi\Image\Image::scalex($fs->getRealPath(), $request->x);
                break;
            case 'scaley':
                $resource = \Mmi\Image\Image::scaley($fs->getRealPath(), $request->x);
                break;
            case 'scalecrop':
                $resource = \Mmi\Image\Image::scaleCrop($fs->getRealPath(), $request->x, $request->y ? : $request->x);
                break;
            case 'default':
                $resource = \Mmi\Image\Image::inputToResource($fs->getRealPath());
                break;
            default:
                throw new MvcForbiddenException('Scaler type invalid');
        }        
        //webp generation
        imagewebp($resource, $targetFilePath, $this->container->get('cms.thumb.quality'));
        return $this->getResponse()->redirectToUrl($this->view->cdn . $publicPath);
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
        foreach (\Cms\Orm\CmsFileQuery::byObjectAndClass($request->object, $request->objectId, $request->class)->find() as $file) {
            switch ($file->class) {
                case 'image':
                    $full = $file->getUrl();
                    $small = $file->getUrl('scalecrop', '100x70');
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
