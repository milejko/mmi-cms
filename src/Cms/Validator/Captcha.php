<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Validator;

use Mmi\Session\SessionSpace;
use Mmi\Validator\ValidatorAbstract;

/**
 * Walidator dla elementu captcha
 * @see \Cms\Form\Element\Captcha
 *
 * @method self setName($name) ustawia nazwę
 * @method string getName() pobiera nazwę
 */
class Captcha extends ValidatorAbstract
{
    /**
     * Komunikat błędnego kodu zabezpieczającego
     */
    const INVALID = 'validator.captcha.message';

    /**
     * Ustawia opcje
     * @param array $options
     * @param bool $reset
     * @return self
     */
    public function setOptions(array $options = [], $reset = false)
    {
        return $this->setName(current($options));
    }

    /**
     * Waliduje poprawność captcha
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        //brak wartości
        if (!$value) {
            return $this->_error(self::INVALID);
        }
        //wartości niezgodne
        if ((new SessionSpace('captcha'))->{$this->getOption('name')} !== strtoupper($value)) {
            return $this->_error(self::INVALID);
        }
        return true;
    }
}
