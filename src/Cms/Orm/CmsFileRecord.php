<?php

namespace Cms\Orm;

/**
 * Rekord pliku
 */
class CmsFileRecord extends \Mmi\Orm\Record {

	public $id;

	/**
	 * Klasa pliku: np. image
	 * @var string
	 */
	public $class;
	public $mimeType;

	/**
	 * Nazwa fizycznego zasobu
	 * @var string
	 */
	public $name;

	/**
	 * Nazwa dla użytkownika
	 * @var string
	 */
	public $original;
	public $title;
	public $author;
	public $source;

	/**
	 * Rozmiar w bajtach
	 * @var integer
	 */
	public $size;
	public $dateAdd;
	public $dateModify;
	public $order;

	/**
	 * Flaga "przyklejony"
	 * @var boolean
	 */
	public $sticky;
	public $object;
	public $objectId;

	/**
	 * ID użytkownika CMS
	 * @var integer
	 */
	public $cmsAuthId;

	/**
	 * Aktywny
	 * @var boolean
	 */
	public $active;

	/**
	 * Ustawia plik jako przyklejony w obrębie danego object+objectId
	 * @return bool
	 */
	public function setSticky() {
		//brak pliku
		if ($this->id === null) {
			return false;
		}
		//wyłącza sticky na innych plikach dla tego object+objectId
		foreach (\Cms\Orm\CmsFileQuery::stickyByObject($this->object, $this->objectId)->find() as $related) {
			$related->sticky = 0;
			$related->save();
		}
		$this->sticky = 1;
		return $this->save();
	}

	/**
	 * Pobiera hash dla danej nazwy pliku
	 * @param string $name nazwa pliku
	 * @return string
	 */
	public function getHashName() {
		//brak pliku
		if ($this->id === null) {
			return;
		}
		return substr(md5($this->name . \App\Registry::$config->salt), 0, 8);
	}

	/**
	 * Pobiera fizyczną ścieżkę do pliku
	 * @return type
	 */
	public function getRealPath() {
		//brak prawidłowej nazwy pliku
		if (strlen($this->name) < 4) {
			return;
		}
		//ścieżka na dysku
		return BASE_PATH . '/var/data/' . $this->name[0] . '/' . $this->name[1] . '/' . $this->name[2] . '/' . $this->name[3] . '/' . $this->name;
	}

	/**
	 * Zwraca binarną zawartość pliku
	 * @return mixed
	 */
	public function getBinaryContent() {
		//brak realnej ścieżki
		if (null === ($path = $this->getRealPath())) {
			return null;
		}
		//pobranie pliku
		$content = file_get_contents($path);
		return $content !== false ? $content : null;
	}

	/**
	 * Pobiera adres pliku
	 * @param string $scaleType scale, scalex, scaley, scalecrop
	 * @param string $scale 320, 320x240
	 * @param boolean $absolute link absolutny
	 * @return string adres publiczny pliku
	 */
	public function getUrl($scaleType = 'default', $scale = 0, $absolute = false) {
		//brak pliku
		if ($this->id === null || strlen($this->name) < 4) {
			return;
		}
		//plik źródłowy
		$inputFile = $this->getRealPath();
		//generowanie linku bazowego
		$url = \Mmi\App\FrontController::getInstance()->getView()->url([], true, $absolute);
		//brzydki if, jak aplikacja odpalana jest z podkatalogu
		if ($url === '/') {
			$baseUrl = '/data';
		} else {
			$baseUrl = $url . '/data';
		}
		$fileName = '/' . $this->name[0] . '/' . $this->name[1] . '/' . $this->name[2] . '/' . $this->name[3] . '/' . $scaleType . '/' . $scale . '/' . $this->name;
		//istnieje plik - wiadomość z bufora
		if (\App\Registry::$cache->load($cacheKey = 'cms-file-' . md5($fileName)) === true) {
			return $baseUrl . $fileName;
		}
		//istnieje plik - zwrot ścieżki publicznej
		if (file_exists(BASE_PATH . '/web/data' . $fileName)) {
			\App\Registry::$cache->save(true, $cacheKey);
			return $baseUrl . $fileName;
		}
		//brak pliku źródłowego
		if (!file_exists($inputFile)) {
			return;
		}
		//klasa obrazu - uruchomienie skalera
		if ($this->class == 'image' && !$this->_scaler($inputFile, BASE_PATH . '/web/data/' . $fileName, $scaleType, $scale)) {
			return;
		}
		//tworzenie katalogów
		if (!file_exists(dirname(BASE_PATH . '/web/data/' . $fileName)) && !@mkdir(dirname(BASE_PATH . '/web/data/' . $fileName), 0777, true)) {
			return true;
		}
		//klasa inna niż obraz - kopiowanie zasobu publicznie
		if ($this->class != 'image' && !copy($inputFile, BASE_PATH . '/web/data/' . $fileName)) {
			return;
		}
		//zwrot ścieżki publicznej
		return $baseUrl . $fileName;
	}
	
