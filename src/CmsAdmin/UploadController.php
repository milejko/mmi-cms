<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Cms\Mvc\ViewHelper\Thumb;
use Cms\Orm\CmsFileQuery;
use Mmi\Http\Request;
use Mmi\Mvc\ActionHelper;
use Mmi\Mvc\Controller;

/**
 * Kontroler pobierania plików
 */
class UploadController extends Controller
{

    /**
     * @Inject
     * @var ActionHelper
     */
    private $actionHelper;

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
    public function pluploadAction(Request $request)
    {
        set_time_limit(5 * 60);
        //obiekt handlera plupload
        $pluploadHandler = new Model\PluploadHandler();
        //jeśli wystąpił błąd
        if (!$pluploadHandler->handle()) {
            return $this->_jsonError($pluploadHandler->getErrorCode(), $pluploadHandler->getErrorMessage());
        }
        //jeśli wykonać operację po przesłaniu całego pliku i zapisaniu rekordu
        if ($request->getPost()->afterUpload && null !== $record = $pluploadHandler->getSavedCmsFileRecord()) {
            $this->_operationAfter($request->getPost()->afterUpload, $record);
        }

        return json_encode(['result' => 'OK', 'cmsFileId' => $pluploadHandler->getSavedCmsFileId()]);
    }

    /**
     * Odbieranie danych z plugina Plupload
     */
    public function multiuploadAction(Request $request)
    {
        set_time_limit(5 * 60);
        //obiekt handlera plupload
        $pluploadHandler = new Model\PluploadHandler();
        //jeśli wystąpił błąd
        if (!$pluploadHandler->handle()) {
            return $this->_jsonError($pluploadHandler->getErrorCode(), $pluploadHandler->getErrorMessage());
        }
        //jeśli wykonać operację po przesłaniu całego pliku i zapisaniu rekordu
        if ($request->getPost()->afterUpload && null !== $record = $pluploadHandler->getSavedCmsFileRecord()) {
            $this->_operationAfter($request->getPost()->afterUpload, $record);
        }

        return json_encode(['result' => 'OK', 'cmsFileId' => $pluploadHandler->getSavedCmsFileId()]);
    }

    /**
     * Zwraca listę aktualnych plików przypiętych do obiektu formularza
     */
    public function currentAction(Request $request)
    {
        $objectId = !empty($request->getPost()->objectId) ? $request->getPost()->objectId : null;
        switch ($request->getPost()->fileTypes) {
            case 'images' :
                //zapytanie o obrazki
                $query = CmsFileQuery::imagesByObject($request->getPost()->object, $objectId);
                break;
            case 'notImages' :
                //wszystkie pliki bez obrazków
                $query = CmsFileQuery::notImagesByObject($request->getPost()->object, $objectId);
                break;
            default :
                //domyślne zapytanie o wszystkie pliki
                $query = CmsFileQuery::byObject($request->getPost()->object, $objectId);
        }
        //wybieranie rekordów z rozmiarem i mimetypem
        $records = $query
            ->whereSize()->notEquals(null)
            ->whereMimeType()->notEquals(null)
            ->find();
        foreach ($records as $record) {
            $record->data = $record->data->toArray();
        }

        //zwrot json'a z plikami
        return json_encode(
            [
                'result' => 'OK',
                'files'  => $records->toArray(),
            ]
        );
    }

    /**
     * Usuwa wybrany rekord pliku
     */
    public function deleteAction(Request $request)
    {
        //szukamy rekordu pliku
        if (!$request->getPost()->cmsFileId || null === $record = (new CmsFileQuery)->findPk($request->getPost()->cmsFileId)) {
            return $this->_jsonError(178);
        }
        //sprawdzenie zgodności z obiektem formularza
        if ($record->object === $request->getPost()->object && $record->objectId == $request->getPost()->objectId) {
            //usuwanie
            if ($record->delete()) {
                //jeśli wykonać operację po usunięciu
                if ($request->getPost()->afterDelete) {
                    $this->_operationAfter($request->getPost()->afterDelete, $record);
                }

                return json_encode(['result' => 'OK']);
            }
        }

        return $this->_jsonError(178);
    }

