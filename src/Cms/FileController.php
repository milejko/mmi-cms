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
use Mmi\App\KernelException;
use Mmi\Http\Request;
use Mmi\Http\ResponseTypes;
use Mmi\Mvc\MvcForbiddenException;
use Mmi\Session\SessionInterface;

/**
 * Kontroler plików
 * @property string $name
 * @property string $hash
 */
class FileController extends \Mmi\Mvc\Controller
{
    /**
     * @Inject
     */
    private SessionInterface $session;

    /**
     * @Inject("cms.thumb.quality")
     */
    private int $imageQuality;

    /**
     * Akcja skalera
     */
    public function scalerAction(Request $request)
    {
        $fs = new FileSystemModel($request->name);
        //public path
        $publicPath = $fs->getThumbPath($request->scaleType, $request->scale);
        //hash check
        if (false === strpos($publicPath, $request->hash)) {
            throw new MvcForbiddenException('Scaler hash invalid');
        }
        //target file calculation
        $targetFilePath = BASE_PATH . '/web' . $publicPath;
        try {
            mkdir(dirname($targetFilePath), 0777, true);
        } catch (\Exception $e) {
            throw new KernelException('Unable to create directory: ' . dirname($targetFilePath));
        }
        $scale = explode('x', $request->scale);
        $width = $scale[0];
        $height = isset($scale[1]) ? $scale[1] : null;
        //wybór skalowania do wykonania
        switch ($request->scaleType) {
            case 'scale':
                $resource = \Mmi\Image\Image::scale($fs->getRealPath(), $width, $height);
                break;
            case 'scalex':
                $resource = \Mmi\Image\Image::scalex($fs->getRealPath(), $width);
                break;
            case 'scaley':
                $resource = \Mmi\Image\Image::scaley($fs->getRealPath(), $width);
                break;
            case 'scalecrop':
                $resource = \Mmi\Image\Image::scaleCrop($fs->getRealPath(), $width, $height ?: $width);
                break;
            case 'default':
                $resource = \Mmi\Image\Image::inputToResource($fs->getRealPath());
                break;
            default:
                throw new MvcForbiddenException('Scaler type invalid');
        }
        //webp generation
        imagewebp($resource, $targetFilePath, $this->imageQuality);
        return $this->getResponse()->redirectToUrl($this->view->cdn . $publicPath);
    }

    /**
     * Akcja kopiowania
     */
    public function downloadAction(Request $request)
    {
        $fs = new FileSystemModel($request->name);
        //public path
        $publicPath = $fs->getDownloadPath($request->targetName);
        //hash check
        if (false === strpos($publicPath, $request->hash)) {
            throw new MvcForbiddenException('Download hash invalid');
        }
        //target file calculation
        $targetFilePath = BASE_PATH . '/web' . $publicPath;
        try {
            mkdir(dirname($targetFilePath), 0777, true);
        } catch (\Exception $e) {
            throw new KernelException('Unable to create directory: ' . dirname($targetFilePath));
        }
        list($name, $extension) = explode('.', $this->name);
        copy($fs->getRealPath(), $targetFilePath);
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
            $files[] = ['title' => $file->original, 'value' => $file->getThumbUrl()];
        }
        return json_encode($files);
    }

    /**
     * Lista obrazów (na potrzeby tinymce)
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
            $full = $file->getThumbUrl();
            $small = $poster = '';
            switch ($file->class) {
                case 'image':
                    $small = $file->getThumbUrl('scalecrop', '100x70');
                    $full = $file->getThumbUrl();
                    $poster = null;
                    break;
                case 'audio':
                case 'video':
                    $small = '';
                    $poster = $file->data->posterFileName ? (new FileSystemModel($file->data->posterFileName))->getThumbPath() : null;
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
