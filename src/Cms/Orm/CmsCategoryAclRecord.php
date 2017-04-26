<?php

namespace Cms\Orm;

class CmsCategoryAclRecord extends \Mmi\Orm\Record
{

    public $id;
    public $cmsRoleId;
    public $cmsCategoryId;
    public $access;

}
