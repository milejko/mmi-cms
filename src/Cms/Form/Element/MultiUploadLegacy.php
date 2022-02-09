<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Cms\Form\Element;

use Cms\Orm\CmsFileQuery;
use Mmi\Validator\NotEmpty;

/**
 * Element wielokrotny upload
 */
class MultiUploadLegacy extends MultiUpload
{
    /**
     * Konstruktor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this
            ->addElement(
                (new Text('filename'))
                    ->setLabel('form.multiupload.filename.label')
                    ->setRequired()
                    ->addValidator(new NotEmpty())
            );
    }

    public function getValue(): mixed
    {
        $value = parent::getValue();

        if (null === $value) {
            $objectId = $this->getObjectId();

            switch ($this->getFileTypes()) {
                case 'images':
                    //zapytanie o obrazki
                    $query = CmsFileQuery::imagesByObject($this->getObject(), $objectId);
                    break;
                case 'notImages':
                    //wszystkie pliki bez obrazków
                    $query = CmsFileQuery::notImagesByObject($this->getObject(), $objectId);
                    break;
                default:
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
     * Generuje automatyczny obiekt dla plików na podstawie nazwy klasy formularza
     * @param string $name
     * @return string
     */
    protected function _getFileObjectByClassName($name)
    {
        $parts = \explode('\\', strtolower($name));
        return substr(end($parts), 0, -6);
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

        $imprint = $this->getOption('imprint');
        //brak pól - pusta lista
        if (null === $imprint) {
            $imprint = [];
        }
        $imprint[] = ['type' => $type, 'name' => $name, 'label' => ($label ? $this->view->_($label) : $name), 'options' => $options];

        return $this->setOption('imprint', $imprint);
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
