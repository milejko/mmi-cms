<?php

namespace Cms\Orm\Auth;

//<editor-fold defaultstate="collapsed" desc="cms_auth Query">
/**
 * @method \Cms\Orm\Auth\Query limit($limit = null)
 * @method \Cms\Orm\Auth\Query offset($offset = null)
 * @method \Cms\Orm\Auth\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Auth\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Auth\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Auth\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Auth\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Auth\Query resetOrder()
 * @method \Cms\Orm\Auth\Query resetWhere()
 * @method \Cms\Orm\Auth\QueryField whereId()
 * @method \Cms\Orm\Auth\QueryField andFieldId()
 * @method \Cms\Orm\Auth\QueryField orFieldId()
 * @method \Cms\Orm\Auth\Query orderAscId()
 * @method \Cms\Orm\Auth\Query orderDescId()
 * @method \Cms\Orm\Auth\Query groupById()
 * @method \Cms\Orm\Auth\QueryField whereLang()
 * @method \Cms\Orm\Auth\QueryField andFieldLang()
 * @method \Cms\Orm\Auth\QueryField orFieldLang()
 * @method \Cms\Orm\Auth\Query orderAscLang()
 * @method \Cms\Orm\Auth\Query orderDescLang()
 * @method \Cms\Orm\Auth\Query groupByLang()
 * @method \Cms\Orm\Auth\QueryField whereName()
 * @method \Cms\Orm\Auth\QueryField andFieldName()
 * @method \Cms\Orm\Auth\QueryField orFieldName()
 * @method \Cms\Orm\Auth\Query orderAscName()
 * @method \Cms\Orm\Auth\Query orderDescName()
 * @method \Cms\Orm\Auth\Query groupByName()
 * @method \Cms\Orm\Auth\QueryField whereUsername()
 * @method \Cms\Orm\Auth\QueryField andFieldUsername()
 * @method \Cms\Orm\Auth\QueryField orFieldUsername()
 * @method \Cms\Orm\Auth\Query orderAscUsername()
 * @method \Cms\Orm\Auth\Query orderDescUsername()
 * @method \Cms\Orm\Auth\Query groupByUsername()
 * @method \Cms\Orm\Auth\QueryField whereEmail()
 * @method \Cms\Orm\Auth\QueryField andFieldEmail()
 * @method \Cms\Orm\Auth\QueryField orFieldEmail()
 * @method \Cms\Orm\Auth\Query orderAscEmail()
 * @method \Cms\Orm\Auth\Query orderDescEmail()
 * @method \Cms\Orm\Auth\Query groupByEmail()
 * @method \Cms\Orm\Auth\QueryField wherePassword()
 * @method \Cms\Orm\Auth\QueryField andFieldPassword()
 * @method \Cms\Orm\Auth\QueryField orFieldPassword()
 * @method \Cms\Orm\Auth\Query orderAscPassword()
 * @method \Cms\Orm\Auth\Query orderDescPassword()
 * @method \Cms\Orm\Auth\Query groupByPassword()
 * @method \Cms\Orm\Auth\QueryField whereLastIp()
 * @method \Cms\Orm\Auth\QueryField andFieldLastIp()
 * @method \Cms\Orm\Auth\QueryField orFieldLastIp()
 * @method \Cms\Orm\Auth\Query orderAscLastIp()
 * @method \Cms\Orm\Auth\Query orderDescLastIp()
 * @method \Cms\Orm\Auth\Query groupByLastIp()
 * @method \Cms\Orm\Auth\QueryField whereLastLog()
 * @method \Cms\Orm\Auth\QueryField andFieldLastLog()
 * @method \Cms\Orm\Auth\QueryField orFieldLastLog()
 * @method \Cms\Orm\Auth\Query orderAscLastLog()
 * @method \Cms\Orm\Auth\Query orderDescLastLog()
 * @method \Cms\Orm\Auth\Query groupByLastLog()
 * @method \Cms\Orm\Auth\QueryField whereLastFailIp()
 * @method \Cms\Orm\Auth\QueryField andFieldLastFailIp()
 * @method \Cms\Orm\Auth\QueryField orFieldLastFailIp()
 * @method \Cms\Orm\Auth\Query orderAscLastFailIp()
 * @method \Cms\Orm\Auth\Query orderDescLastFailIp()
 * @method \Cms\Orm\Auth\Query groupByLastFailIp()
 * @method \Cms\Orm\Auth\QueryField whereLastFailLog()
 * @method \Cms\Orm\Auth\QueryField andFieldLastFailLog()
 * @method \Cms\Orm\Auth\QueryField orFieldLastFailLog()
 * @method \Cms\Orm\Auth\Query orderAscLastFailLog()
 * @method \Cms\Orm\Auth\Query orderDescLastFailLog()
 * @method \Cms\Orm\Auth\Query groupByLastFailLog()
 * @method \Cms\Orm\Auth\QueryField whereFailLogCount()
 * @method \Cms\Orm\Auth\QueryField andFieldFailLogCount()
 * @method \Cms\Orm\Auth\QueryField orFieldFailLogCount()
 * @method \Cms\Orm\Auth\Query orderAscFailLogCount()
 * @method \Cms\Orm\Auth\Query orderDescFailLogCount()
 * @method \Cms\Orm\Auth\Query groupByFailLogCount()
 * @method \Cms\Orm\Auth\QueryField whereLogged()
 * @method \Cms\Orm\Auth\QueryField andFieldLogged()
 * @method \Cms\Orm\Auth\QueryField orFieldLogged()
 * @method \Cms\Orm\Auth\Query orderAscLogged()
 * @method \Cms\Orm\Auth\Query orderDescLogged()
 * @method \Cms\Orm\Auth\Query groupByLogged()
 * @method \Cms\Orm\Auth\QueryField whereActive()
 * @method \Cms\Orm\Auth\QueryField andFieldActive()
 * @method \Cms\Orm\Auth\QueryField orFieldActive()
 * @method \Cms\Orm\Auth\Query orderAscActive()
 * @method \Cms\Orm\Auth\Query orderDescActive()
 * @method \Cms\Orm\Auth\Query groupByActive()
 * @method \Cms\Orm\Auth\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Auth\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Auth\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Auth\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Auth\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Auth\Record[] find()
 * @method \Cms\Orm\Auth\Record findFirst()
 * @method \Cms\Orm\Auth\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_auth';

	/**
	 * @return \Cms\Orm\Auth\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * Zapytanie filtrujące użytkowników z daną rolą
	 * @param string $role
	 * @return \Cms\Orm\Auth\Query
	 */
	public static function byRole($role) {
		//wyszukuje konta z podaną rolą
		return self::factory()
				->join('cms_auth_role')->on('id', 'cms_auth_id')
				->join('cms_role', 'cms_auth_role')->on('cms_role_id', 'id')
				->where('name', 'cms_role')->equals($role);
	}

}
