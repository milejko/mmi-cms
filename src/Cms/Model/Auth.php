<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use \Cms\Orm;

/**
 * Klasa autoryzacji
 */
class Auth implements \Mmi\Auth\AuthInterface {

	/**
	 * Autoryzacja do CMS
	 * @param string $identity
	 * @param string $credential
	 * @return \Mmi\Auth\Record
	 */
	public static function authenticate($identity, $credential) {
		//błędna autoryzacja (brak aktywnego użytkownika)
		if (null === $record = self::_findUserByIdentity($identity)) {
			return;
		}

		//próby logowania lokalnie i ldap
		if (!self::_localAuthenticate($record, $credential) && !self::_ldapAuthenticate($record, $credential)) {
			self::_updateUserFailedLogin($record);
			return self::_authFailed($identity, 'Invalid password.');
		}

		//poprawna autoryzacja
		$record->setOption('roles', Orm\Auth\Role\Query::joinedRolebyAuthId($record->id)->findPairs('cms_role_id', 'name'));
		return self::_authSuccess($record);
	}

	/**
	 * Autoryzacja po ID
	 * @param integer $id
	 * @return boolean
	 */
	public static function idAuthenticate($id) {
		//wyszukiwanie aktywnego użytkownika
		if (null === $record = self::_findUserByIdentity($id)) {
			return;
		}
		$record->setOption('roles', Orm\Auth\Role\Query::joinedRolebyAuthId($record->id)->findPairs('cms_role_id', 'name'));
		return self::_authSuccess($record);
	}

	/**
	 * Wylogowanie
	 */
	public static function deauthenticate() {
		//logowanie deautoryzacji
		\Cms\Model\Log::add('logout', [
			'object' => 'cms_auth',
			'objectId' => \App\Registry::$auth->getId(),
			'success' => true,
			'message' => 'LOGGED OUT: ' . \App\Registry::$auth->getUsername()
		]);
	}

	/**
	 * Zwraca hash hasła zakodowany z "solą"
	 * @param string $password
	 * @return string
	 */
	public static function getSaltedPasswordHash($password) {
		return hash('sha512', \App\Registry::$config->salt . md5($password) . $password . 'sltd');
	}

	/**
	 * Obsługa błędnego logowania znanego użytkownika
	 * @param string $identity
	 */
	protected static function _authFailed($identity, $reason = '') {
		//logowanie błędnej próby autoryzacji
		\Cms\Model\Log::add('login failed', [
			'success' => false,
			'message' => 'LOGIN FAILED: ' . $identity . ' ' . $reason]);
	}

	/**
	 * Po poprawnej autoryzacji zapis danych i loga
	 * zwraca rekord autoryzacji
	 * @param \Cms\Orm\Auth\Record $record
	 * @return \Mmi\Auth\Record
	 */
	protected static function _authSuccess(\Cms\Orm\Auth\Record $record) {
		//zapis poprawnego logowania do rekordu
		$record->lastIp = \Mmi\Controller\Front::getInstance()->getEnvironment()->remoteAddress;
		$record->lastLog = date('Y-m-d H:i:s');
		$record->save();
		//logowanie pozytywnej autoryzacji
		\Cms\Model\Log::add('login', [
			'object' => 'cms_auth',
			'objectId' => $record->id,
			'cmsAuthId' => $record->id,
			'success' => true,
			'message' => 'LOGGED: ' . $record->username
		]);
		//nowy obiekt autoryzacji
		$authRecord = new \Mmi\Auth\Record();
		//ustawianie pól rekordu
		$authRecord->id = $record->id;
		$authRecord->name = $record->name;
		$authRecord->username = $record->username;
		$authRecord->email = $record->email;
		$authRecord->lang = $record->lang;
		$authRecord->roles = $record->getOption('roles') ? $record->getOption('roles') : ['guest'];
		return $authRecord;
	}

	/**
	 * Autoryzacja lokalna
	 * @param \Cms\Orm\Auth\Record $identity
	 * @param string $credential
	 * @return boolean
	 */
	protected static function _localAuthenticate(\Cms\Orm\Auth\Record $identity, $credential) {
		//rekord aktywny i hasło zgodne
		if ($identity->password == self::getSaltedPasswordHash($credential) || $identity->password == sha1($credential)) {
			return true;
		}
		return false;
	}

	/**
	 * Autoryzacja LDAP
	 * @param \Cms\Orm\Auth\Record $identity
	 * @param string $credential
	 * @return boolean
	 */
	protected static function _ldapAuthenticate(\Cms\Orm\Auth\Record $identity, $credential) {
		//ldap wyłączony
		if (!isset(\App\Registry::$config->ldap) || !\App\Registry::$config->ldap->active) {
			return;
		}
		try {
			//tworzenie klienta
			$ldapClient = new \Mmi\Ldap\Client(\Mmi\Ldap\ServerConfig::factory()
					->setAddress(\App\Registry::$config->ldap->address)
					->setUser(\App\Registry::$config->ldap->user)
					->setPassword(\App\Registry::$config->ldap->password)
					->setDomain(\App\Registry::$config->ldap->domain));

			//kalkulacja DN na podstawie patternu z konfiguracji
			$dn = sprintf(\App\Registry::$config->ldap->dnPattern, str_replace('@' . \App\Registry::$config->ldap->domain, '', $identity->username));

			//zwrot autoryzacji LDAP
			return $ldapClient->authenticate($dn, $credential);
		} catch (\Mmi\Ldap\Exception $e) {
			//błąd LDAP'a
			\Cms\Model\Log::add('LDAP failed', ['message' => $e->getMessage()]);
			return false;
		}
	}

	/**
	 * Znajduje użytkownika po nazwie lub mailu
	 * @param string $identity
	 * @return \Cms\Orm\Auth\Record
	 */
	protected static function _findUserByIdentity($identity) {
		return Orm\Auth\Query::factory()
				->whereActive()->equals(true)
				->andQuery(Orm\Auth\Query::factory()
					->whereUsername()->equals($identity)
					->orFieldEmail()->equals($identity)
					->orFieldId()->equals((integer) $identity))
				->findFirst();
	}

	/**
	 * Aktualizuje rekord użytkownika o błędne logowanie
	 * @param \Cms\Orm\Auth\Record $record
	 */
	protected static function _updateUserFailedLogin($record) {
		//zapis danych błędnego logowania znanego użytkownika
		$record->lastFailIp = \Mmi\Controller\Front::getInstance()->getEnvironment()->remoteAddress;
		$record->lastFailLog = date('Y-m-d H:i:s');
		$record->failLogCount = $record->failLogCount + 1;
		$record->save();
	}

}
