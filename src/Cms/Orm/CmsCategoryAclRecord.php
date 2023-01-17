<?php

namespace Cms\Orm;

class CmsCategoryAclRecord extends \Mmi\Orm\Record
{
    public $id;
    public $role;
    public $cmsCategoryId;
    public $access;
}
