<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Validator;

use Cms\Form\Element\UploaderElementInterface;
use Cms\Orm\CmsFileQuery;
use Mmi\Validator\ValidatorAbstract;

/**
 * Walidator - minimalna liczba plików w Cms (musi być conajmniej tyle ile podano)
 *
 * @method self setValue($value) ustawia wartość bazową
 * @method self setObject($object) ustawia obiekt
 * @method self setObjectId($objectId) ustawia ID obiektu
 * @method self setClass($class) ustawia klasę plików
 * @method self setActive($active) ustawia aktywność
 * @method self setTemporary($tmp) ustawia, czy pliki tymczasowe
 * @method self setMessage($message) ustawia własną wiadomość walidatora
 *
 * @method string getValue() pobiera wartość bazową
 * @method string getObject() pobiera obiekt
 * @method integer getObjectId() pobiera ID obiektu
 * @method string getClass() pobiera klasę plików
 * @method boolean getActive() pobiera aktywność
 * @method boolean getTemporary() pobiera, czy pliki tymczasowe
 * @method string getMessage() pobiera wiadomość
 */
class MinNumberCmsFiles extends ValidatorAbstract
{
    /**
     * Komunikat błędnego kodu zabezpieczającego
     */
    const INVALID = 'validator.minNumberCmsFiles.message';

    /**
     * Ustawia opcje
     * @param array $options
     * @return self
     */
    public function setOptions(array $options = [], $reset = false)
    {
        return $this->setValue(current($options))
            ->setObject(next($options))
            ->setObjectId(next($options))
            ->setMessage(next($options));
    }

    /**
     * Waliduje czy w cms_file znajduje się wystarczająca liczba plików dla danego rekordu
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $query = (new CmsFileQuery)
            ->byObject($this->_getObjectName(), $this->getObjectId())
            ->andFieldSize()->notEquals(null);
        if ($this->getClass()) {
            $query->andFieldClass()->equals($this->getClass());
        }
        if ($this->getActive() !== null) {
            $query->andFieldActive()->equals($this->getActive());
        }
        if ($query->count() < $this->getValue()) {
            $this->_error([self::INVALID, [$this->getValue()]]);
            return false;
        }
        return true;
    }

    /**
     * Zwraca nazwę obiektu dla zapytania o pliki
     * @return string
     */
    protected function _getObjectName()
    {
        if ($this->getTemporary() === false) {
            return $this->getObject();
        }
        return UploaderElementInterface::TEMP_OBJECT_PREFIX . $this->getObject();
    }
}
