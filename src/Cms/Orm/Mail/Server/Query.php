<?php

namespace Cms\Orm\Mail\Server;

//<editor-fold defaultstate="collapsed" desc="cms_mail_server Query">
/**
 * @method \Cms\Orm\Mail\Server\Query limit($limit = null)
 * @method \Cms\Orm\Mail\Server\Query offset($offset = null)
 * @method \Cms\Orm\Mail\Server\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Mail\Server\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Mail\Server\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Mail\Server\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Mail\Server\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Mail\Server\Query resetOrder()
 * @method \Cms\Orm\Mail\Server\Query resetWhere()
 * @method \Cms\Orm\Mail\Server\QueryField whereId()
 * @method \Cms\Orm\Mail\Server\QueryField andFieldId()
 * @method \Cms\Orm\Mail\Server\QueryField orFieldId()
 * @method \Cms\Orm\Mail\Server\Query orderAscId()
 * @method \Cms\Orm\Mail\Server\Query orderDescId()
 * @method \Cms\Orm\Mail\Server\Query groupById()
 * @method \Cms\Orm\Mail\Server\QueryField whereAddress()
 * @method \Cms\Orm\Mail\Server\QueryField andFieldAddress()
 * @method \Cms\Orm\Mail\Server\QueryField orFieldAddress()
 * @method \Cms\Orm\Mail\Server\Query orderAscAddress()
 * @method \Cms\Orm\Mail\Server\Query orderDescAddress()
 * @method \Cms\Orm\Mail\Server\Query groupByAddress()
 * @method \Cms\Orm\Mail\Server\QueryField wherePort()
 * @method \Cms\Orm\Mail\Server\QueryField andFieldPort()
 * @method \Cms\Orm\Mail\Server\QueryField orFieldPort()
 * @method \Cms\Orm\Mail\Server\Query orderAscPort()
 * @method \Cms\Orm\Mail\Server\Query orderDescPort()
 * @method \Cms\Orm\Mail\Server\Query groupByPort()
 * @method \Cms\Orm\Mail\Server\QueryField whereUsername()
 * @method \Cms\Orm\Mail\Server\QueryField andFieldUsername()
 * @method \Cms\Orm\Mail\Server\QueryField orFieldUsername()
 * @method \Cms\Orm\Mail\Server\Query orderAscUsername()
 * @method \Cms\Orm\Mail\Server\Query orderDescUsername()
 * @method \Cms\Orm\Mail\Server\Query groupByUsername()
 * @method \Cms\Orm\Mail\Server\QueryField wherePassword()
 * @method \Cms\Orm\Mail\Server\QueryField andFieldPassword()
 * @method \Cms\Orm\Mail\Server\QueryField orFieldPassword()
 * @method \Cms\Orm\Mail\Server\Query orderAscPassword()
 * @method \Cms\Orm\Mail\Server\Query orderDescPassword()
 * @method \Cms\Orm\Mail\Server\Query groupByPassword()
 * @method \Cms\Orm\Mail\Server\QueryField whereFrom()
 * @method \Cms\Orm\Mail\Server\QueryField andFieldFrom()
 * @method \Cms\Orm\Mail\Server\QueryField orFieldFrom()
 * @method \Cms\Orm\Mail\Server\Query orderAscFrom()
 * @method \Cms\Orm\Mail\Server\Query orderDescFrom()
 * @method \Cms\Orm\Mail\Server\Query groupByFrom()
 * @method \Cms\Orm\Mail\Server\QueryField whereDateAdd()
 * @method \Cms\Orm\Mail\Server\QueryField andFieldDateAdd()
 * @method \Cms\Orm\Mail\Server\QueryField orFieldDateAdd()
 * @method \Cms\Orm\Mail\Server\Query orderAscDateAdd()
 * @method \Cms\Orm\Mail\Server\Query orderDescDateAdd()
 * @method \Cms\Orm\Mail\Server\Query groupByDateAdd()
 * @method \Cms\Orm\Mail\Server\QueryField whereDateModify()
 * @method \Cms\Orm\Mail\Server\QueryField andFieldDateModify()
 * @method \Cms\Orm\Mail\Server\QueryField orFieldDateModify()
 * @method \Cms\Orm\Mail\Server\Query orderAscDateModify()
 * @method \Cms\Orm\Mail\Server\Query orderDescDateModify()
 * @method \Cms\Orm\Mail\Server\Query groupByDateModify()
 * @method \Cms\Orm\Mail\Server\QueryField whereActive()
 * @method \Cms\Orm\Mail\Server\QueryField andFieldActive()
 * @method \Cms\Orm\Mail\Server\QueryField orFieldActive()
 * @method \Cms\Orm\Mail\Server\Query orderAscActive()
 * @method \Cms\Orm\Mail\Server\Query orderDescActive()
 * @method \Cms\Orm\Mail\Server\Query groupByActive()
 * @method \Cms\Orm\Mail\Server\QueryField whereSsl()
 * @method \Cms\Orm\Mail\Server\QueryField andFieldSsl()
 * @method \Cms\Orm\Mail\Server\QueryField orFieldSsl()
 * @method \Cms\Orm\Mail\Server\Query orderAscSsl()
 * @method \Cms\Orm\Mail\Server\Query orderDescSsl()
 * @method \Cms\Orm\Mail\Server\Query groupBySsl()
 * @method \Cms\Orm\Mail\Server\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Mail\Server\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Mail\Server\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Mail\Server\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Mail\Server\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Mail\Server\Record[] find()
 * @method \Cms\Orm\Mail\Server\Record findFirst()
 * @method \Cms\Orm\Mail\Server\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_mail_server';

	/**
	 * @return \Cms\Orm\Mail\Server\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

}
