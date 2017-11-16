<?php

namespace Cms\Orm;

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
     * Id oryginalnego pliku, z którego powstała kopia
     * @var integer
     */
    public $cmsFileOriginalId;
    
    /**
     * Czy nowo przesłany
     * @var boolean
     */
    public $newUploaded;

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
        //pobranie realnej ścieżki
        return (new \Cms\Model\FileSystemModel($this->name))->getRealPath();
    }

    /**
     * Zwraca binarną zawartość pliku
     * @return mixed
     */
    public function getBinaryContent()
    {
        //pobranie pliku
        $content = file_get_contents($this->getRealPath());
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
        if ($this->id === null) {
            return;
        }
        //ścieżka CDN
        $cdnPath = rtrim(\Mmi\App\FrontController::getInstance()->getView()->cdn ? \Mmi\App\FrontController::getInstance()->getView()->cdn : \Mmi\App\FrontController::getInstance()->getView()->url([], true, $https), '/');
        //pobranie ścieżki z systemu plików
        return $cdnPath . (new \Cms\Model\FileSystemModel($this->name))->getPublicPath($scaleType, $scale, $https);
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
        (new \Cms\Model\FileSystemModel($name))->unlink();
        return true;
    }

    /**
     * Usuwa plik, fizycznie i z bazy danych
     * @return boolean
     */
    public function delete()
    {
        //kasowanie z systemu plików
        (new \Cms\Model\FileSystemModel($this->name))->unlink();
        //usuwanie rekordu
        return parent::delete();
    }
    
    /**
     * Pobranie posteru dla video
     * @return boolean
     */
    public function getVideoPoster()
    {
        if (!isset($this->data->poster)) {
            return null;
        }

        if (null === $poster = (new CmsFileQuery)->whereObject()->equals($this->data->poster)
            ->andFieldActive()->equals(1)
            ->findFirst()) {
            return null;
        }

        return $poster;
    }

}