	/**
	 * Zapisuje plik przesłany na serwer i aktualizuje pola w rekordzie
	 * Uwaga! Metoda nie zapisuje zmian w rekordzie (nie wywołuje save)!
	 * @param \Mmi\Http\RequestFile $file obiekt pliku
	 * @param array $allowedTypes dozwolone typy plików
	 * @return boolean
	 */
	public function replaceFile(\Mmi\Http\RequestFile $file, $allowedTypes = []) {
		//jeśli brak danych pliku
		if (empty($file->name) || empty($file->tmpName)) {
			return false;
		}
		//plik nie jest dozwolony
		if (!empty($allowedTypes) && !in_array($file->type, $allowedTypes)) {
			return false;
		}
		//zapamiętujemy nazwę obecnego pliku na dysku
		if ($this->getOption('currentFile') === null) {
			$this->setOption('currentFile', $this->name);
		}
		//kalkulacja nazwy systemowej
		$name = md5(microtime(true) . $file->tmpName) . substr($file->name, strrpos($file->name, '.'));
		//określanie ścieżki
		$dir = BASE_PATH . '/var/data/' . $name[0] . '/' . $name[1] . '/' . $name[2] . '/' . $name[3];
		//tworzenie ścieżki
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		//zmiana uprawnień
		chmod($file->tmpName, 0664);
		//kopiowanie pliku
		if (!copy($file->tmpName, $dir . '/' . $name)) {
			return false;
		}
		//ustawienie nazwy pliku
		$this->name = $name;
		//typ zasobu
		$this->mimeType = $file->type;
		//klasa zasobu
		$class = explode('/', $file->type);
		$this->class = $class[0];
		//oryginalna nazwa pliku
		$this->original = $file->name;
		//rozmiar pliku
		$this->size = $file->size;
		return true;
	}
	
	/**
	 * Zapis danych do obiektu
	 * @return bool
	 */
	public function save() {
		$res = parent::save();
		//jeśli udało się zapisać rekord
		if ($res) {
			//usunięcie obecnego pliku z dysku
			$this->_unlinkCurrent();
		}
		return $res;
	}
	
	/**
	 * Wstawienie danych (przez save)
	 * @return boolean
	 */
	protected function _insert() {
		//data dodania
		if (!$this->dateAdd) {
			$this->dateAdd = date('Y-m-d H:i:s');
		}
		//właściciel pliku
		if (!$this->cmsAuthId) {
			$this->cmsAuthId = \App\Registry::$auth ? \App\Registry::$auth->getId() : null;
		}
		return parent::_insert();
	}

	/**
	 * Aktualizacja danych (przez save)
	 * @return boolean
	 */
	protected function _update() {
		//data modyfikacji
		$this->dateModify = date('Y-m-d H:i:s');
		return parent::_update();
	}
	
