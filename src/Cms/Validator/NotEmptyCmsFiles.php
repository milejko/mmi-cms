<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Validator;

/**
 * Walidator - niepusta lista plików w Cms (musi być conajmniej jeden)
 * 
 * @method self setObject($object) ustawia obiekt
 * @method self setObjectId($objectId) ustawia ID obiektu
 * @method self setClass($class) ustawia klasę plików
 * @method self setActive($active) ustawia aktywność
 * @method self setRecord($record) ustawia rekord z formularza
 * @method self setMessage($message) ustawia własną wiadomość walidatora
 * 
 * @method string getObject() pobiera obiekt
 * @method integer getObjectId() pobiera ID obiektu
 * @method string getClass() pobiera klasę plików
 * @method boolean getActive() pobiera aktywność
 * @method \Mmi\Orm\Record getRecord() pobiera rekord
 * @method string getMessage() pobiera wiadomość 
 */
class NotEmptyCmsFiles extends \Mmi\Validator\ValidatorAbstract
{

    /**
     * Komunikat błędnego kodu zabezpieczającego
     */
    const INVALID = 'validator.notEmptyCmsFiles.message';

    /**
     * Ustawia opcje
     * @param array $options
     * @return self
     */
    public function setOptions(array $options = [], $reset = false)
    {
        return $this->setObject(current($options))
            ->setObjectId(next($options))
            ->setMessage(next($options));
    }

    /**
     * Waliduje czy w cms_file znajdują się jakieś pliki dla danego rekordu
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setFromRecord();
        $query = (new \Cms\Orm\CmsFileQuery)
            ->byObject($this->getObject(), $this->getObjectId());
        if ($this->getClass()) {
            $query->andFieldClass()->equals($this->getClass());
        }
        if ($this->getActive() !== null) {
            $query->andFieldActive()->equals($this->getActive());
        }
        if (!$query->count()) {
            $this->_error(self::INVALID);
            return false;
        }
        return true;
    }

    /**
     * Ustawia object i objectId na podstawie przekazanego obiektu rekordu
     * @return \Cms\Validator\NotEmptyCmsFiles
     */
    protected function _setFromRecord()
    {
        //jeśli przekazano rekord z formularza
        if ($this->getRecord()) {
            //w zależności od stanu zapisu, ustawiamy object i objectId
            if ($this->getRecord()->getPk()) {
                $this->setObjectId($this->getRecord()->getPk());
            } else {
                $this->setObjectId(\Mmi\Session\Session::getNumericId());
                $this->setObject('tmp-' . $this->getObject());
            }
        }
        return $this;
    }

}
