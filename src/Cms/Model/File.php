<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Form\Element\UploaderElementInterface;
use Cms\Orm\CmsFileQuery;
use Cms\Orm\CmsFileRecord;
use Exception;
use Mmi\App\App;
use Mmi\App\KernelException;
use Mmi\Http\RequestFile;
use Mmi\Security\AuthInterface;
use Psr\Log\LoggerInterface;

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
            if ($fileSet instanceof RequestFile) {
                if (null === self::appendFile($fileSet, $object, $id, $allowedTypes)) {
                    return false;
                }
                continue;
            }
            //pliki w polu formularza
            foreach ($fileSet as $file) {
                /* @var $file RequestFile */
                if (null === self::appendFile($file, $object, $id, $allowedTypes)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Dołącza pliki dla danego object i id bezpośrednio z serwera
     * @param RequestFile $file obiekt pliku
     * @param string $object obiekt
     * @param int $id id obiektu
     * @param array $allowedTypes dozwolone typy plików
     * @return CmsFileRecord
     */
    public static function appendFile(RequestFile $file, $object, $id = null, $allowedTypes = [])
    {
        //sprawdzenie i skopiowanie pliku do odpowiedniego katalogu na dysku
        if (null === $name = self::_checkAndCopyFile($file, $allowedTypes)) {
            return null;
        }
        //przypisywanie pól w rekordzie
        $record = self::_updateRecordFromRequestFile($file, new CmsFileRecord());
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
     * @param RequestFile $file obiekt pliku
     * @param array $allowedTypes dozwolone typy plików
     * @return string|null zwraca nazwę utworzonego pliku na dysku
     */
    protected static function _checkAndCopyFile(RequestFile $file, $allowedTypes = [])
    {
        //pomijanie plików typu tiff, svg, heic
        if ($file->type == 'image/tiff' || $file->type == 'image/svg+xml' || $file->type == 'image/heic') {
            $file->type = 'application/octet-stream';
        }
        //plik nie jest dozwolony
        if (!empty($allowedTypes) && !in_array($file->type, $allowedTypes)) {
            return null;
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
        //kopiowanie plików
        foreach (CmsFileQuery::byObject($srcObject, $srcId)->find() as $file) {
            try {
                //tworzenie kopii
                $copy = new RequestFile(
                    [
                        'name' => $file->original,
                        'tmp_name' => $file->getRealPath(),
                        'size' => $file->size
                    ]
                );
            } catch (KernelException $e) {
                App::$di->get(LoggerInterface::class)->warning('Unable to copy file, file not found: ' . $file->getRealPath());
                continue;
            }
            $newFile = clone $file;
            $newFile->id = null;
            //dołączanie pliku
            self::_copyFile($copy, $destObject, $destId, $newFile);
            $i++;
        }
        return $i;
    }

    /**
     * Linkuje plik
     * @param string $srcObject obiekt źródłowy
     * @param int $srcId id źródła
     * @param string $destObject obiekt docelowy
     * @param int $destId docelowy id
     * @return int ilość zlinkowanych
     */
    public static function link($srcObject, $srcId, $destObject, $destId)
    {
        $i = 0;
        //kopiowanie plików (unikalne - zgrupowane po hashu)
        foreach (CmsFileQuery::byObject($srcObject, $srcId)
            ->groupByName()
            ->find() as $file) {
            $file->getHashName();
            $newFile = clone $file;
            $newFile->object = $destObject;
            $newFile->objectId = $destId;
            $newFile->dateAdd = null;
            $newFile->id = null;
            $newFile->save();
            $i++;
        }
        return $i;
    }

    /**
     * Kopiuje plik Cms, zachowując pola oryginalnego rekordu
     * @param RequestFile $file obiekt pliku
     * @param string $object obiekt
     * @param int $id id obiektu
     * @param CmsFileRecord $copy rekord pliku Cms
     * @return CmsFileRecord|null
     */
    protected static function _copyFile(RequestFile $file, $object, $id, CmsFileRecord $copy)
    {
        //sprawdzenie i skopiowanie pliku do odpowiedniego katalogu na dysku
        if (null === $name = self::_checkAndCopyFile($file)) {
            return null;
        }
        $copy->dateModify = date('Y-m-d H:i:s');
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
        try {
            $copy = new RequestFile(
                [
                    'name' => $file->original,
                    'tmp_name' => $file->getRealPath(),
                    'size' => $file->size
                ]
            );
            $newFile = self::appendFile($copy, $file->object, $destId);
        } catch (Exception $e) {
            App::$di->get(LoggerInterface::class)->warning($e->getMessage());
            return;
        }
        $newFile->data = $file->data;
        $newFile->order = $file->order;
        $newFile->active = $file->active;
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
            if (null === ($record = (new CmsFileQuery())->findPk($id))) {
                continue;
            }
            //ustawianie kolejności i zapis
            $record->order = $order;
            $record->save();
        }
    }

    /**
     * Aktualizuje rekord na podstawie pliku z requestu
     * @param RequestFile $file plik z requesta
     * @param CmsFileRecord $record rekord pliku Cms
     * @return CmsFileRecord rekord pliku
     */
    protected static function _updateRecordFromRequestFile(RequestFile $file, CmsFileRecord $record)
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
        if (!$record->dateModify) {
            $record->dateModify = date('Y-m-d H:i:s');
        }
        //właściciel pliku
        $record->cmsAuthId = App::$di->has(AuthInterface::class) ? App::$di->get(AuthInterface::class)->getId() : null;
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
     * @param string[] $ignoreFileNames
     * @return int ilość usuniętych obiektów
     */
    public static function deleteByObject($object = null, $objectId = null, $ignoreFileNames = [])
    {
        //wybieramy kolekcję i usuwamy całą
        return CmsFileQuery::byObject($object, $objectId)
            ->andFieldName()->notEquals($ignoreFileNames)
            ->delete();
    }

    /**
     * Usuwanie nieużywanych plików przypiętych do tmp-XXX
     * @param string $modifiedDate graniczna data modyfikacji
     */
    public static function deleteOrphans($limit = 1000)
    {
        (new CmsFileQuery())->whereObject()->like(UploaderElementInterface::TEMP_OBJECT_PREFIX . '%')
            ->andFieldDateModify()->less(date('Y-m-d H:i:s', strtotime('-1 week')))
            ->limit($limit)
            ->delete();
    }
}