    /**
     * Zwraca minaturę wybranego rekord pliku
     */
    public function thumbnailAction(Request $request)
    {
        if (!$request->getPost()->cmsFileId) {
            return $this->_jsonError(179);
        }
        //szukamy rekordu pliku
        if (null !== $record = (new CmsFileQuery)->findPk($request->getPost()->cmsFileId)) {
            //sprawdzenie czy obrazek
            if ($record->class === 'image') {
                try {
                    $thumb = new Thumb($this->view);
                    $url   = $thumb->thumb($record, 'scaley', '60');
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
     * Zwraca minaturę wybranego rekord pliku
     */
    public function multithumbnailAction(Request $request)
    {
        if (!$request->getPost()->cmsFileId) {
            return $this->_jsonError(179, 'No file id specified');
        }
        //szukamy rekordu pliku
        if (null !== $record = (new CmsFileQuery)->findPk($request->getPost()->cmsFileId)) {
            //sprawdzenie czy obrazek
            if ($record->class === 'image') {
                try {
                    $thumbHelper = new Thumb($this->view);
                    $thumb       = $thumbHelper->thumb($record, 'scalecrop', '300');
                    if (!empty($thumb)) {
                        return json_encode(['result' => 'OK', 'thumb' => $thumb]);
                    }
                } catch (\Exception $ex) {
                    return $this->_jsonError(179, 'Thumb creation failed');
                }
            }

            if (in_array($record->class, ['audio', 'application', 'video', 'text'])) {
                return json_encode(['result' => 'OK', 'class' => $record->class]);
            }

            return json_encode(['result' => 'OK', 'class' => 'file']);
        }

        return $this->_jsonError(179, 'File not found');
    }

    /**
     * Zwraca dane opisujące rekord pliku
     */
    public function detailsAction(Request $request)
    {
        if (!$request->getPost()->cmsFileId) {
            return $this->_jsonError(185);
        }
        //szukamy rekordu pliku
        if (null == $record = (new CmsFileQuery)->findPk($request->getPost()->cmsFileId)) {
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
        $data['urlFile'] = $record->getUrl();
        if ($record->data->posterFileName) {
            $data['poster'] = $record->getPosterUrl();
        }

        return json_encode(['result' => 'OK', 'record' => $record, 'data' => $data]);
    }

    /**
     * Aktualizuje dane opisujące rekord pliku: tytuł, autora, źródło
     */
    public function describeAction(Request $request)
    {
        //sprawdzamy, czy jest id pliku i form
        if (!$request->getPost()->cmsFileId || !is_array($request->getPost()->form)) {
            return $this->_jsonError(186);
        }
        //szukamy rekordu pliku
        if (null === $record = (new CmsFileQuery)->findPk($request->getPost()->cmsFileId)) {
            return $this->_jsonError(186);
        }
        $formData = [];
        //pobranie danych
        foreach ($request->getPost()->form as $field) {
            $formData[$field['name']] = $field['value'];
            if ($field['name'] == 'active') {
                continue;
            }
            if ($field['name'] == 'original') {
                //dodajemy rozszerzenie
                //pozycja ostatniej kropki w nazwie - rozszerzenie pliku
                $pointPosition = strrpos($record->name, '.');
                if ($pointPosition !== false) {
                    $formData['original'] .= substr($record->name, $pointPosition);
                }
                $record->data->original = $formData['original'];
                continue;
            }
            $record->data->{$field['name']} = $field['value'];
        }
        //próba zapisu postera
        if (isset($request->getPost()->form['poster']) && $request->getPost()->form['poster']) {
            $formData['posterFileName'] = $this->_savePoster($request->getPost()->form['poster'], $record);
        }
        if ($record->data instanceof \Mmi\DataObject) {
            //czyszczenie nieprzesłanych checkboxów
            foreach (array_keys($record->data->toArray()) as $name) {
                //nie czyścimy postera jeśli był wgrany
                if ($name == 'posterFileName') {
                    continue;
                }
                if (!isset($formData[$name])) {
                    $record->data->{$name} = null;
                }
            }
        }
        $record->active   = \array_key_exists('active', $formData);
        $record->original = isset($formData['original']) ? $formData['original'] : $record->original;
        if (!$record->save()) {
            return $this->_jsonError(186);
        }
        //jeśli wykonać operację po edycji
        if ($request->getPost()->afterEdit) {
            $this->_operationAfter($request->getPost()->afterEdit, $record);
        }

        return json_encode(['result' => 'OK']);
    }

    /**
     * Przekierowanie na plik
     *
     * @return string
     */
    public function downloadAction(Request $request)
    {
        if (null === $file = (new CmsFileQuery)->byObject($request->object, $request->objectId)
                ->findPk($request->id)) {
            return '';
        }
        $this->getResponse()->redirectToUrl($file->getUrl('download'));
    }

    /**
     * Zapisuje kolejność plików
     */
    public function sortAction(Request $request)
    {
        $order = $request->getPost()->order;
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
     *
     * @param integer $code
     * @param string  $message
     *
     * @return string
     */
    protected function _jsonError($code = 403, $message = '')
    {
        return json_encode([
                               'result' => 'ERR',
                               'error'  => [
                                   'code'    => $code,
                                   'message' => $message,
                               ],
                           ]);
    }

    /**
     * Wykonuje dodatkową operację po innym zdarzeniu
     *
     * @param array                  $action
     * @param \Cms\Orm\CmsFileRecord $record
     *
     * @return mixed
     */
    protected function _operationAfter($action, \Cms\Orm\CmsFileRecord $record)
    {
        return $this->actionHelper->action(new \Mmi\Http\Request(array_merge($record->toArray(), $action)));
    }

    /**
     * Zapisanie postera dla video
     *
     * @param string                 $blob
     * @param \Cms\Orm\CmsFileRecord $file
     *
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
