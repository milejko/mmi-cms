<?php

namespace Cms\Orm;

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
        $rule = new \Cms\Orm\CmsAclRecord;
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
        \Mmi\Orm\DbConnector::getAdapter()->beginTransaction();
        //usuwanie uprawnień ról
        (new CmsAclQuery)->whereCmsRoleId()->equals($this->id)
            ->find()->delete();
        try {
            $result = parent::delete();
        } catch (Mmi\Db\DbException $e) {
            \Mmi\Orm\DbConnector::getAdapter()->rollBack();
            return false;
        }
        \Mmi\Orm\DbConnector::getAdapter()->commit();
        return $result;
    }

}
