<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Validator;

use Mmi\Validator\ValidatorAbstract;

/**
 * Walidator - minimalna liczba kafelków (musi być co najmniej tyle ile podano)
 *
 * @see \Cms\Form\Element\MultiField
 *
 * @method self setNumber($number) ustawia liczbę
 *
 * @method int getNumber() pobiera liczbę
 */
class MinNumberMultifieldTiles extends ValidatorAbstract
{
    /**
     * Komunikat błędnej liczby kafelków
     */
    public const INVALID = 'validator.minNumberMultifieldTiles.message';

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
     * Waliduje czy w cms_file znajduje się wystarczająca liczba plików dla danego rekordu
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
        if (false === is_array($value) || count($value) < $this->getNumber()) {
            $this->_error([self::INVALID, [$this->getNumber()]]);

            return false;
        }

        return true;
    }
}
