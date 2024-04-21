<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Cms\Form\Element;

use Cms\Model\File as FileModel;
use Cms\Orm\CmsFileQuery;
use Cms\Orm\CmsFileRecord;
use Mmi\App\App;
use Mmi\Form\Element\ElementAbstract;
use Mmi\Http\Request;
use Mmi\Http\RequestFile;

/**
 * @method string getObject() pobiera obiekt
 * @method int getObjectId() pobiera identyfikator obiektu
 *
 * @method self setObject($object) ustawia obiekt
 * @method self setObjectId($id) ustawia identyfikator obiektu
 */
class File extends UploaderElementAbstract
{
    //szablon początku pola
    public const TEMPLATE_BEGIN = 'cmsAdmin/form/element/element-abstract/begin';
    //szablon opisu
    public const TEMPLATE_DESCRIPTION = 'cmsAdmin/form/element/element-abstract/description';
    //szablon pola
    public const TEMPLATE_FIELD = 'cmsAdmin/form/element/file';
    //szablon końca pola
    public const TEMPLATE_END = 'cmsAdmin/form/element/element-abstract/end';
    //szablon błędów
    public const TEMPLATE_ERRORS = 'cmsAdmin/form/element/element-abstract/errors';
    //szablon etykiety
    public const TEMPLATE_LABEL = 'cmsAdmin/form/element/element-abstract/label';

    /**
     * Załadowane pliki
     * @var array|CmsFileRecord[]
     */
    protected array $_uploadedFiles = [];

    public function fetchField()
    {
        //multiupload backwards compatibility
        if (is_array($this->getValue()) && isset($this->getValue()[0][$this->getBasename()])) {
            $this->setValue($this->getValue()[0][$this->getBasename()]);
        }
        $this->_createTempFiles();
        foreach (CmsFileQuery::byObject(self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId())
            ->whereName()->notEquals(self::PLACEHOLDER_NAME)
            ->find() as $file) {
            $this->_uploadedFiles[$file->name] = $file;
        }
        return ElementAbstract::fetchField();
    }

    public function getUploadedFile(?string $name): ?CmsFileRecord
    {
        return $this->_uploadedFiles[$name] ?? array_values($this->_uploadedFiles)[0] ?? null;
    }

    public function beforeFormSave(): void
    {
        $request = App::$di->get(Request::class);
        $post = $request->getPost();
        if (!isset($post->{$this->_form->getBaseName()})) {
            return;
        }
        $fileArray = $request->getFiles()->getAsArray()[$this->_form->getBaseName()];
        $dataArray = $post->{$this->_form->getBaseName()};
        $ignoreFileNames = $this->keepFiles($dataArray);
        if (empty($ignoreFileNames) && empty($fileArray)) {
            $ignoreFileNames = [self::PLACEHOLDER_NAME];
        }
        //delete files
        FileModel::deleteByObject(self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId(), $ignoreFileNames);
        //save files
        $savedFiles = $this->saveFiles($fileArray);
        //update form data
        $post->{$this->_form->getBaseName()} = array_replace_recursive($dataArray, $savedFiles);
        $this->_form->setFromPost($post);
    }

    private function keepFiles(array $values, array &$names = []): array
    {
        foreach ($values as $index => $value) {
            if (is_array($value)) {
                $this->keepFiles($value, $names);
            } elseif (!empty($value) && $index === $this->getBasename()) {
                $names[] = $value;
            }
        }

        return $names;
    }

    private function saveFiles(array $fileArray): array
    {
        $savedFiles = [];

        foreach ($fileArray as $fieldName => $fieldData) {
            if (is_array($fieldData)) {
                $savedFiles[$fieldName] = $this->saveFiles($fieldData);
            } elseif ($fieldData instanceof RequestFile && $fieldName === $this->getBasename()) {
                $savedFiles[$fieldName] = FileModel::appendFile($fieldData, self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId())->name;
            }
        }
        return $savedFiles;
    }
}
