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
 * Walidator - maksymalna liczba kafelków (musi być co najwyżej tyle ile podano)
 *
 * @see \Cms\Form\Element\MultiField
 *
 * @method self setNumber($number) ustawia liczbę
 *
 * @method int getNumber() pobiera liczbę
 */
class MaxNumberMultifieldTiles extends \Mmi\Validator\ValidatorAbstract
{
    /**
     * Komunikat błędnej liczby kafelków
     */
    const INVALID = 'validator.maxNumberMultifieldTiles.message';

    /**
     * Ustawia opcje
     *
     * @param array $options
     *
     * @return self
     */
    public function setOptions(array $options = [], $reset = false)
    {
        return $this
            ->setNumber(current($options))
            ->setMessage(next($options));
    }

    /**
     * Waliduje czy w cms_file znajduje się dopuszczalna liczba plików dla danego rekordu
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
        if (count($value) > $this->getNumber()) {
            $this->_error(self::INVALID);
            return false;
        }

        return true;
    }
}
