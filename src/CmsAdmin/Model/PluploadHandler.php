<?php

namespace CmsAdmin\Model;

use Mmi\App\App;
use Mmi\Http\Request;
use Mmi\Http\Response;

define('PLUPLOAD_TMPDIR_ERR', 100);
define('PLUPLOAD_INPUT_ERR', 101);
define('PLUPLOAD_OUTPUT_ERR', 102);
define('PLUPLOAD_MOVE_ERR', 103);
define('PLUPLOAD_TYPE_ERR', 104);
define('PLUPLOAD_SECURITY_ERR', 105);
define('PLUPLOAD_UNKNOWN_ERR', 111);

/**
 * Klasa do obsługi odbierania danych z pluginu Plupload
 */
class PluploadHandler
{
    /**
     * Opisy możliwych błędów
     * @var array
     */
    private static $errors = [
        PLUPLOAD_MOVE_ERR => "Błąd przenoszenia pliku",
        PLUPLOAD_INPUT_ERR => "Błąd odczytu danych wejściowych",
        PLUPLOAD_OUTPUT_ERR => "Błąd zapisu danych",
        PLUPLOAD_TMPDIR_ERR => "Błąd dostępu do katalogu zapisu",
        PLUPLOAD_TYPE_ERR => "Błąd: niedozwolony typ pliku",
        PLUPLOAD_UNKNOWN_ERR => "Nieznany błąd",
        PLUPLOAD_SECURITY_ERR => "Błąd zabezpieczeń"
    ];

    /**
     * Kod błędu, jaki wystąpił przy odbieraniu pliku
     * @var integer
     */
    private $errorCode = null;

    /**
     * Opis błędu, jaki wystąpił przy odbieraniu pliku
     * @var string
     */
    private $errorMessage = "";

    /**
     * Nazwa oryginalna pliku
     * @var string
     */
    private $fileName;

    /**
     * Nazwa unikalna pliku
     * @var string
     */
    private $fileId;

    /**
     * Rozmiar przesłanego pliku
     * @var integer
     */
    private $fileSize;

    /**
     * Typ obiektu formularza
     * @var string
     */
    private $formObject;

    /**
     * Id obiektu z formularza
     * @var integer
     */
    private $formObjectId = null;

    /**
     * Id rekordu pliku Cms w bazie
     * @var integer
     */
    private $cmsFileId;

    /**
     * Zapisany rekord pliku Cms
     * @var \Cms\Orm\CmsFileRecord
     */
    private $cmsFileRecord;

    /**
     * Ścieżka do katalogu do zapisu plików tymczasowych
     * @var string
     */
    private $targetDir;

    /**
     * Ścieżka do zapisu pliku tymczasowego
     * @var string
     */
    private $filePath;

    /**
     * Ścieżka do zapisu fragmentu pliku tymczasowego
     * @var string
     */
    private $filePathPart;

    /**
     * Uchwyt do zapisu pliku tymczasowego
     * @var resource
     */
    private $fileHandle;

    /**
     * Uchwyt do odczytu danych wejściowych
     * @var resource
     */
    private $inputHandle;

    /**
     * Obiekt odpowiedzi
     * @var \Mmi\Http\Response
     */
    private $response;

    /**
     * Obiekt żądania
     * @var \Mmi\Http\Request
     */
    private $request;

    /**
     * Numer kolejny kawałka pliku
     * @var integer
     */
    private $_chunk = 0;

    /**
     * Ilość wszystkich kawałków na jakie podzielono plik
     * @var integer
     */
    private $_chunks = 0;

    /**
     * Typ zawartości w żądaniu
     * @var string
     */
    private $contentType;

    /**
     * Filtry dla akceptowanych plików
     * @var array
     */
    private $filters;

    /**
     * Konstruktor
     */
    public function __construct($path = '')
    {
        if (!$path) {
            $path = BASE_PATH . '/var/data/plupload/';
        }
        $this->setTargetDir($path);
    }

    /**
     * Ustawia ścieżkę do katalogu do zapisu plików tymczasowych
     * @param string $path
     * @return \CmsAdmin\Model\PluploadHandler
     */
    public function setTargetDir($path)
    {
        $this->targetDir = rtrim($path, '/') . '/';
        return $this;
    }

