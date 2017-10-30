<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsFileQuery;
use Cms\Orm\CmsFileRecord;

/**
 * Model pliku
 */
class File
{

    /**
     * Dołącza pliki dla danego object i id
     * @param string $object obiekt
     * @param integer $id obiektu
     * @param array $files tabela plików
     * @param array $allowedTypes
     * @return boolean
     */
    public static function appendFiles($object, $id = null, array $files = [], array $allowedTypes = [])
    {
        //pola formularza
        foreach ($files as $fileSet) {
            //pojedynczy upload
            if ($fileSet instanceof \Mmi\Http\RequestFile) {
                if (null === self::appendFile($fileSet, $object, $id, $allowedTypes)) {
                    return false;
                }
                continue;
            }
            //pliki w polu formularza
            foreach ($fileSet as $file) {
                /* @var $file \Mmi\Http\RequestFile */
                if (null === self::appendFile($file, $object, $id, $allowedTypes)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Dołącza pliki dla danego object i id bezpośrednio z serwera
     * @param \Mmi\Http\RequestFile $file obiekt pliku
     * @param string $object obiekt
     * @param int $id id obiektu
     * @param array $allowedTypes dozwolone typy plików
     * @return \Cms\Orm\CmsFileRecord
     */
    public static function appendFile(\Mmi\Http\RequestFile $file, $object, $id = null, $allowedTypes = [])
    {
        //sprawdzenie i skopiowanie pliku do odpowiedniego katalogu na dysku
        if (null === $name = self::_checkAndCopyFile($file, $allowedTypes)) {
            return null;
        }
        //przypisywanie pól w rekordzie
        $record = self::_updateRecordFromRequestFile($file, new \Cms\Orm\CmsFileRecord());
        //zapis nazwy pliku
        $record->name = $name;
        //obiekt i id
        $record->object = $object;
        $record->objectId = $id;
        //zapis rekordu
        return ($record->save()) ? $record : null;
    }
    
    /**
     * Sprawdza parametry i kopiuje plik do odpowiedniego katalogu na dysku
     * @param \Mmi\Http\RequestFile $file obiekt pliku
     * @param array $allowedTypes dozwolone typy plików
     * @return string|null zwraca nazwę utworzonego pliku na dysku
     */
    protected static function _checkAndCopyFile(\Mmi\Http\RequestFile $file, $allowedTypes = [])
    {
        //pomijanie plików typu bmp (bitmapy windows - nieobsługiwane w PHP)
        if ($file->type == 'image/x-ms-bmp' || $file->type == 'image/tiff') {
            $file->type = 'application/octet-stream';
        }

        //plik nie jest dozwolony
        if (!empty($allowedTypes) && !in_array($file->type, $allowedTypes)) {
            return null;
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
        copy($file->tmpName, $dir . '/' . $name);
        return $name;
    }

    /**
     * Przenosi plik z jednego obiektu na inny
     * @param string $srcObject obiekt źródłowy
     * @param int $srcId id źródła
     * @param string $destObject obiekt docelowy
     * @param int $destId docelowy id
     * @return int ilość przeniesionych
     */
    public static function move($srcObject, $srcId, $destObject, $destId)
    {
        $i = 0;
        //przenoszenie plików
        foreach (CmsFileQuery::byObject($srcObject, $srcId)->find() as $file) {
            //nowy obiekt i id
            $file->object = $destObject;
            $file->objectId = $destId;
            //zapis
            $file->save();
            $i++;
        }
        return $i;
    }

    /**
     * Kopiuje plik z jednego obiektu na inny
     * @param string $srcObject obiekt źródłowy
     * @param int $srcId id źródła
     * @param string $destObject obiekt docelowy
     * @param int $destId docelowy id
     * @return int ilość skopiowanych
     */
    public static function copy($srcObject, $srcId, $destObject, $destId)
    {
        $i = 0;
        //przenoszenie plików
        foreach (CmsFileQuery::byObject($srcObject, $srcId)->find() as $file) {
            //tworzenie kopii
            $copy = new \Mmi\Http\RequestFile([
                'name' => $file->original,
                'tmp_name' => $file->getRealPath(),
                'size' => $file->size
            ]);
            $newFile = clone $file;
            $newFile->id = null;
            //dołączanie pliku
            self::_copyFile($copy, $destObject, $destId, $newFile);
            $i++;
        }
        return $i;
    }
    
    /**
     * Kopiuje plik Cms, zachowując pola oryginalnego rekordu
     * @param \Mmi\Http\RequestFile $file obiekt pliku
     * @param string $object obiekt
     * @param int $id id obiektu
     * @param \Cms\Orm\CmsFileRecord $copy rekord pliku Cms
     * @return \Cms\Orm\CmsFileRecord|null
     */
    protected static function _copyFile(\Mmi\Http\RequestFile $file, $object, $id, \Cms\Orm\CmsFileRecord $copy)
    {
        //sprawdzenie i skopiowanie pliku do odpowiedniego katalogu na dysku
        if (null === $name = self::_checkAndCopyFile($file)) {
            return null;
        }
        //przypisywanie pól w rekordzie
        $record = self::_updateRecordFromRequestFile($file, $copy);
        //zapis nazwy pliku
        $record->name = $name;
        //obiekt i id
        $record->object = $object;
        $record->objectId = $id;
        //zapis rekordu
        return ($record->save()) ? $record : null;
    }

    /**
     * @param CmsFileRecord $file
     * @param $destId
     * @return CmsFileRecord|null
     */
    public static function copyWithData(CmsFileRecord $file, $destId)
    {
        $copy = new \Mmi\Http\RequestFile([
            'name' => $file->original,
            'tmp_name' => $file->getRealPath(),
            'size' => $file->size
        ]);
        $newFile = self::appendFile($copy, $file->object, $destId);
        $newFile->data = $file->data;
        $newFile->sticky = $file->sticky;
        $newFile->order = $file->order;
        return ($newFile->save()) ? $newFile : null;
    }

    /**
     * Sortuje po zserializowanej tabeli identyfikatorów
     * @param array $serial tabela identyfikatorów
     */
    public static function sortBySerial(array $serial = [])
    {
        foreach ($serial as $order => $id) {
            //brak rekordu o danym ID
            if (null === ($record = (new CmsFileQuery)->findPk($id))) {
                continue;
            }
            //ustawianie kolejności i zapis
            $record->order = $order;
            $record->save();
        }
    }

    /**
     * Aktualizuje rekord na podstawie pliku z requestu
     * @param \Mmi\Http\RequestFile $file plik z requesta
     * @param \Cms\Orm\CmsFileRecord $record rekord pliku Cms
     * @return CmsFileRecord rekord pliku
     */
    protected static function _updateRecordFromRequestFile(\Mmi\Http\RequestFile $file, \Cms\Orm\CmsFileRecord $record)
    {
        //typ zasobu
        $record->mimeType = $file->type;
        //klasa zasobu
        $class = explode('/', $file->type);
        $record->class = $class[0];
        //oryginalna nazwa pliku
        $record->original = $file->name;
        //rozmiar pliku
        $record->size = $file->size;
        //daty dodania i modyfikacji
        if (!$record->dateAdd) {
            $record->dateAdd = date('Y-m-d H:i:s');
        }
        $record->dateModify = date('Y-m-d H:i:s');
        //właściciel pliku
        $record->cmsAuthId = \App\Registry::$auth ? \App\Registry::$auth->getId() : null;
        if ($record->active === null) {
            //domyślnie aktywny
            $record->active = 1;
        }
        return $record;
    }

    /**
     * Usuwa kolekcję rekordów po obiekcie i id
     * @param string $object
     * @param string $objectId
     * @return int ilość usuniętych obiektów
     */
    public static function deleteByObject($object = null, $objectId = null)
    {
        //wybieramy kolekcję i usuwamy całą
        return CmsFileQuery::byObject($object, $objectId)
            ->find()
            ->delete();
    }

    /**
     * Usuwanie nieużywanych plików przypiętych do tmp-XXX
     */
    public static function deleteOrphans()
    {
        (new CmsFileQuery)->whereObject()->like('tmp-%')
            ->andFieldDateAdd()->less(date('Y-m-d H:i:s', strtotime('-1 week')))
            ->find()
            ->delete();
    }

}
