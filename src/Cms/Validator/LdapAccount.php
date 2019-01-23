<?php

namespace Cms\Validator;

/**
 * Walidator konta w katalogu LDAP
 */
class LdapAccount extends \Mmi\Validator\ValidatorAbstract
{

    /**
     * Treść wiadomości
     */
    const INVALID = 'validator.ldapAccount.message';

    /**
     * Walidacja znaków alfanumerycznych
     * @param mixed $value wartość
     * @return boolean
     */
    public function isValid($value)
    {
        //sprawdzanie istnienia pojedynczego użytkownika o podanej nazwie
        if (count((new \Cms\Model\Auth())->ldapAutocomplete($value)) != 1) {
            $this->_error(self::INVALID);
            return false;
        }
        //poprawne
        return true;
    }

}
