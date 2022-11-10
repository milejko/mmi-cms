<?php

namespace Cms\Orm;

use Mmi\App\App;
use Mmi\Db\DbInterface;
use Mmi\Db\DbException;

/**
 * Rekord roli
 */
class CmsRoleRecord extends \Mmi\Orm\Record
{
    public $id;
    public $name;

    /**
     * Zapis roli
     * @return boolean
     */
    public function save()
    {
        if (!parent::save()) {
            return false;
        }
        //zapis reguły dostępu do defaulta dla zapisanej roli
        $rule = new \Cms\Orm\CmsAclRecord();
        $rule->cmsRoleId = $this->id;
        $rule->module = 'mmi';
        $rule->access = 'allow';
        //zapis reguły acl
        return $rule->save();
    }

    /**
     * Usuwanie roli
     * @return boolean
     */
    public function delete()
    {
        //zablokowane kasowanie admina i guesta
        if ($this->name == 'admin' || $this->name == 'guest') {
            return false;
        }
        $db = App::$di->get(DbInterface::class);
        $db->beginTransaction();
        //usuwanie uprawnień ról
        (new CmsAclQuery())->whereCmsRoleId()->equals($this->id)
            ->delete();
        try {
            $result = parent::delete();
        } catch (DbException $e) {
            $db->rollBack();
            return false;
        }
        $db->commit();
        return $result;
    }
}
