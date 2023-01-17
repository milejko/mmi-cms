<?php

namespace Cms\Orm;

/**
 * Rekord użytkownika CMS
 */
class CmsAuthRecord extends \Mmi\Orm\Record
{
    public $id;
    public $lang;
    public $name;
    public $username;
    public $email;
    public $password;
    public $roles;
    public $lastIp;
    public $lastLog;
    public $lastFailIp;
    public $lastFailLog;
    public $failLogCount;
    public $logged;
    public $active;

    /**
     * Zwraca role użytkownika jako tablicę
     * @return array
     */
    public function getRoles()
    {
        return explode(',', $this->roles);
    }
}
