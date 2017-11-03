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
 * Łączy inteligentnie worek plików tymczasowych z edycji z wersjami oryginalnymi.
 *
 * @author b.wolos
 */
class FileMerge
{
    /**
     * Typ obiektu źródłowego
     * @var string
     */
    protected $_sourceObject;
    
    /**
     * Id obiektu źródłowego
     * @var int
     */
    protected $_sourceId;
    
    /**
     * Typ obiektu docelowego
     * @var string
     */
    protected $_destinationObject;
    
    /**
     * Id obiektu docelowego
     * @var int
     */
    protected $_destinationId;
    
    /**
     * Idki aktualnych plików - te pliki mają zostać
     * @var array
     */
    protected $_currentIds = [];
    
    /**
     * Konstruktor
     * @param string $srcObject obiekt źródłowy
     * @param int $srcId id źródła
     * @param string $destObject obiekt docelowy
     * @param int $destId docelowy id
     */
    public function __construct($srcObject, $srcId, $destObject, $destId)
    {
        $this->_sourceObject = $srcObject;
        $this->_sourceId = $srcId;
        $this->_destinationObject = $destObject;
        $this->_destinationId = $destId;
    }
    
    /**
     * Łączy pliki źródłowe z docelowymi
     * @return boolean
     */
    public function merge()
    {
        //dla każdego pliku tymczasowego
        foreach ((new CmsFileQuery)->byObject($this->_sourceObject, $this->_sourceId)
            ->find() as $tmpFile) {
            //jeśli nie ma id oryginału, to znaczy, że nowy plik lub usunięto oryginał
            if ($tmpFile->cmsFileOriginalId === null ||
                null === ($originalFile = (new CmsFileQuery)->findPk($tmpFile->cmsFileOriginalId))) {
                //przeniesienie pliku tmp pod obiekt docelowy
                $this->_moveToDestination($tmpFile);
                continue;
            }
            //jeśli przesłano nową zawartość pliku - nadpisano
            if ($tmpFile->newUploaded) {
                //usunięcie oryginału pliku
                $originalFile->delete();
                //przeniesienie pliku tmp pod obiekt docelowy
                $this->_moveToDestination($tmpFile);
                continue;
            }
            //jeśli edytowano rekord - zmieniono parametry
            if ($tmpFile->dateModify !== $originalFile->dateModify) {
                //synchronizujemy dane z rekordów
                $this->_synchronizeData($tmpFile, $originalFile);
            }
            array_push($this->_currentIds, $tmpFile->cmsFileOriginalId);
            //usuwamy plik tymczasowy
            $tmpFile->delete();
        }
        //usunięcie z orginałów plików skasowanych z listy tymczasowej
        $this->_deleteOriginals();
        return true;
    }
    
    /**
     * Przenosi plik do obiektu docelowego
     * @param CmsFileRecord $record
     * @return boolean
     */
    protected function _moveToDestination(\Cms\Orm\CmsFileRecord $record)
    {
        array_push($this->_currentIds, $record->id);
        $record->object = $this->_destinationObject;
        $record->objectId = $this->_destinationId;
        $record->newUploaded = 0;
        $record->cmsFileOriginalId = null;
        return $record->save();
    }
    
    /**
     * Synchronizuje dane opisujące zasoby w rekordach
     * @param CmsFileRecord $tmp
     * @param CmsFileRecord $original
     * @return boolean
     */
    protected function _synchronizeData(\Cms\Orm\CmsFileRecord $tmp, \Cms\Orm\CmsFileRecord $original)
    {
        $original->active = $tmp->active;
        $original->cmsAuthId = $tmp->cmsAuthId;
        $original->data = $tmp->data;
        $original->dateModify = $tmp->dateModify;
        $original->order = $tmp->order;
        $original->original = $tmp->original;
        $original->sticky = $tmp->sticky;
        return $original->save();
    }
    
    /**
     * Usuwa zbędne oryginały - odpowiadające usuniętym plikom tymczasowym
     * @return integer ilość usuniętych obiektów
     */
    protected function _deleteOriginals()
    {
        $query = (new CmsFileQuery)->byObject($this->_destinationObject, $this->_destinationId);
        if ($this->_currentIds) {
            $query->andFieldId()->notEquals($this->_currentIds);
        }
        return $query->find()
            ->delete();
    }
    
}
