<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsAclQuery;

class Acl
{
    /**
     * Ustawianie ACL'a
     * @return \Mmi\Security\Acl
     */
    public static function setupAcl()
    {
        $acl = new \Mmi\Security\Acl();
        $aclData = (new CmsAclQuery())
            ->join('cms_role')->on('cms_role_id')
            ->find();
        foreach ($aclData as $aclRule) { /* @var $aclData \Cms\Orm\CmsAclRecord */
            $resource = '';
            if ($aclRule->module) {
                $resource .= $aclRule->module . ':';
            }
            if ($aclRule->controller) {
                $resource .= $aclRule->controller . ':';
            }
            if ($aclRule->action) {
                $resource .= $aclRule->action . ':';
            }
            $access = $aclRule->access;
            if ($access == 'allow' || $access == 'deny') {
                $acl->$access($aclRule->getJoined('cms_role')->name, trim($resource, ':'));
            }
        }
        return $acl;
    }
}
