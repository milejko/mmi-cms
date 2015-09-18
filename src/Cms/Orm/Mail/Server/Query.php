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
 * @method \Cms\Orm\Mail\Server\Query\Field whereId()
 * @method \Cms\Orm\Mail\Server\Query\Field andFieldId()
 * @method \Cms\Orm\Mail\Server\Query\Field orFieldId()
 * @method \Cms\Orm\Mail\Server\Query orderAscId()
 * @method \Cms\Orm\Mail\Server\Query orderDescId()
 * @method \Cms\Orm\Mail\Server\Query groupById()
 * @method \Cms\Orm\Mail\Server\Query\Field whereAddress()
 * @method \Cms\Orm\Mail\Server\Query\Field andFieldAddress()
 * @method \Cms\Orm\Mail\Server\Query\Field orFieldAddress()
 * @method \Cms\Orm\Mail\Server\Query orderAscAddress()
 * @method \Cms\Orm\Mail\Server\Query orderDescAddress()
 * @method \Cms\Orm\Mail\Server\Query groupByAddress()
 * @method \Cms\Orm\Mail\Server\Query\Field wherePort()
 * @method \Cms\Orm\Mail\Server\Query\Field andFieldPort()
 * @method \Cms\Orm\Mail\Server\Query\Field orFieldPort()
 * @method \Cms\Orm\Mail\Server\Query orderAscPort()
 * @method \Cms\Orm\Mail\Server\Query orderDescPort()
 * @method \Cms\Orm\Mail\Server\Query groupByPort()
 * @method \Cms\Orm\Mail\Server\Query\Field whereUsername()
 * @method \Cms\Orm\Mail\Server\Query\Field andFieldUsername()
 * @method \Cms\Orm\Mail\Server\Query\Field orFieldUsername()
 * @method \Cms\Orm\Mail\Server\Query orderAscUsername()
 * @method \Cms\Orm\Mail\Server\Query orderDescUsername()
 * @method \Cms\Orm\Mail\Server\Query groupByUsername()
 * @method \Cms\Orm\Mail\Server\Query\Field wherePassword()
 * @method \Cms\Orm\Mail\Server\Query\Field andFieldPassword()
 * @method \Cms\Orm\Mail\Server\Query\Field orFieldPassword()
 * @method \Cms\Orm\Mail\Server\Query orderAscPassword()
 * @method \Cms\Orm\Mail\Server\Query orderDescPassword()
 * @method \Cms\Orm\Mail\Server\Query groupByPassword()
 * @method \Cms\Orm\Mail\Server\Query\Field whereFrom()
 * @method \Cms\Orm\Mail\Server\Query\Field andFieldFrom()
 * @method \Cms\Orm\Mail\Server\Query\Field orFieldFrom()
 * @method \Cms\Orm\Mail\Server\Query orderAscFrom()
 * @method \Cms\Orm\Mail\Server\Query orderDescFrom()
 * @method \Cms\Orm\Mail\Server\Query groupByFrom()
 * @method \Cms\Orm\Mail\Server\Query\Field whereDateAdd()
 * @method \Cms\Orm\Mail\Server\Query\Field andFieldDateAdd()
 * @method \Cms\Orm\Mail\Server\Query\Field orFieldDateAdd()
 * @method \Cms\Orm\Mail\Server\Query orderAscDateAdd()
 * @method \Cms\Orm\Mail\Server\Query orderDescDateAdd()
 * @method \Cms\Orm\Mail\Server\Query groupByDateAdd()
 * @method \Cms\Orm\Mail\Server\Query\Field whereDateModify()
 * @method \Cms\Orm\Mail\Server\Query\Field andFieldDateModify()
 * @method \Cms\Orm\Mail\Server\Query\Field orFieldDateModify()
 * @method \Cms\Orm\Mail\Server\Query orderAscDateModify()
 * @method \Cms\Orm\Mail\Server\Query orderDescDateModify()
 * @method \Cms\Orm\Mail\Server\Query groupByDateModify()
 * @method \Cms\Orm\Mail\Server\Query\Field whereActive()
 * @method \Cms\Orm\Mail\Server\Query\Field andFieldActive()
 * @method \Cms\Orm\Mail\Server\Query\Field orFieldActive()
 * @method \Cms\Orm\Mail\Server\Query orderAscActive()
 * @method \Cms\Orm\Mail\Server\Query orderDescActive()
 * @method \Cms\Orm\Mail\Server\Query groupByActive()
 * @method \Cms\Orm\Mail\Server\Query\Field whereSsl()
 * @method \Cms\Orm\Mail\Server\Query\Field andFieldSsl()
 * @method \Cms\Orm\Mail\Server\Query\Field orFieldSsl()
 * @method \Cms\Orm\Mail\Server\Query orderAscSsl()
 * @method \Cms\Orm\Mail\Server\Query orderDescSsl()
 * @method \Cms\Orm\Mail\Server\Query groupBySsl()
 * @method \Cms\Orm\Mail\Server\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Mail\Server\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Mail\Server\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Mail\Server\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Mail\Server\Query\Join joinLeft($tableName, $targetTableName = null)
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
