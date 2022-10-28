<?php

namespace Cms\Orm;

/**
 * Rekord łączący rolę z użytkownikiem
 */
class CmsAuthRoleRecord extends \Mmi\Orm\Record
{
    public $id;
    public $cmsAuthId;
    public $cmsRoleId;
}