	/**
	 * Usuwa obecny plik, fizycznie z dysku
	 * @return boolean
	 */
	protected function _unlinkCurrent() {
		$name = $this->getOption('currentFile');
		if (empty($name) || strlen($name) < 4) {
			return true;
		}
		//ścieżka do obecnego pliku
		$path = BASE_PATH . '/var/data/' . $name[0] . '/' . $name[1] . '/' . $name[2] . '/' . $name[3] . '/' . $name;
		//usuwa plik
		if (file_exists($path) && is_writable($path)) {
			unlink($path);
		}
		//usuwa miniatury
		$this->_unlink(BASE_PATH . '/web/data/' . $name[0] . '/' . $name[1] . '/' . $name[2] . '/' . $name[3], $name);
		//reset obecnego pliku
		$this->setOption('currentFile', null);
		return true;
	}

	/**
	 * Makes the tumb and return its address
	 *
	 * @param string $inputFile
	 * @param string $outputFile
	 * @param string $scaleType
	 * @param string $scale
	 * @return string
	 */
	protected function _scaler($inputFile, $outputFile, $scaleType, $scale) {
		switch ($scaleType) {
			//skalowanie domyślne
			case 'default':
				$imgRes = \Mmi\Image\Image::inputToResource($inputFile);
				break;
			//skalowanie proporcjonalne do maksymalnego rozmiaru
			case 'scale':
				$v = explode('x', $scale);
				if (count($v) == 1 && is_numeric($v[0]) && intval($v[0]) > 0) {
					$imgRes = \Mmi\Image\Image::scale($inputFile, $v[0]);
				} elseif (count($v) == 2 && is_numeric($v[0]) && intval($v[0]) > 0 && is_numeric($v[1]) && intval($v[1]) > 0) {
					$imgRes = \Mmi\Image\Image::scale($inputFile, $v[0], $v[1]);
				}
				break;
			//skalowanie do maksymalnego X
			case 'scalex':
				$imgRes = \Mmi\Image\Image::scalex($inputFile, intval($scale));
				break;
			//skalowanie do maksymalnego Y
			case 'scaley':
				$imgRes = \Mmi\Image\Image::scaley($inputFile, intval($scale));
				break;
			//skalowanie z obcięciem
			case 'scalecrop':
				$v = explode('x', $scale);
				if (is_numeric($v[0]) && intval($v[0]) > 0 && is_numeric($v[1]) && intval($v[1]) > 0) {
					$imgRes = \Mmi\Image\Image::scaleCrop($inputFile, $v[0], $v[1]);
				}
				break;
		}
		//brak obrazu
		if (!isset($imgRes)) {
			return false;
		}
		//plik istnieje
		if (!file_exists(dirname($outputFile)) && !@mkdir(dirname($outputFile), 0777, true)) {
			return true;
		}
		//określanie typu wyjścia
		switch (\Mmi\FileSystem::mimeType($inputFile)) {
			//GIF
			case 'image/gif':
				imagegif($imgRes, $outputFile);
				return true;
			//PNG
			case 'image/png':
				imagesavealpha($imgRes, true);
				imagepng($imgRes, $outputFile);
				return true;
			//domyślnie jpeg
			default:
				imagejpeg($imgRes, $outputFile, 92);
				return true;
		}
	}

	/**
	 * Usuwa plik, fizycznie i z bazy danych
	 * @return boolean
	 */
	public function delete() {
		//usuwa plik
		if (file_exists($this->getRealPath()) && is_writable($this->getRealPath())) {
			unlink($this->getRealPath());
		}
		//usuwa miniatury
		$this->_unlink(BASE_PATH . '/web/data/' . $this->name[0] . '/' . $this->name[1] . '/' . $this->name[2] . '/' . $this->name[3], $this->name);
		//usuwa rekord
		return parent::delete();
	}

	/**
	 * Usuwa pliki ze ścieżki o danej nazwie
	 * @param string $path ścieżka
	 * @param string $name nazwa pliku
	 */
	protected function _unlink($path, $name) {
		//pętla po wszystkich plikach
		foreach (glob($path . '/*') as $file) {
			if (is_dir($file)) {
				//rekurencyjnie schodzi katalog niżej
				$this->_unlink($file, $name);
				continue;
			}
			//kasowanie pliku jeśli nazwa jest zgodna z wzorcem
			if (basename($file) == $name) {
				unlink($file);
			}
		}
	}

}
