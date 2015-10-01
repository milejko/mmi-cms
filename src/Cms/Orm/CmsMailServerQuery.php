<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsMailServerQuery">
/**
 * @method CmsMailServerQuery limit($limit = null)
 * @method CmsMailServerQuery offset($offset = null)
 * @method CmsMailServerQuery orderAsc($fieldName, $tableName = null)
 * @method CmsMailServerQuery orderDesc($fieldName, $tableName = null)
 * @method CmsMailServerQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsMailServerQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsMailServerQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsMailServerQuery resetOrder()
 * @method CmsMailServerQuery resetWhere()
 * @method QueryHelper\CmsMailServerQueryField whereId()
 * @method QueryHelper\CmsMailServerQueryField andFieldId()
 * @method QueryHelper\CmsMailServerQueryField orFieldId()
 * @method CmsMailServerQuery orderAscId()
 * @method CmsMailServerQuery orderDescId()
 * @method CmsMailServerQuery groupById()
 * @method QueryHelper\CmsMailServerQueryField whereAddress()
 * @method QueryHelper\CmsMailServerQueryField andFieldAddress()
 * @method QueryHelper\CmsMailServerQueryField orFieldAddress()
 * @method CmsMailServerQuery orderAscAddress()
 * @method CmsMailServerQuery orderDescAddress()
 * @method CmsMailServerQuery groupByAddress()
 * @method QueryHelper\CmsMailServerQueryField wherePort()
 * @method QueryHelper\CmsMailServerQueryField andFieldPort()
 * @method QueryHelper\CmsMailServerQueryField orFieldPort()
 * @method CmsMailServerQuery orderAscPort()
 * @method CmsMailServerQuery orderDescPort()
 * @method CmsMailServerQuery groupByPort()
 * @method QueryHelper\CmsMailServerQueryField whereUsername()
 * @method QueryHelper\CmsMailServerQueryField andFieldUsername()
 * @method QueryHelper\CmsMailServerQueryField orFieldUsername()
 * @method CmsMailServerQuery orderAscUsername()
 * @method CmsMailServerQuery orderDescUsername()
 * @method CmsMailServerQuery groupByUsername()
 * @method QueryHelper\CmsMailServerQueryField wherePassword()
 * @method QueryHelper\CmsMailServerQueryField andFieldPassword()
 * @method QueryHelper\CmsMailServerQueryField orFieldPassword()
 * @method CmsMailServerQuery orderAscPassword()
 * @method CmsMailServerQuery orderDescPassword()
 * @method CmsMailServerQuery groupByPassword()
 * @method QueryHelper\CmsMailServerQueryField whereFrom()
 * @method QueryHelper\CmsMailServerQueryField andFieldFrom()
 * @method QueryHelper\CmsMailServerQueryField orFieldFrom()
 * @method CmsMailServerQuery orderAscFrom()
 * @method CmsMailServerQuery orderDescFrom()
 * @method CmsMailServerQuery groupByFrom()
 * @method QueryHelper\CmsMailServerQueryField whereDateAdd()
 * @method QueryHelper\CmsMailServerQueryField andFieldDateAdd()
 * @method QueryHelper\CmsMailServerQueryField orFieldDateAdd()
 * @method CmsMailServerQuery orderAscDateAdd()
 * @method CmsMailServerQuery orderDescDateAdd()
 * @method CmsMailServerQuery groupByDateAdd()
 * @method QueryHelper\CmsMailServerQueryField whereDateModify()
 * @method QueryHelper\CmsMailServerQueryField andFieldDateModify()
 * @method QueryHelper\CmsMailServerQueryField orFieldDateModify()
 * @method CmsMailServerQuery orderAscDateModify()
 * @method CmsMailServerQuery orderDescDateModify()
 * @method CmsMailServerQuery groupByDateModify()
 * @method QueryHelper\CmsMailServerQueryField whereActive()
 * @method QueryHelper\CmsMailServerQueryField andFieldActive()
 * @method QueryHelper\CmsMailServerQueryField orFieldActive()
 * @method CmsMailServerQuery orderAscActive()
 * @method CmsMailServerQuery orderDescActive()
 * @method CmsMailServerQuery groupByActive()
 * @method QueryHelper\CmsMailServerQueryField whereSsl()
 * @method QueryHelper\CmsMailServerQueryField andFieldSsl()
 * @method QueryHelper\CmsMailServerQueryField orFieldSsl()
 * @method CmsMailServerQuery orderAscSsl()
 * @method CmsMailServerQuery orderDescSsl()
 * @method CmsMailServerQuery groupBySsl()
 * @method QueryHelper\CmsMailServerQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsMailServerQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsMailServerQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsMailServerQueryJoin join($tableName, $targetTableName = null)
 * @method QueryHelper\CmsMailServerQueryJoin joinLeft($tableName, $targetTableName = null)
 * @method CmsMailServerRecord[] find()
 * @method CmsMailServerRecord findFirst()
 * @method CmsMailServerRecord findPk($value)
 */
//</editor-fold>
class CmsMailServerQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_mail_server';

	/**
	 * @return CmsMailServerQuery
	 */
	public static function factory($tableName = null) {
		return new self($tableName);
	}

}
