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
use Mmi\Form\Form;

/**
 * Element wielokrotny upload
 */
class MultiUploadLegacy extends MultiUpload
{
    private const PLACEHOLDER_NAME = '.placeholder';

    public function getValue(): mixed
    {
        $value = parent::getValue();

        if (null === $value) {
            $objectId = $this->getObjectId();

            switch ($this->getFileTypes()) {
                case 'images' :
                    //zapytanie o obrazki
                    $query = CmsFileQuery::imagesByObject($this->getObject(), $objectId);
                    break;
                case 'notImages' :
                    //wszystkie pliki bez obrazków
                    $query = CmsFileQuery::notImagesByObject($this->getObject(), $objectId);
                    break;
                default :
                    //domyślne zapytanie o wszystkie pliki
                    $query = CmsFileQuery::byObject($this->getObject(), $objectId);
            }
            //wybieranie rekordów z rozmiarem i mimetypem
            $records = $query
                ->whereSize()->notEquals(null)
                ->whereMimeType()->notEquals(null)
                ->find();

            if (!empty($records)) {
                $value = [];
                foreach ($records as $record) {
                    $imprints    = $record->data->toArray();
                    $recordArray = [
                        'file'     => $record->id,
                        'isActive' => $record->active,
                        'filename' => $record->name,
                    ];

                    $value[] = array_merge($recordArray, $imprints);
                }
            }
        }

        return $value;
    }

    /**
     * @param Form $form
     *
     * @return $this
     */
    public function setForm(Form $form): self
    {
        parent::setForm($form);
        $this->_createTempFiles();

        return $this;
    }

    /**
     * Utorzenie kopii plików dla tego uploadera
     *
     * @return boolean
     */
    protected function _createTempFiles()
    {
        //jeśli już są pliki tymczasowe, to wychodzimy
        if ((new CmsFileQuery())
            ->byObject(self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId())
            ->count()) {
            return true;
        }
        //tworzymy pliki tymczasowe - kopie oryginałów
        File::link($this->getObject(), $this->getObjectId(), self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId());
        $placeholder           = new CmsFileRecord();
        $placeholder->name     = self::PLACEHOLDER_NAME;
        $placeholder->object   = self::TEMP_OBJECT_PREFIX . $this->getObject();
        $placeholder->objectId = $this->getUploaderId();
        $placeholder->save();

        return true;
    }

    /**
     * Dodaje pole do metryczki
     *
     * @param string $type    typ pola: text, checkbox, textarea, tinymce, select
     * @param string $name    nazwa pola
     * @param string $label   labelka pola
     * @param string $options opcje pola
     *
     * @return self
     */
    public function addImprintElement($type, $name, $label = null, $options = [])
    {
        $label        = ($label ? $this->view->_($label) : $name);
        $elementClass = __NAMESPACE__ . '\\' . ucfirst($type);

        if (class_exists($elementClass)) {
            $element = new $elementClass($name);
            $element->setLabel($label);

            if ('select' === $type) {
                $element->setMultioptions($options);
            }

            $this->addElement($element);
        }

        return $this;
    }

    /**
     * Dodaje pole tekstowe do metryczki
     *
     * @param string $name  nazwa pola
     * @param string $label labelka pola
     *
     * @return self
     */
    public function addImprintElementText($name, $label)
    {
        return $this->addImprintElement('text', $name, $label);
    }

    /**
     * Dodaje pole textarea do metryczki
     *
     * @param string $name  nazwa pola
     * @param string $label labelka pola
     *
     * @return self
     */
    public function addImprintElementTextarea($name, $label)
    {
        return $this->addImprintElement('textarea', $name, $label);
    }

    /**
     * Dodaje pole edytora wysiwyg do metryczki
     *
     * @param string $name  nazwa pola
     * @param string $label labelka pola
     *
     * @return self
     */
    public function addImprintElementTinymce($name, $label)
    {
        return $this->addImprintElement('tinymce', $name, $label);
    }

    /**
     * Dodaje pole checkbox do metryczki
     *
     * @param string $name  nazwa pola
     * @param string $label labelka pola
     *
     * @return self
     */
    public function addImprintElementCheckbox($name, $label)
    {
        return $this->addImprintElement('checkbox', $name, $label);
    }

    /**
     * Dodaje pole listy do metryczki
     *
     * @param string $name   nazwa pola
     * @param string $label  labelka pola
     * @param array  $option opcje
     *
     * @return self
     */
    public function addImprintElementSelect($name, $label, $option)
    {
        return $this->addImprintElement('select', $name, $label, $option);
    }
}
