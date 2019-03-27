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
 * Kontroler pobierania plików
 */
class UploadController extends Mvc\Controller
{

    /**
     * Inicjalizacja - wyłączanie layoutu
     */
    public function init()
    {
        $this->view->setLayoutDisabled();
        $this->getResponse()->setTypeJson();

    }

    /**
     * Odbieranie danych z plugina Plupload
     */
    public function pluploadAction()
    {
        set_time_limit(5 * 60);
        //obiekt handlera plupload
        $pluploadHandler = new Model\PluploadHandler();
        //jeśli wystąpił błąd
        if (!$pluploadHandler->handle()) {
            return $this->_jsonError($pluploadHandler->getErrorCode(), $pluploadHandler->getErrorMessage());
        }
        //jeśli wykonać operację po przesłaniu całego pliku i zapisaniu rekordu
        if ($this->getPost()->afterUpload && null !== $record = $pluploadHandler->getSavedCmsFileRecord()) {
            $this->_operationAfter($this->getPost()->afterUpload, $record);
        }
        return json_encode(['result' => 'OK', 'cmsFileId' => $pluploadHandler->getSavedCmsFileId()]);
    }

    /**
     * Zwraca listę aktualnych plików przypiętych do obiektu formularza
     */
    public function currentAction()
    {
        $objectId = !empty($this->getPost()->objectId) ? $this->getPost()->objectId : null;
        switch ($this->getPost()->fileTypes) {
            case 'images' :
                //zapytanie o obrazki
                $query = CmsFileQuery::imagesByObject($this->getPost()->object, $objectId);
                break;
            case 'notImages' :
                //wszystkie pliki bez obrazków
                $query = CmsFileQuery::notImagesByObject($this->getPost()->object, $objectId);
                break;
            default :
                //domyślne zapytanie o wszystkie pliki
                $query = CmsFileQuery::byObject($this->getPost()->object, $objectId);
        }

        $records = $query->find();
        foreach ($records as $record) {
            $record->data = $record->data->toArray();
        }

        //zwrot json'a z plikami
        return json_encode([
            'result' => 'OK',
            'files' => $records->toArray()
        ]);
    }

    /**
     * Usuwa wybrany rekord pliku
     */
    public function deleteAction()
    {
        //szukamy rekordu pliku
        if (!$this->getPost()->cmsFileId || null === $record = (new CmsFileQuery)->findPk($this->getPost()->cmsFileId)) {
            return $this->_jsonError(178);
        }
        //sprawdzenie zgodności z obiektem formularza
        if ($record->object === $this->getPost()->object && $record->objectId == $this->getPost()->objectId) {
            //usuwanie
            if ($record->delete()) {
                //jeśli wykonać operację po usunięciu
                if ($this->getPost()->afterDelete) {
                    $this->_operationAfter($this->getPost()->afterDelete, $record);
                }
                return json_encode(['result' => 'OK']);
            }
        }
        return $this->_jsonError(178);
    }

    /**
     * Zwraca minaturę wybranego rekord pliku
     */
    public function thumbnailAction()
    {
        if (!$this->getPost()->cmsFileId) {
            return $this->_jsonError(179);
        }
        //szukamy rekordu pliku
        if (null !== $record = (new CmsFileQuery)->findPk($this->getPost()->cmsFileId)) {
            //sprawdzenie czy obrazek
            if ($record->class === 'image') {
                try {
                    $thumb = new \Cms\Mvc\ViewHelper\Thumb();
                    $url = $thumb->thumb($record, 'scaley', '60');
                    if (!empty($url)) {
                        return json_encode(['result' => 'OK', 'url' => $url]);
                    }
                } catch (\Exception $ex) {
                    
                }
            }
        }
        return $this->_jsonError(179);
    }

