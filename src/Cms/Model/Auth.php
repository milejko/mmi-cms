<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsAuthQuery;
use Cms\Orm\CmsAuthRecord;
use Mmi\App\App;
use Mmi\Http\Request;
use Mmi\Ldap\LdapConfig;
use Psr\Log\LoggerInterface;

/**
 * Model autoryzacji
 */
class Auth implements \Mmi\Security\AuthInterface
{

    /**
     * Autoryzacja do CMS
     * @param string $identity
     * @param string $credential
     * @return \Mmi\Security\AuthRecord
     */
    public static function authenticate($identity, $credential)
    {
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
        return self::_authSuccess($record);
    }

    /**
     * Autoryzacja po ID
     * @param integer $id
     * @return boolean
     */
    public static function idAuthenticate($id)
    {
        //wyszukiwanie aktywnego użytkownika
        if (null === $record = self::_findUserByIdentity($id)) {
            return;
        }
        return self::_authSuccess($record);
    }

    /**
     * Wylogowanie
     */
    public static function deauthenticate()
    {}

    /**
     * Zwraca hash hasła zakodowany z "solą"
     * @param string $password
     * @return string
     */
    public static function getSaltedPasswordHash($password)
    {
        return hash('sha512', App::$di->get('cms.auth.salt') . md5($password) . $password . 'sltd');
    }

    /**
     * Zapytanie o użytkowników
     * @param string $query
     * @return array
     */
    public function ldapAutocomplete($query = '*')
    {
        //tworzenie klienta
        if (!App::$di->has(LdapConfig::class)) {
            return [];
        }
        $ldapClient = new \Mmi\Ldap\LdapClient(App::$di->get(LdapConfig::class));
        try {
            //wyszukiwanie w LDAPie
            $ldapResults = $ldapClient->findUser($query, 10, ['sAMAccountname']);
        } catch (\Exception $e) {
            //błąd usługi
            App::$di->get(LoggerInterface::class)->error($e->getMessage());
            return [];
        }
        //budowa tablicy z użytkownikami
        $userTable = [];
        foreach ($ldapResults as $key => $user) {
            /* @var $user \Mmi\Ldap\LdapUserRecord */
            $userTable[$key]['label'] = $user->sAMAccountname;
            $userTable[$key]['name'] = $user->cn;
            $userTable[$key]['email'] = $user->mail;
        }
        return $userTable;
    }

    /**
     * Obsługa błędnego logowania znanego użytkownika
     * @param string $identity
     */
    protected static function _authFailed($identity, $reason = '')
    {
        //logowanie błędnej próby autoryzacji
        App::$di->get(LoggerInterface::class)->notice('Login failed: ' . $identity . ' ' . $reason);
    }

    /**
     * Po poprawnej autoryzacji zapis danych i loga
     * zwraca rekord autoryzacji
     * @param CmsAuthRecord $record
     * @return \Mmi\Security\AuthRecord
     */
    protected static function _authSuccess(CmsAuthRecord $record)
    {
        //zapis poprawnego logowania do rekordu
        $record->lastIp = App::$di->get(Request::class)->getServer()->remoteAddress;
        $record->lastLog = date('Y-m-d H:i:s');
        $record->save();
        App::$di->get(LoggerInterface::class)->info('Logged in: ' . $record->username);
        //nowy obiekt autoryzacji
        $authRecord = new \Mmi\Security\AuthRecord;
        //ustawianie pól rekordu
        $authRecord->id = $record->id;
        $authRecord->name = $record->name;
        $authRecord->username = $record->username;
        $authRecord->email = $record->email;
        $authRecord->lang = $record->lang;
        $authRecord->roles = count($record->getRoles()) ? $record->getRoles() : ['guest'];
        return $authRecord;
    }

    /**
     * Autoryzacja lokalna
     * @param CmsAuthRecord $identity
     * @param string $credential
     * @return boolean
     */
    protected static function _localAuthenticate(CmsAuthRecord $identity, $credential)
    {
        //rekord aktywny i hasło zgodne
        if ($identity->password == self::getSaltedPasswordHash($credential) || $identity->password == sha1($credential)) {
            return true;
        }
        return false;
    }

    /**
     * Autoryzacja LDAP
     * @param CmsAuthRecord $identity
     * @param string $credential
     * @return boolean
     */
    protected static function _ldapAuthenticate(CmsAuthRecord $identity, $credential)
    {
        //ldap wyłączony
        if (!App::$di->has(LdapConfig::class)) {
            return;
        }
        $config = App::$di->get(LdapConfig::class);
        try {
            //tworzenie klienta
            $ldapClient = new \Mmi\Ldap\LdapClient($config->ldap);

            //kalkulacja DN na podstawie patternu z konfiguracji
            $dn = sprintf($config->ldap->dnPattern, str_replace('@' . $config->ldap->domain, '', $identity->username));

            //zwrot autoryzacji LDAP
            return $ldapClient->authenticate($dn, $credential);
        } catch (\Exception $e) {
            //błąd LDAP'a
            App::$di->get(LoggerInterface::class)->error('LDAP failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Znajduje użytkownika po nazwie lub mailu
     * @param string $identity
     * @return CmsAuthRecord
     */
    protected static function _findUserByIdentity($identity)
    {
        try {
            return (new CmsAuthQuery)
                ->whereActive()->equals(true)
                ->andQuery((new CmsAuthQuery)
                    ->whereUsername()->equals($identity)
                    ->orFieldEmail()->equals($identity)
                    ->orFieldId()->equals((integer)$identity))
                ->findFirst();
        } catch (\Exception $e) {
            App::$di->get(LoggerInterface::class)->error($e->getMessage());
        }
    }

    /**
     * Aktualizuje rekord użytkownika o błędne logowanie
     * @param CmsAuthRecord $record
     */
    protected static function _updateUserFailedLogin($record)
    {
        //zapis danych błędnego logowania znanego użytkownika
        $record->lastFailIp = App::$di->get(Request::class)->getServer()->remoteAddress;
        $record->lastFailLog = date('Y-m-d H:i:s');
        $record->failLogCount = $record->failLogCount + 1;
        $record->save();
    }
}
