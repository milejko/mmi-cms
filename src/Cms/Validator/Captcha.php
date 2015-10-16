<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Validator;

/**
 * Walidator dla elementu captcha
 * @see \Cms\Form\Element\Captcha
 * 
 * @method self setName($name) ustawia nazwę
 * @method string getName() pobiera nazwę
 */
class Captcha extends \Mmi\Validator\ValidatorAbstract {

	/**
	 * Komunikat błędnego kodu zabezpieczającego
	 */
	const INVALID = 'Przepisany kod jest niepoprawny';
	
	/**
	 * Waliduje poprawność captcha
	 * @param string $value
	 * @return boolean
	 */
	public function isValid($value) {
		$this->_error(self::INVALID);
		$name = 'captcha-' . $this->getName();
		return ((new \Mmi\Session\SessionSpace('CmsForm'))->$name == strtoupper($value));
	}

}
