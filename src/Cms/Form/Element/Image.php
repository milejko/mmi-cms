<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Cms\Form\Element;

use Cms\Model\File;
use Cms\Orm\CmsFileQuery;
use Cms\Orm\CmsFileRecord;
use Mmi\App\App;
use Mmi\Form\Element\ElementAbstract;
use Mmi\Http\Request;
use Mmi\Http\RequestFiles;
use Mmi\Http\RequestPost;

/**
 * Element TinyMce specyficzny dla generatora
 * Gettery
 * @method string getObject() pobiera obiekt
 * @method int getObjectId() pobiera identyfikator obiektu
 *
 * Settery
 * @method self setObject($object) ustawia obiekt
 * @method self setObjectId($id) ustawia identyfikator obiektu
 */
class Image extends UploaderElementAbstract implements UploaderElementInterface
{
    //szablon początku pola
    public const TEMPLATE_BEGIN = 'cmsAdmin/form/element/element-abstract/begin';
    //szablon opisu
    public const TEMPLATE_DESCRIPTION = 'cmsAdmin/form/element/element-abstract/description';
    //szablon pola
    public const TEMPLATE_FIELD = 'cmsAdmin/form/element/image';
    //szablon końca pola
    public const TEMPLATE_END = 'cmsAdmin/form/element/element-abstract/end';
    //szablon błędów
    public const TEMPLATE_ERRORS = 'cmsAdmin/form/element/element-abstract/errors';
    //szablon etykiety
    public const TEMPLATE_LABEL = 'cmsAdmin/form/element/element-abstract/label';

    //suffixy dodatkowych pól hidden
    private const DELETE_FIELD_SUFFIX = '-delete';

    /**
     * Załadowany plik
     * @var CmsFileRecord
     */
    protected $_uploadedFile;

    /**
     * Metoda wywoływana po dodaniu pola do formularza
     * @param \Mmi\Form\Form $form
     * @return $this|ElementAbstract|void
     * @throws \Mmi\App\KernelException
     */
    public function setForm(\Mmi\Form\Form $form)
    {
        parent::setForm($form);
        $request = App::$di->get(Request::class);
        $this->_handlePost($form, $request->getPost(), $request->getFiles());
        return $this;
    }

    public function fetchField()
    {
        $this->_createTempFiles();
        $this->_uploadedFile = CmsFileQuery::byObjectAndClass(self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId(), 'image')->findFirst();
        return ElementAbstract::fetchField();
    }

    public function getUploadedFile(): ?CmsFileRecord
    {
        return $this->_uploadedFile;
    }

    protected function _handlePost($form, RequestPost $post, RequestFiles $files)
    {
        if (!isset($post->{$form->getBaseName()})) {
            return;
        }
        //delete checkbox
        if (isset($post->{$form->getBaseName()}[$this->getBasename() . self::DELETE_FIELD_SUFFIX])) {
            File::deleteByObject(self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId(), [self::PLACEHOLDER_NAME]);
            return;
        }
        $fileArray = $files->getAsArray();
        if (!isset($fileArray[$form->getBaseName()]) || !isset($fileArray[$form->getBaseName()][$this->getBasename()][0])) {
            return;
        }
        File::deleteByObject( self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId());
        File::appendFile($fileArray[$form->getBaseName()][$this->getBasename()][0], self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function getDeleteCheckboxName(): string {
        return $this->_form ? $this->_form->getBaseName() . '[' . $this->getBasename() . self::DELETE_FIELD_SUFFIX . ']' : '';
    }

}
