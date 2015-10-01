<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

/**
 * Formularz dodawania i edycji użytkowników CMS
 * @method \Cms\Orm\CmsAuthRecord getRecord()
 */
class Auth extends \Mmi\Form\Form {

	public function init() {

		//nazwa użytkownika
		$this->addElementText('username')
			->setLabel('nazwa użytkownika')
			->setRequired()
			->addFilter('stringTrim')
			->addValidatorNotEmpty()
			->addValidatorRecordUnique(\Cms\Orm\CmsAuthQuery::factory(), 'username', $this->getRecord()->id);

		//imię i nazwisko użytkownika
		$this->addElementText('name')
			->setLabel('pełna nazwa użytkownika (opcjonalna)')
			->addFilter('stringTrim');

		//email
		$this->addElementText('email')
			->setLabel('adres e-mail')
			->setRequired()
			->addFilter('stringTrim')
			->addValidatorEmailAddress()
			->addValidatorRecordUnique(\Cms\Orm\CmsAuthQuery::factory(), 'email', $this->getRecord()->id);

		//role
		$this->addElementMultiCheckbox('cmsRoles')
			->setLabel('role')
			->setDescription('Grupa uprawnień')
			->setMultiOptions(\Cms\Orm\CmsRoleQuery::factory()->findPairs('id', 'name'))
			->setValue(\Cms\Orm\CmsAuthRoleQuery::byAuthId($this->_record->id)->findPairs('cms_role_id', 'cms_role_id'));

		$languages = [];
		foreach (\App\Registry::$config->languages as $language) {
			$languages[$language] = $language;
		}

		if (!empty($languages)) {
			$this->addElementSelect('lang')
				->setLabel('język')
				->setMultiOptions($languages)
				->setDescription('Preferowany przez użytkownika język interfejsu');
		}

		//aktywny
		$this->addElementCheckbox('active')
			->setLabel('Aktywny');

		//zmiana hasła
		$this->addElementText('changePassword')
			->setLabel('zmiana hasła')
			->setDescription('Jeśli nie chcesz zmienić hasła nie wypełniaj tego pola')
			->addValidatorStringLength(4, 128);

		$this->addElementSubmit('submit')
			->setLabel('Zapisz');
	}

	/**
	 * Przed zapisem - kalkulacja hasha hasła
	 * @return boolean
	 */
	public function beforeSave() {
		if (null !== $password = $this->getElement('changePassword')->getValue()) {
			$this->getRecord()->password = \Cms\Model\Auth::getSaltedPasswordHash($password);
		}
		return true;
	}

	/**
	 * Po zapisie nadawanie uprawnień
	 * @return boolean
	 */
	public function afterSave() {
		//nadawanie uprawnień
		\Cms\Model\Role::grant($this->getRecord()->id, $this->getElement('cmsRoles')->getValue());
		return true;
	}

}
