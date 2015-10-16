<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Orm\CmsAuthQuery;

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
			->addFilterStringTrim()
			->addValidatorRecordUnique(new CmsAuthQuery, 'username', $this->getRecord()->id);

		//imię i nazwisko użytkownika
		$this->addElementText('name')
			->setLabel('pełna nazwa użytkownika (opcjonalna)')
			->addFilterStringTrim();

		//email
		$this->addElementText('email')
			->setLabel('adres e-mail')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorEmailAddress()
			->addValidatorRecordUnique(new CmsAuthQuery, 'email', $this->getRecord()->id);

		//role
		$this->addElementMultiCheckbox('cmsRoles')
			->setLabel('role')
			->setDescription('Grupa uprawnień')
			->setMultioptions((new \Cms\Orm\CmsRoleQuery)->findPairs('id', 'name'))
			->setValue(\Cms\Orm\CmsAuthRoleQuery::byAuthId($this->_record->id)->findPairs('cms_role_id', 'cms_role_id'));

		$languages = [];
		foreach (\App\Registry::$config->languages as $language) {
			$languages[$language] = $language;
		}

		if (!empty($languages)) {
			$this->addElementSelect('lang')
				->setLabel('język')
				->setMultioptions($languages)
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
		if ('' !== $password = $this->getElement('changePassword')->getValue()) {
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
