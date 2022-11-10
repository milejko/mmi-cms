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
 * Walidator dla elementu formularza antirobot
 *
 * @see \Cms\Form\Element\Antirobot
 */
class Antirobot extends \Mmi\Validator\ValidatorAbstract
{
    /**
     * Komunikat błędnego kodu zabezpieczającego
     */
    public const INVALID = 'validator.antirobot.message';

    /**
     * Waliduje poprawność antirobot
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (('js-' . self::generateCrc() . '-js') == $value) {
            return true;
        }
        return $this->_error(self::INVALID);
    }

    /**
     * Generowanie unikalnego CRC na dany dzień
     * @return integer
     */
    public static function generateCrc()
    {
        return crc32(date('Y-m-d') . 'antirobot-crc');
    }
}