    /**
     * Zwraca dane opisujące rekord pliku
     */
    public function detailsAction()
    {
        if (!$this->getPost()->cmsFileId) {
            return $this->_jsonError(185);
        }
        //szukamy rekordu pliku
        if (null == $record = (new CmsFileQuery)->findPk($this->getPost()->cmsFileId)) {
            return $this->_jsonError(185);
        }
        //wycinamy rozszerzenie z nazwy oryginalnego pliku do edycji
        //pozycja ostatniej kropki w nazwie
        $pointPosition = strrpos($record->original, '.');
        if ($pointPosition !== false) {
            $record->original = substr($record->original, 0, $pointPosition);
        }
        $data = [];
        if ($record->data instanceof \Mmi\DataObject) {
            //parametry
            $data = $record->data->toArray();
        }
        $data['urlFile'] = ((\App\Registry::$config->cdn) ? : $record->getUrl();
        if ($record->data->posterFileName) {
            $data['poster'] = $record->getPosterUrl();
        }    
        return json_encode(['result' => 'OK', 'record' => $record, 'data' => $data]);
    }

    /**
     * Aktualizuje dane opisujące rekord pliku: tytuł, autora, źródło
     */
    public function describeAction()
    {
        //sprawdzamy, czy jest id pliku i form
        if (!$this->getPost()->cmsFileId || !is_array($this->getPost()->form)) {
            return $this->_jsonError(186);
        }
        //szukamy rekordu pliku
        if (null === $record = (new CmsFileQuery)->findPk($this->getPost()->cmsFileId)) {
            return $this->_jsonError(186);
        }
        //pobranie danych
        $form = array_merge(['active' => 0, 'sticky' => null], $this->getPost()->form);
        foreach ($form as $field) {
            $form[$field['name']] = $field['value'];
            if ($field['name'] == 'active' || $field['name'] == 'sticky') {
                continue;
            }
            if ($field['name'] == 'original') {
                //dodajemy rozszerzenie
                //pozycja ostatniej kropki w nazwie - rozszerzenie pliku
                $pointPosition = strrpos($record->name, '.');
                if ($pointPosition !== false) {
                    $form['original'] .= substr($record->name, $pointPosition);
                }
                $record->data->original = $form['original'];
                continue;
            }
            $record->data->{$field['name']} = $field['value'];
        }
        //próba zapisu postera
        if (isset($form['poster']) && $form['poster']) {
            $form['posterFileName'] = $this->_savePoster($form['poster'], $record);
        }
        unset($form['poster']);
        if ($record->data instanceof \Mmi\DataObject) {
            //czyszczenie nieprzesłanych checkboxów
            foreach (array_keys($record->data->toArray()) as $name) {
                //nie czyścimy postera jeśli był wgrany
                if ($name == 'posterFileName') {
                    continue;
                }
                if (!isset($form[$name])) {
                    $record->data->{$name} = null;
                }
            }
        }
        $record->active = isset($form['active']) ? $form['active'] : $record->active;
        $record->sticky = isset($form['sticky']) ? $form['sticky'] : $record->sticky;
        $record->original = isset($form['original']) ? $form['original'] : $record->original;
        if ($record->sticky) {
            $result = $record->setSticky();
        } else {
            $result = $record->save();
        }
        if ($result) {
            //jeśli wykonać operację po edycji
            if ($this->getPost()->afterEdit) {
                $this->_operationAfter($this->getPost()->afterEdit, $record);
            }
            return json_encode(['result' => 'OK']);
        }
        return $this->_jsonError(186);
    }

    /**
     * Przekierowanie na plik
     * @return string
     */
    public function downloadAction()
    {
        if (null === $file = (new CmsFileQuery)->byObject($this->object, $this->objectId)
            ->findPk($this->id)) {
            return '';
        }
        $this->getResponse()->redirectToUrl($file->getUrl());
    }

    /**
     * Zapisuje kolejność plików
     */
    public function sortAction()
    {
        $order = $this->getPost()->order;
        if (empty($order) || !is_array($order)) {
            return json_encode(['result' => 'OK']);
        }
        try {
            \Cms\Model\File::sortBySerial($order);
        } catch (\Exception $ex) {
            return $this->_jsonError(180);
        }
        return json_encode(['result' => 'OK']);
    }

    /**
     * Zwraca sformatowany błąd JSON
     * @param integer $code
     * @param string $message
     * @return string
     */
    protected function _jsonError($code = 403, $message = '')
    {
        return json_encode([
            'result' => 'ERR',
            'error' => [
                'code' => $code,
                'message' => $message
            ]
        ]);
    }

    /**
     * Wykonuje dodatkową operację po innym zdarzeniu
     * @param array $action
     * @param \Cms\Orm\CmsFileRecord $record
     * @return mixed
     */
    protected function _operationAfter($action, \Cms\Orm\CmsFileRecord $record)
    {
        return \Mmi\Mvc\ActionHelper::getInstance()->action(new \Mmi\Http\Request(array_merge($record->toArray(), $action)));
    }

    /**
     * Zapisanie postera dla video
     * @param string $blob
     * @param \Cms\Orm\CmsFileRecord $file
     * @return string
     */
    protected function _savePoster($blob, \Cms\Orm\CmsFileRecord $file)
    {
        //brak danych
        if (!\preg_match('/^data:(image\/[a-z]+);base64,(.*)/i', $blob, $match)) {
            return;
        }
        //nazwa postera
        $posterFileName = substr($file->name, 0, strpos($file->name, '.')) . '-' . $file->id . '.' . \Mmi\Http\ResponseTypes::getExtensionByType($match[1]);
        //próba utworzenia katalogu
        try {
            //tworzenie katalogu
            mkdir(dirname($file->getRealPath()), 0777, true);
        } catch (\Exception $e) {
            //nic
        }
        //zapis
        file_put_contents(str_replace($file->name, $posterFileName, $file->getRealPath()), base64_decode($match[2]));
        //zapis do rekordu
        $file->data->posterFileName = $posterFileName;
        return $posterFileName;
    }

}
