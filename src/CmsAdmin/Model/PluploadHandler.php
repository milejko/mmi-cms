<?php

namespace CmsAdmin\Model;

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
class PluploadHandler {

	/**
	 * Opisy możliwych błędów
	 * @var array
	 */
	private static $_errors = [
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
	private $_errorCode = null;

	/**
	 * Opis błędu, jaki wystąpił przy odbieraniu pliku
	 * @var string
	 */
	private $_errorMessage = "";

	/**
	 * Nazwa oryginalna pliku
	 * @var string
	 */
	private $_fileName;

	/**
	 * Nazwa unikalna pliku
	 * @var string
	 */
	private $_fileId;
	
	/**
	 * Rozmiar przesłanego pliku
	 * @var integer
	 */
	private $_fileSize;
	
	/**
	 * Typ obiektu formularza
	 * @var string
	 */
	private $_formObject;
	
	/**
	 * Id obiektu z formularza
	 * @var integer
	 */
	private $_formObjectId = null;
	
	/**
	 * Id rekordu pliku Cms w bazie
	 * @var integer
	 */
	private $_cmsFileId;
	
	/**
	 * Zapisany rekord pliku Cms
	 * @var \Cms\Orm\CmsFileRecord
	 */
	private $_cmsFileRecord;

	/**
	 * Ścieżka do katalogu do zapisu plików tymczasowych
	 * @var string
	 */
	private $_targetDir;

	/**
	 * Ścieżka do zapisu pliku tymczasowego
	 * @var string
	 */
	private $_filePath;

	/**
	 * Ścieżka do zapisu fragmentu pliku tymczasowego
	 * @var string
	 */
	private $_filePathPart;

	/**
	 * Uchwyt do zapisu pliku tymczasowego
	 * @var resource
	 */
	private $_fileHandle;

	/**
	 * Uchwyt do odczytu danych wejściowych
	 * @var resource
	 */
	private $_inputHandle;

	/**
	 * Obiekt odpowiedzi
	 * @var \Mmi\Http\Response
	 */
	private $_response;

	/**
	 * Obiekt żądania
	 * @var \Mmi\Http\Request
	 */
	private $_request;

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
	private $_contentType;
	
	/**
	 * Filtry dla akceptowanych plików
	 * @var array
	 */
	private $_filters;

	/**
	 * Konstruktor
	 */
	function __construct($path = '') {
		if (!$path) {
			$path = BASE_PATH . '/var/plupload/';
		}
		$this->setTargetDir($path);
	}

	/**
	 * Ustawia ścieżkę do katalogu do zapisu plików tymczasowych
	 * @param string $path
	 * @return \CmsAdmin\Model\PluploadHandler
	 */
	public function setTargetDir($path) {
		$this->_targetDir = rtrim($path, '/') . '/';
		return $this;
	}

	/**
	 * Ustawia obiekt żądania
	 * @param \Mmi\Http\Request $request
	 * @return \CmsAdmin\Model\PluploadHandler
	 */
	public function setRequest(\Mmi\Http\Request $request) {
		$this->_request = $request;
		return $this;
	}

	/**
	 * Ustawia obiekt odpowiedzi
	 * @param \Mmi\Http\Response $response
	 * @return \CmsAdmin\Model\PluploadHandler
	 */
	public function setResponse(\Mmi\Http\Response $response) {
		$this->_response = $response;
		return $this;
	}

	/**
	 * Obsługa procesu odbierania pliku
	 * @param boolean $headers Czy wysłać nagłówki no-cache
	 */
	public function handle($headers = true) {
		$this->_setRequestAndResponse();
		//czy wysłać nagłówki no-cache
		if ($headers) {
			$this->_setResponseHeaders();
		}
		try {
			if (!$this->_prepareUploadData()) {
				return false;
			}
			if (strpos($this->_contentType, "multipart") !== false) {
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
	 * Ustawia obiekty żądania i odpowiedzi z FrontControllera, jeśli nie są ustawione
	 * @return \CmsAdmin\Model\PluploadHandler
	 */
	private function _setRequestAndResponse() {
		if (!is_object($this->_request)) {
			$this->_request = \Mmi\App\FrontController::getInstance()->getRequest();
		}
		if (!is_object($this->_response)) {
			$this->_response = \Mmi\App\FrontController::getInstance()->getResponse();
		}
		return $this;
	}

	/**
	 * Przygotowuje dane w polach klasy opisujące przesyłany fragment pliku
	 * @return boolean
	 */
	private function _prepareUploadData() {
		if (!$this->_createTargetDir()) {
			$this->_setError(PLUPLOAD_TMPDIR_ERR);
			return false;
		}
		$post = $this->_request->getPost();
		$this->_chunk = ($post->chunk) ? intval($post->chunk) : 0;
		$this->_chunks = ($post->chunks) ? intval($post->chunks) : 0;
		$this->_fileName = $post->name;
		$this->_fileId = $post->fileId;
		$this->_fileSize = $post->fileSize;
		$this->_formObject = $post->formObject;
		$this->_formObjectId = ($post->formObjectId) ? $post->formObjectId : null;
		$this->_cmsFileId = ($post->cmsFileId > 0) ? $post->cmsFileId : null;
		$this->_filters = ($post->filters) ? $post->filters : [];
		if (!$this->_fileName || !$this->_fileId || !$this->_fileSize || !$this->_formObject) {
			$this->_setError(PLUPLOAD_INPUT_ERR, "Błąd: niekompletne parametry żądania");
			return false;
		}
		$this->_filePath = $this->_targetDir . $this->_fileId;
		if (strrpos($this->_fileName, '.') > 0) {
			$this->_filePath .= substr($this->_fileName, strrpos($this->_fileName, '.'));
		}
		$this->_filePathPart = $this->_filePath . '.part';
		$this->_contentType = $this->_request->getContentType();
		return true;
	}

	/**
	 * Ustawia nagłówki standardowe odpowiedzi
	 * @return void
	 */
	private function _setResponseHeaders() {
		$this->_response->setHeader("Expires", "Mon, 26 Jul 1997 05:00:00 GMT", true)
			->setHeader("Last-Modified", gmdate("D, d M Y H:i:s") . " GMT", true)
			->setHeader("Cache-Control", "no-store, no-cache, must-revalidate", true)
			->setHeader("Cache-Control", "post-check=0, pre-check=0", false)
			->setHeader("Pragma", "no-cache", true);
	}

	/**
	 * Tworzy strukturę katalogów do zapisania plików tymczasowych
	 * @return boolean
	 */
	private function _createTargetDir() {
		if (!$this->_targetDir) {
			return false;
		}
		$targetDir = rtrim($this->_targetDir, '/');
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
	public function getErrorCode() {
		return $this->_errorCode;
	}

	/**
	 * Zwraca opis błędu, jaki wystąpił przy odbieraniu pliku
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->_errorMessage;
	}
	
	/**
	 * Zwraca id zapisanego rekordu pliku
	 * @return integer
	 */
	public function getSavedCmsFileId() {
		if ($this->_cmsFileRecord) {
			return $this->_cmsFileRecord->id;
		}
		return null;
	}

	/**
	 * Ustawia błąd, jaki wystąpił podczas odbierania pliku
	 * @param integer $code
	 * @param string $message
	 * @return \CmsAdmin\Model\PluploadHandler
	 */
	private function _setError($code, $message = "") {
		if (isset(self::$_errors[$code])) {
			$this->_errorCode = $code;
			$this->_errorMessage = self::$_errors[$code];
		} else {
			$this->_errorCode = PLUPLOAD_UNKNOWN_ERR;
			$this->_errorMessage = self::$_errors[PLUPLOAD_UNKNOWN_ERR];
		}
		if ($message) {
			$this->_errorMessage = $message;
		}
		return $this;
	}

	/**
	 * Obsługa odbierania danych z formularza
	 * @return boolean
	 */
	private function _multipart() {
		$files = $this->_request->getFiles()->toArray();
		if (!isset($files['file']) || empty($files['file'])) {
			$this->_setError(PLUPLOAD_INPUT_ERR);
			return false;
		}
		$file = reset($files['file']);
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
	private function _input() {
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
	private function _readWrite($input, $unlink) {
		$this->_fileHandle = fopen($this->_filePathPart, $this->_chunk == 0 ? "wb" : "ab");
		//brak uchwytu pliku wyjściowego
		if (!$this->_fileHandle) {
			$this->_setError(PLUPLOAD_OUTPUT_ERR);
			return false;
		}
		$this->_inputHandle = fopen($input, "rb");
		//brak uchwytu danych wejściowych
		if (!$this->_inputHandle) {
			$this->_setError(PLUPLOAD_INPUT_ERR);
			return false;
		}
		while ($buff = fread($this->_inputHandle, 8192)) {
			fwrite($this->_fileHandle, $buff);
		}
		fclose($this->_inputHandle);
		fclose($this->_fileHandle);
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
	private function _ifComplete() {
		//jeśli są kawałki i nie jest to ostatni kawałek, wychodzi z true
		if ($this->_chunks && $this->_chunk != $this->_chunks - 1) {
			return true;
		}
		//zamiana nazwy
		if (!rename($this->_filePathPart, $this->_filePath)) {
			$this->_setError(PLUPLOAD_MOVE_ERR);
			return false;
		}
		//zapis rekordu pliku
		if (!$this->_saveFile()) {
			if ($this->_errorCode === null) {
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
	private function _saveFile() {
		$requestFile = $this->_getRequestFile();
		//jeśli niedopuszczony plik
		if (!$this->_filterFile($requestFile)) {
			//usuwamy plik z katalogu plupload
			@unlink($this->_filePath);
			$this->_setError(PLUPLOAD_TYPE_ERR, "Niedopuszczony typ pliku");
			return false;
		}
		//jeśli przesłano plik dla konkretnego id w bazie
		if ($this->_cmsFileId) {
			if (null !== $this->_cmsFileRecord = (new \Cms\Orm\CmsFileQuery)->findPk($this->_cmsFileId)) {
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
	private function _getRequestFile() {
		$data = [
			'name' => $this->_fileName,
			'size' => $this->_fileSize,
			'tmp_name' => $this->_filePath
		];
		return new \Mmi\Http\RequestFile($data);
	}
	
	/**
	 * Sprawdza, czy plik spełnia warunki filtrowania
	 * @param \Mmi\Http\RequestFile $requestFile
	 * @return boolean
	 */
	private function _filterFile(\Mmi\Http\RequestFile $requestFile) {
		if (empty($this->_filters)) {
			return true;
		}
		if (!isset($this->_filters['mime_types']) || empty($this->_filters['mime_types'])) {
			return true;
		}
		$allowedTypes = $allowedExts = [];
		foreach ($this->_filters['mime_types'] as $mt) {
			if (array_key_exists('mime', $mt)) {
				$allowedTypes[] = strtolower($mt['mime']);
			} elseif (array_key_exists('extensions', $mt)) {
				$allowedExts = array_merge($allowedExts, explode(",", strtolower($mt['extensions'])));
			}
		}
		//sprawdzamy typy mime
		if (in_array($requestFile->type, $allowedTypes)) {
			return true;
		}
		//sprawdzamy rozszerzenie
		if (strrpos($this->_fileName, '.') > 0) {
			$ext = substr($this->_fileName, strrpos($this->_fileName, '.') + 1);
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
	private function _createNewFile(\Mmi\Http\RequestFile $requestFile) {
		if (null === $this->_cmsFileRecord = \Cms\Model\File::appendFile($requestFile, $this->_formObject, $this->_formObjectId)) {
			$this->_setError(PLUPLOAD_MOVE_ERR, "Błąd tworzenia nowego rekordu pliku");
			$result = false;
		} else {
			$result = true;
		}
		//usuwamy plik z katalogu plupload
		@unlink($this->_filePath);
		return $result;
	}

	/**
	 * Zamienia plik w istniejącym rekordzie
	 * @param \Mmi\Http\RequestFile $requestFile
	 * @return boolean
	 */
	private function _replaceFile(\Mmi\Http\RequestFile $requestFile) {
		if ($this->_cmsFileRecord === null) {
			$result = false;
		} else {
			$result = ($this->_cmsFileRecord->replaceFile($requestFile) && $this->_cmsFileRecord->save());
		}
		if ($result === false) {
			$this->_setError(PLUPLOAD_MOVE_ERR, "Błąd podczas nadpisywania pliku");
		}
		//usuwamy plik z katalogu plupload
		@unlink($this->_filePath);
		return $result;
	}

}
