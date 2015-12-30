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
 * Walidator - niepusta lista plików w Cms (musi być conajmniej jeden)
 * 
 * @method self setObject($object) ustawia obiekt
 * @method self setObjectId($objectId) ustawia ID obiektu
 * @method self setMessage($message) ustawia własną wiadomość walidatora
 * 
 * @method string getObject() pobiera obiekt
 * @method integer getObjectId() pobiera ID obiektu
 * @method string getMessage() pobiera wiadomość 
 */
class NotEmptyCmsFiles extends \Mmi\Validator\ValidatorAbstract {

	/**
	 * Komunikat błędnego kodu zabezpieczającego
	 */
	const INVALID = 'Proszę przesłać pliki';

	/**
	 * Ustawia opcje
	 * @param array $options
	 * @return self
	 */
	public function setOptions(array $options = [], $reset = false) {
		return $this->setObject(current($options))
				->setObjectId(next($options))
				->setMessage(next($options));
	}

	/**
	 * Waliduje czy w cms_file znajdują się jakieś pliki dla danego rekordu
	 * @param string $value
	 * @return boolean
	 */
	public function isValid($value) {
		$query = (new \Cms\Orm\CmsFileQuery)
			->byObject($this->getObject(), $this->getObjectId());
		if ($this->getClass()) {
			$query->andFieldClass()->equals($this->getClass());
		}
		if (!$query->count()) {
				$this->_error(self::INVALID);
				return false;
		}
		return true;
	}

}