    /**
     * Ustawia obiekt żądania
     * @param \Mmi\Http\Request $request
     * @return \CmsAdmin\Model\PluploadHandler
     */
    public function setRequest(\Mmi\Http\Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Ustawia obiekt odpowiedzi
     * @param \Mmi\Http\Response $response
     * @return \CmsAdmin\Model\PluploadHandler
     */
    public function setResponse(\Mmi\Http\Response $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Obsługa procesu odbierania pliku
     * @param boolean $headers Czy wysłać nagłówki no-cache
     * @return bool
     */
    public function handle($headers = true)
    {
        $this->_setRequestAndResponse();
        //czy wysłać nagłówki no-cache
        if ($headers) {
            $this->_setResponseHeaders();
        }
        try {
            if (!$this->_prepareUploadData()) {
                return false;
            }
            if (strpos($this->contentType, "multipart") !== false) {
                if (!$this->_multipart()) {
                    return false;
                }
            } else {
                if (!$this->_input()) {
                    return false;
                }
            }
            if (!$this->_ifComplete()) {
                return false;
            }
        } catch (\Exception $e) {
            $this->_setError(PLUPLOAD_UNKNOWN_ERR, "Nieznany błąd: przechwycony wyjątek: " . $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Ustawia obiekty żądania i odpowiedzi, jeśli nie są ustawione
     * @return \CmsAdmin\Model\PluploadHandler
     */
    private function _setRequestAndResponse()
    {
        if (!is_object($this->request)) {
            $this->request = App::$di->get(Request::class);
        }
        if (!is_object($this->response)) {
            $this->response = App::$di->get(Response::class);
        }
        return $this;
    }

    /**
     * Przygotowuje dane w polach klasy opisujące przesyłany fragment pliku
     * @return boolean
     */
    private function _prepareUploadData()
    {
        if (!$this->_createTargetDir()) {
            $this->_setError(PLUPLOAD_TMPDIR_ERR);
            return false;
        }
        $post = $this->request->getPost();
        $this->_chunk = ($post->chunk) ? intval($post->chunk) : 0;
        $this->_chunks = ($post->chunks) ? intval($post->chunks) : 0;
        $this->fileName = $post->name;
        $this->fileId = $post->fileId;
        $this->fileSize = $post->fileSize;
        $this->formObject = $post->formObject;
        $this->formObjectId = ($post->formObjectId) ? $post->formObjectId : null;
        $this->cmsFileId = ($post->cmsFileId > 0) ? $post->cmsFileId : null;
        $this->filters = ($post->filters) ? $post->filters : [];
        if (!$this->fileName || !$this->fileId || !$this->fileSize || !$this->formObject) {
            $this->_setError(PLUPLOAD_INPUT_ERR, "Błąd: niekompletne parametry żądania");
            return false;
        }
        $this->filePath = $this->targetDir . $this->fileId . sha1($this->fileName);
        if (strrpos($this->fileName, '.') > 0) {
            $this->filePath .= substr($this->fileName, strrpos($this->fileName, '.'));
        }
        $this->filePathPart = $this->filePath . '.part';
        $this->contentType = $this->request->getContentType();
        return true;
    }

    /**
     * Ustawia nagłówki standardowe odpowiedzi
     * @return void
     */
    private function _setResponseHeaders()
    {
        $this->response->setHeader("Expires", "Mon, 26 Jul 1997 05:00:00 GMT", true)
            ->setHeader("Last-Modified", gmdate("D, d M Y H:i:s") . " GMT", true)
            ->setHeader("Cache-Control", "no-store, no-cache, must-revalidate", true)
            ->setHeader("Cache-Control", "post-check=0, pre-check=0", false)
            ->setHeader("Pragma", "no-cache", true)
            ->sendHeaders();
    }

    /**
     * Tworzy strukturę katalogów do zapisania plików tymczasowych
     * @return boolean
     */
    private function _createTargetDir()
    {
        if (!$this->targetDir) {
            return false;
        }
        $targetDir = rtrim($this->targetDir, '/');
        if (!file_exists($targetDir)) {
            if (!@mkdir($targetDir, 0777, true)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Zwraca kod błędu, jaki wystąpił przy odbieraniu pliku
     * @return integer
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Zwraca opis błędu, jaki wystąpił przy odbieraniu pliku
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Zwraca id zapisanego rekordu pliku
     * @return integer
     */
    public function getSavedCmsFileId()
    {
        if ($this->cmsFileRecord) {
            return $this->cmsFileRecord->id;
        }
        return null;
    }

    /**
     * Zwraca id zapisanego rekordu pliku
     * @return integer
     */
    public function getSavedCmsFileName()
    {
        if ($this->cmsFileRecord) {
            return $this->cmsFileRecord->name;
        }
        return null;
    }

    /**
     * Zwraca zapisany rekord pliku
     * @return \Cms\Orm\CmsFileRecord
     */
    public function getSavedCmsFileRecord()
    {
        return $this->cmsFileRecord;
    }

    /**
     * Ustawia błąd, jaki wystąpił podczas odbierania pliku
     * @param integer $code
     * @param string $message
     * @return \CmsAdmin\Model\PluploadHandler
     */
    private function _setError($code, $message = "")
    {
        if (isset(self::$errors[$code])) {
            $this->errorCode = $code;
            $this->errorMessage = self::$errors[$code];
        } else {
            $this->errorCode = PLUPLOAD_UNKNOWN_ERR;
            $this->errorMessage = self::$errors[PLUPLOAD_UNKNOWN_ERR];
        }
        if ($message) {
            $this->errorMessage = $message;
        }
        return $this;
    }

    /**
     * Obsługa odbierania danych z formularza
     * @return boolean
     */
    private function _multipart()
    {
        $files = $this->request->getFiles()->getAsArray();
        //brak dodanego pliku (part'a)
        if (!isset($files['file']) || !isset($files['file'][0])) {
            $this->_setError(PLUPLOAD_INPUT_ERR);
            return false;
        }
        $file = $files['file'][0];
        /* @var $file \Mmi\Http\RequestFile */
        if ($file->tmpName && is_uploaded_file($file->tmpName)) {
            if (!$this->_readWrite($file->tmpName, true)) {
                return false;
            }
        } else {
            $this->_setError(PLUPLOAD_INPUT_ERR);
            return false;
        }
        return true;
    }

    /**
     * Obsługa odbierania danych ze strumienia
     * @return boolean
     */
    private function _input()
    {
        if (!$this->_readWrite("php://input", false)) {
            return false;
        }
        return true;
    }

    /**
     * Odczyt danych z wejścia i zapis na wyjściu
     * @param string $input
     * @param boolean $unlink
     * @return boolean
     */
    private function _readWrite($input, $unlink)
    {
        $this->fileHandle = fopen($this->filePathPart, $this->_chunk == 0 ? "wb" : "ab");
        //brak uchwytu pliku wyjściowego
        if (!$this->fileHandle) {
            $this->_setError(PLUPLOAD_OUTPUT_ERR);
            return false;
        }
        $this->inputHandle = fopen($input, "rb");
        //brak uchwytu danych wejściowych
        if (!$this->inputHandle) {
            $this->_setError(PLUPLOAD_INPUT_ERR);
            return false;
        }
        while ($buff = fread($this->inputHandle, 8192)) {
            fwrite($this->fileHandle, $buff);
        }
        fclose($this->inputHandle);
        fclose($this->fileHandle);
        if ($unlink) {
            @unlink($input);
        }
        return true;
    }

    /**
     * Sprawdza czy odebrano już cały plik i wywołuje akcje na zakończenie uploadu,
     * między innymi zapis pliku i rekordu do bazy
     * @return boolean
     */
    private function _ifComplete()
    {
        //jeśli są kawałki i nie jest to ostatni kawałek, wychodzi z true
        if ($this->_chunks && $this->_chunk != $this->_chunks - 1) {
            return true;
        }
        //zamiana nazwy
        if (!rename($this->filePathPart, $this->filePath)) {
            $this->_setError(PLUPLOAD_MOVE_ERR);
            return false;
        }
        //zapis rekordu pliku
        if (!$this->_saveFile()) {
            if ($this->errorCode === null) {
                $this->_setError(PLUPLOAD_MOVE_ERR);
            }
            return false;
        }
        return true;
    }

    /**
     * Zapis pliku zależny danych wejściowych
     * @return boolean
     */
    private function _saveFile()
    {
        $requestFile = $this->_getRequestFile();
        //jeśli niedopuszczalny plik
        if (!$this->_filterFile($requestFile)) {
            //usuwamy plik z katalogu plupload
            @unlink($this->filePath);
            $this->_setError(PLUPLOAD_TYPE_ERR, "Niedopuszczalny typ pliku");
            return false;
        }
        //jeśli przesłano plik dla konkretnego id w bazie
        if ($this->cmsFileId) {
            if (null !== $this->cmsFileRecord = (new \Cms\Orm\CmsFileQuery())->findPk($this->cmsFileId)) {
                return $this->_replaceFile($requestFile);
            }
        }
        //nie było pliku - tworzymy nowy
        return $this->_createNewFile($requestFile);
    }

    /**
     * Zwraca obiekt pliku request
     * @return \Mmi\Http\RequestFile
     */
    private function _getRequestFile()
    {
        $data = [
            'name' => $this->fileName,
            'size' => $this->fileSize,
            'tmp_name' => $this->filePath
        ];
        return new \Mmi\Http\RequestFile($data);
    }

    /**
     * Sprawdza, czy plik spełnia warunki filtrowania
     * @param \Mmi\Http\RequestFile $requestFile
     * @return boolean
     */
    private function _filterFile(\Mmi\Http\RequestFile $requestFile)
    {
        if (empty($this->filters)) {
            return true;
        }
        if (!isset($this->filters['mime_types']) || empty($this->filters['mime_types'])) {
            return true;
        }
        $allowedTypes = $allowedExts = [];
        foreach ($this->filters['mime_types'] as $mt) {
            if (array_key_exists('mime', $mt)) {
                $allowedTypes[] = strtolower($mt['mime']);
            } elseif (array_key_exists('extensions', $mt)) {
                $allowedExts = array_merge($allowedExts, explode(",", strtolower(preg_replace('/ \s/', '', $mt['extensions']))));
            }
        }
        //sprawdzamy typy mime
        if (!in_array(strtolower($requestFile->type), $allowedTypes)) {
            return false;
        }
        //sprawdzamy rozszerzenie
        if (strrpos($this->fileName, '.') > 0) {
            $ext = strtolower(substr($this->fileName, strrpos($this->fileName, '.') + 1));
            if (in_array($ext, $allowedExts)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Tworzy nowy rekord pliku i zapisuje go
     * @param \Mmi\Http\RequestFile $requestFile
     * @return boolean
     */
    private function _createNewFile(\Mmi\Http\RequestFile $requestFile)
    {
        if (null === $this->cmsFileRecord = \Cms\Model\File::appendFile($requestFile, $this->formObject, $this->formObjectId)) {
            $this->_setError(PLUPLOAD_MOVE_ERR, "Błąd tworzenia nowego rekordu pliku");
            $result = false;
        } else {
            $result = $this->cmsFileRecord->save();
        }
        //usuwamy plik z katalogu plupload
        @unlink($this->filePath);
        return $result;
    }

    /**
     * Zamienia plik w istniejącym rekordzie
     * @param \Mmi\Http\RequestFile $requestFile
     * @return boolean
     */
    private function _replaceFile(\Mmi\Http\RequestFile $requestFile)
    {
        if ($this->cmsFileRecord === null) {
            $result = false;
        } else {
            $result = ($this->cmsFileRecord->replaceFile($requestFile) && $this->cmsFileRecord->save());
        }
        if ($result === false) {
            $this->_setError(PLUPLOAD_MOVE_ERR, "Błąd podczas nadpisywania pliku");
        }
        //usuwamy plik z katalogu plupload
        @unlink($this->filePath);
        return $result;
    }
}
