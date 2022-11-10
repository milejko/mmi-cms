<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Security;

use Cms\Orm\CmsAuthQuery;
use Cms\Orm\CmsAuthRecord;
use Mmi\App\App;
use Mmi\Http\Request;
use Mmi\Ldap\LdapClient;
use Mmi\Ldap\LdapConfig;
use Mmi\Security\AuthProviderInterface;
use Mmi\Security\AuthRecord;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Default CMS Auth provider
 */
class AuthProvider implements AuthProviderInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(
        LoggerInterface $logger,
        Request $request,
        ContainerInterface $container
    ) {
        $this->logger       = $logger;
        $this->request      = $request;
        $this->container    = $container;
    }

    /**
     * Autoryzacja do CMS
     */
    public function authenticate(string $identity, string $credential): ?AuthRecord
    {
        //błędna autoryzacja (brak aktywnego użytkownika)
        if (null === $record = $this->_findUserByIdentity($identity)) {
            return null;
        }

        //próby logowania lokalnie i ldap
        if (!$this->_localAuthenticate($record, $credential) &&
            !$this->_ldapAuthenticate($record, $credential)
        ) {
            $this->_updateUserFailedLogin($record);
            return $this->_authFailed($identity, 'Invalid password.');
        }

        //poprawna autoryzacja
        return $this->_authSuccess($record);
    }

    /**
     * Autoryzacja po ID
     */
    public function idAuthenticate(string $id): ?AuthRecord
    {
        //wyszukiwanie aktywnego użytkownika
        if (null === $record = $this->_findUserByIdentity($id)) {
            return null;
        }
        return $this->_authSuccess($record);
    }

    /**
     * Wylogowanie
     */
    public function deauthenticate(): void
    {
    }

    /**
     * Zwraca hash hasła zakodowany z "solą"
     * @param string $password
     * @return string
     */
    public function getSaltedPasswordHash($password)
    {
        return hash('sha512', $this->container->get('cms.auth.salt') . md5($password) . $password . 'sltd');
    }

    /**
     * Zapytanie o użytkowników
     * @param string $query
     * @return array
     */
    public function ldapAutocomplete($query = '*')
    {
        //tworzenie klienta
        if (!$this->container->has(LdapConfig::class)) {
            return [];
        }
        $ldapClient = new LdapClient($this->container->get(LdapConfig::class));
        try {
            //wyszukiwanie w LDAPie
            $ldapResults = $ldapClient->findUser($query, 10, ['sAMAccountname']);
        } catch (\Exception $e) {
            //błąd usługi
            $this->logger->error($e->getMessage());
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
    protected function _authFailed($identity, $reason = '')
    {
        //logowanie błędnej próby autoryzacji
        $this->logger->notice('Login failed: ' . $identity . ' ' . $reason);
    }

    /**
     * Po poprawnej autoryzacji zapis danych i loga
     * zwraca rekord autoryzacji
     * @param CmsAuthRecord $record
     * @return \Mmi\Security\AuthRecord
     */
    protected function _authSuccess(CmsAuthRecord $record)
    {
        //zapis poprawnego logowania do rekordu
        $record->lastIp = $this->request->getServer()->remoteAddress;
        $record->lastLog = date('Y-m-d H:i:s');
        $record->save();
        $this->logger->info('Logged in: ' . $record->username);
        //nowy obiekt autoryzacji
        $authRecord = new \Mmi\Security\AuthRecord();
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
    protected function _localAuthenticate(CmsAuthRecord $identity, $credential)
    {
        //rekord aktywny i hasło zgodne
        if ($identity->password == $this->getSaltedPasswordHash($credential) || $identity->password == sha1($credential)) {
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
    protected function _ldapAuthenticate(CmsAuthRecord $identity, $credential)
    {
        //ldap wyłączony
        if (!$this->container->has(LdapConfig::class)) {
            return;
        }
        $config = $this->container->get(LdapConfig::class);
        try {
            //tworzenie klienta
            $ldapClient = new \Mmi\Ldap\LdapClient($config);

            //kalkulacja DN na podstawie patternu z konfiguracji
            $dn = sprintf($config->dnPattern, str_replace('@' . $config->domain, '', $identity->username));

            //zwrot autoryzacji LDAP
            return $ldapClient->authenticate($dn, $credential);
        } catch (\Exception $e) {
            //błąd LDAP'a
            $this->logger->error('LDAP failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Znajduje użytkownika po nazwie lub mailu
     * @param string $identity
     * @return CmsAuthRecord
     */
    protected function _findUserByIdentity($identity)
    {
        try {
            return (new CmsAuthQuery())
                ->whereActive()->equals(true)
                ->andQuery((new CmsAuthQuery())
                    ->whereUsername()->equals($identity)
                    ->orFieldEmail()->equals($identity)
                    ->orFieldId()->equals((int)$identity))
                ->findFirst();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * Aktualizuje rekord użytkownika o błędne logowanie
     * @param CmsAuthRecord $record
     */
    protected function _updateUserFailedLogin($record)
    {
        //zapis danych błędnego logowania znanego użytkownika
        $record->lastFailIp = $this->request->getServer()->remoteAddress;
        $record->lastFailLog = date('Y-m-d H:i:s');
        $record->failLogCount = $record->failLogCount + 1;
        $record->save();
    }
}
