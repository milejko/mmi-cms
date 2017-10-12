<?php

namespace Cms\Orm;

use Mmi\App\FrontController;

/**
 * Rekord pliku
 */
class CmsFileRecord extends \Mmi\Orm\Record
{

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

    /**
     * Json z danymi opisowymi
     * @var string
     */
    public $data;

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
    public function setSticky()
    {
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
    public function getHashName()
    {
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
    public function getRealPath()
    {
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
    public function getBinaryContent()
    {
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
     * @param int|string $scale 320, 320x240
     * @param boolean $https null - bez zmian, true - tak, false - nie
     * @return string adres publiczny pliku
     */
    public function getUrl($scaleType = 'default', $scale = 0, $https = null)
    {
        //brak pliku
        if ($this->id === null || strlen($this->name) < 4) {
            return;
        }
        //plik źródłowy
        $inputFile = $this->getRealPath();
        $fileName = '/' . $this->name[0] . '/' . $this->name[1] . '/' . $this->name[2] . '/' . $this->name[3] . '/' . $scaleType . '/' . $scale . '/' . $this->name;
        //obliczanie publicznego linku
        $publicUrl = rtrim(\Mmi\App\FrontController::getInstance()->getView()->cdn ? \Mmi\App\FrontController::getInstance()->getView()->cdn : \Mmi\App\FrontController::getInstance()->getView()->url([], true, $https), '/') . '/data' . $fileName;
        //istnieje plik - wiadomość z bufora
        if (true === FrontController::getInstance()->getLocalCache()->load($cacheKey = 'cms-file-' . md5($fileName))) {
            return $publicUrl;
        }
        //brak pliku źródłowego
        if (!file_exists($inputFile)) {
            FrontController::getInstance()->getLogger()->warning('CMS file not found: ' . $this->id . ' - ' . $this->original);
            return;
        }
        //istnieje plik - zwrot ścieżki publicznej
        if (file_exists($thumbPath = BASE_PATH . '/web/data' . $fileName) && filemtime($thumbPath) > filemtime($inputFile)) {
            FrontController::getInstance()->getLocalCache()->save(true, $cacheKey);
            return $publicUrl;
        }
        //próba tworzenia katalogów
        try {
            mkdir(dirname($thumbPath), 0777, true);
        } catch (\Mmi\App\KernelException $e) {
            //nic
        }
        //wybrano skalowanie dla klasy obrazu
        if ($this->class == 'image' && $scaleType != 'default') {
            //uruchomienie skalera
            $this->_scaler($inputFile, $thumbPath, $scaleType, $scale);
            return $publicUrl;
        }
        try {
            copy($inputFile, $thumbPath);
        } catch (\Exception $e) {
            FrontController::getInstance()->getLogger()->warning('Unable to copy CMS file to web: ' . $this->id);
            return;
        }
        //zwrot ścieżki publicznej
        return $publicUrl;
    }

    /**
     * Zapisuje plik przesłany na serwer i aktualizuje pola w rekordzie
     * Uwaga! Metoda nie zapisuje zmian w rekordzie (nie wywołuje save)!
     * @param \Mmi\Http\RequestFile $file obiekt pliku
     * @param array $allowedTypes dozwolone typy plików
     * @return boolean
     */
    public function replaceFile(\Mmi\Http\RequestFile $file, $allowedTypes = [])
    {
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
     * Wczytanie rekordu
     * @param array $row
     * @return \Cms\Orm\CmsFileRecord
     */
    public function setFromArray(array $row = [])
    {
        parent::setFromArray($row);
        try {
            $data = $this->data ? json_decode($this->data, true) : [];
        } catch (\Exception $e) {
            $data = [];
        }
        $this->data = (new \Mmi\DataObject)->setParams($data);
        return $this;
    }

    /**
     * Zapis danych do obiektu
     * @return bool
     */
    public function save()
    {
        //iteracja po polach
        foreach (($data = ($this->data instanceof \Mmi\DataObject) ? $this->data->toArray() : []) as $field => $value) {
            //usuwanie pustych pól
            if ($value === null) {
                unset($data[$field]);
            }
        }
        //zapis json'a
        $this->data = empty($data) ? null : json_encode($data);
        $result = parent::save();
        //jeśli udało się zapisać rekord
        if ($result) {
            //usunięcie obecnego pliku z dysku
            $this->_unlinkCurrent();
        }
        return $result;
    }

    /**
     * Wstawienie danych (przez save)
     * @return boolean
     */
    protected function _insert()
    {
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
    protected function _update()
    {
        //data modyfikacji
        $this->dateModify = date('Y-m-d H:i:s');
        return parent::_update();
    }

    /**
     * Usuwa obecny plik, fizycznie z dysku
     * @return boolean
     */
    protected function _unlinkCurrent()
    {
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
     * Makes the thumb and return its address
     *
     * @param string $inputFile
     * @param string $outputFile
     * @param string $scaleType
     * @param string $scale
     * @return string
     */
    protected function _scaler($inputFile, $outputFile, $scaleType, $scale)
    {
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
            FrontController::getInstance()->getLogger()->warning('Unable to resize CMS file: ' . $outputFile);
            return;
        }
        //plik istnieje
        if (!file_exists(dirname($outputFile))) {
            try {
                mkdir(dirname($outputFile), 0777, true);
            } catch (\Mmi\App\KernelException $e) {
                FrontController::getInstance()->getLogger()->warning('Unable to create directories: ' . $e->getMessage());
                return;
            }
        }
        //określanie typu wyjścia
        $mimeType = \Mmi\FileSystem::mimeType($inputFile);
        //GIF
        if ($mimeType == 'image/gif') {
            imagealphablending($imgRes, false);
            imagesavealpha($imgRes, true);
            imagegif($imgRes, $outputFile);
            return;
        }
        //PNG (nieprzeźroczysty)
        if ($mimeType == 'image/png') {
            imagealphablending($imgRes, false);
            imagesavealpha($imgRes, true);
            imagepng($imgRes, $outputFile, 9);
            return;
        }
        //domyślnie JPEG
        imagejpeg($imgRes, $outputFile, intval(\App\Registry::$config->thumbQuality));
    }

    /**
     * Usuwa plik, fizycznie i z bazy danych
     * @return boolean
     */
    public function delete()
    {
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
    protected function _unlink($path, $name)
    {
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
