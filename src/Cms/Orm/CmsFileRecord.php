<?php

namespace Cms\Orm;

use Mmi\App\App;
use Mmi\DataObject;
use Mmi\Mvc\View;
use Mmi\Security\AuthInterface;

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
        return substr(md5($this->name . App::$di->get('app.view.cdn')), 0, 8);
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
     * @return string adres publiczny pliku
     */
    public function getUrl($scaleType = 'default', $scale = null)
    {
        //brak pliku
        if ($this->id === null) {
            return;
        }
        //ścieżka CDN
        $cdnPath = rtrim(App::$di->get(View::class)->cdn ? App::$di->get(View::class)->cdn : App::$di->get(View::class)->url([], true), '/');
        //pobranie ścieżki z systemu plików
        return $cdnPath . (new \Cms\Model\FileSystemModel($this->name))->getPublicPath($scaleType, $scale);
    }

    /**
     * Pobiera adres postera pliku
     * @param string $scaleType scale, scalex, scaley, scalecrop
     * @param int|string $scale 320, 320x240
     * @return string adres publiczny postera pliku
     */
    public function getPosterUrl($scaleType = 'default', $scale = null)
    {
        //brak pliku
        if (null === $this->id || !$this->data->posterFileName) {
            return;
        }
        //ścieżka CDN
        $cdnPath = rtrim(App::$di->get(View::class)->cdn ? App::$di->get(View::class)->cdn : App::$di->get(View::class)->url([], true), '/');
        //pobranie ścieżki z systemu plików
        return $cdnPath . (new \Cms\Model\FileSystemModel($this->data->posterFileName))->getPublicPath($scaleType, $scale);
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
        //pozycja ostatniej kropki w nazwie - rozszerzenie pliku
        $pointPosition = strrpos($file->name, '.');
        //kalkulacja nazwy systemowej
        $name = md5(microtime(true) . $file->tmpName) . (($pointPosition !== false) ? substr($file->name, $pointPosition) : '');
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
        $this->data = (new DataObject)->setParams($data);
        return $this;
    }

    /**
     * Zapis danych do obiektu
     * @return bool
     */
    public function save()
    {
        //iteracja po polach
        foreach (($data = ($this->data instanceof DataObject) ? $this->data->toArray() : []) as $field => $value) {
            //usuwanie pustych pól
            if ($value === null) {
                unset($data[$field]);
            }
        }
        //zapis json'a
        $this->data = empty($data) ? null : json_encode($data);
        return parent::save();
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
            $this->cmsAuthId = App::$di->get(AuthInterface::class) ? App::$di->get(AuthInterface::class)->getId() : null;
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
     * Usuwa plik, fizycznie i z bazy danych
     * @return boolean
     */
    public function delete()
    {
        //usuwanie meta
        if (!parent::delete()) {
            return false;
        }
        //plik jest ciągle potrzebny (ma linki)
        if (0 != (new CmsFileQuery)->whereName()->equals($this->name)->count()) {
            return true;
        }
        //kasowanie z systemu plików
        (new \Cms\Model\FileSystemModel($this->name))->unlink();
        //usuwanie rekordu
        return true;
    }

}
