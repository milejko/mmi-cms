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
use Mmi\Mvc\MvcForbiddenException;
use App\Registry;
use Cms\Orm\CmsFileQuery;
use Mmi\Http\ResponseTypes;

/**
 * Kontroler plików
 */
class FileController extends \Mmi\Mvc\Controller
{

    /**
     * Akcja skalera
     */
    public function scalerAction()
    {
        //memory limit bump
        ini_set('memory_limit', '512M');
        $fs = new FileSystemModel($this->name);
        //public path
        $publicPath = $this->legacy ? 
            str_replace('.webp', '.jpg', $fs->getPublicPath($this->operation, trim($this->x . 'x' . $this->y, 'x')))
            : $fs->getPublicPath($this->operation, trim($this->x . 'x' . $this->y, 'x'));
        //hash check
        if (false === strpos($publicPath, $this->hash)) {
            throw new MvcForbiddenException('Scaler hash invalid');
        }
        //target file calculation
        $targetFilePath = BASE_PATH . '/web' . $publicPath;
        try {
            mkdir(dirname($targetFilePath), 0777, true);
        } catch (\Exception $e) {
        }
        //wybór skalowania do wykonania
        switch ($this->operation) {
            case 'scale':
                $resource = \Mmi\Image\Image::scalex($fs->getRealPath(), $this->x, $this->y ?: $this->x);
                break;
            case 'scalex':
                $resource = \Mmi\Image\Image::scalex($fs->getRealPath(), $this->x);
                break;
            case 'scaley':
                $resource = \Mmi\Image\Image::scaley($fs->getRealPath(), $this->x);
                break;
            case 'scalecrop':
                $resource = \Mmi\Image\Image::scaleCrop($fs->getRealPath(), $this->x, $this->y ?: $this->x);
                break;
            case 'default':
                $resource = \Mmi\Image\Image::inputToResource($fs->getRealPath());
                break;
            default:
                throw new MvcForbiddenException('Scaler type invalid');
        }
        //webp generation
        $this->legacy ? 
            imagejpeg($resource, $targetFilePath, Registry::$config->thumbQuality) :
            imagewebp($resource, $targetFilePath, Registry::$config->thumbQuality);
        return $this->getResponse()->redirectToUrl($this->view->cdn . $publicPath);
    }

    /**
     * Akcja kopiowania
     */
    public function copyAction()
    {
        $fs = new FileSystemModel($this->name);
        //public path
        $publicPath = $fs->getPublicPath();
        //hash check
        if (false === strpos($publicPath, $this->hash)) {
            throw new MvcForbiddenException('Scaler hash invalid');
        }
        //target file calculation
        $targetFilePath = BASE_PATH . '/web' . $publicPath;
        try {
            mkdir(dirname($targetFilePath), 0777, true);
        } catch (\Exception $e) {
        }
        list($name, $extension) = explode('.', $this->name);
        copy($fs->getRealPath(), $targetFilePath);
        $this->getResponse()
            ->setHeader('Content-Length', filesize($fs->getRealPath()))
            ->setType(ResponseTypes::searchType($extension))
            ->setHeader('Cache-Control', 'public')
            ->setHeader('Expires', 'max')
            ->sendHeaders();
        readfile($fs->getRealPath());
        exit;
    }

    /**
     * Lista obrazów json (na potrzeby tinymce)
     * @return string
     */
    public function listAction()
    {
        \Mmi\App\FrontController::getInstance()->getResponse()->setHeader('Content-type', 'application/json');
        if (!$this->object || !$this->objectId || !$this->hash || !$this->t) {
            return '';
        }
        if ($this->hash != md5(\Mmi\Session\Session::getId() . '+' . $this->t . '+' . $this->objectId)) {
            return '';
        }
        $files = [];
        foreach (\Cms\Orm\CmsFileQuery::imagesByObject($this->object, $this->objectId)->find() as $file) {
            $files[] = ['title' => $file->original, 'value' => $file->getUrl('default', '')];
        }
        return json_encode($files);
    }
    
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