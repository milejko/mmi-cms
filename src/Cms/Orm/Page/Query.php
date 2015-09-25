<?php

namespace Cms\Orm\Page;

//<editor-fold defaultstate="collapsed" desc="cms_page Query">
/**
 * @method \Cms\Orm\Page\Query limit($limit = null)
 * @method \Cms\Orm\Page\Query offset($offset = null)
 * @method \Cms\Orm\Page\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Page\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Page\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Page\Query resetOrder()
 * @method \Cms\Orm\Page\Query resetWhere()
 * @method \Cms\Orm\Page\QueryField whereId()
 * @method \Cms\Orm\Page\QueryField andFieldId()
 * @method \Cms\Orm\Page\QueryField orFieldId()
 * @method \Cms\Orm\Page\Query orderAscId()
 * @method \Cms\Orm\Page\Query orderDescId()
 * @method \Cms\Orm\Page\Query groupById()
 * @method \Cms\Orm\Page\QueryField whereName()
 * @method \Cms\Orm\Page\QueryField andFieldName()
 * @method \Cms\Orm\Page\QueryField orFieldName()
 * @method \Cms\Orm\Page\Query orderAscName()
 * @method \Cms\Orm\Page\Query orderDescName()
 * @method \Cms\Orm\Page\Query groupByName()
 * @method \Cms\Orm\Page\QueryField whereCmsNavigationId()
 * @method \Cms\Orm\Page\QueryField andFieldCmsNavigationId()
 * @method \Cms\Orm\Page\QueryField orFieldCmsNavigationId()
 * @method \Cms\Orm\Page\Query orderAscCmsNavigationId()
 * @method \Cms\Orm\Page\Query orderDescCmsNavigationId()
 * @method \Cms\Orm\Page\Query groupByCmsNavigationId()
 * @method \Cms\Orm\Page\QueryField whereCmsRouteId()
 * @method \Cms\Orm\Page\QueryField andFieldCmsRouteId()
 * @method \Cms\Orm\Page\QueryField orFieldCmsRouteId()
 * @method \Cms\Orm\Page\Query orderAscCmsRouteId()
 * @method \Cms\Orm\Page\Query orderDescCmsRouteId()
 * @method \Cms\Orm\Page\Query groupByCmsRouteId()
 * @method \Cms\Orm\Page\QueryField whereText()
 * @method \Cms\Orm\Page\QueryField andFieldText()
 * @method \Cms\Orm\Page\QueryField orFieldText()
 * @method \Cms\Orm\Page\Query orderAscText()
 * @method \Cms\Orm\Page\Query orderDescText()
 * @method \Cms\Orm\Page\Query groupByText()
 * @method \Cms\Orm\Page\QueryField whereActive()
 * @method \Cms\Orm\Page\QueryField andFieldActive()
 * @method \Cms\Orm\Page\QueryField orFieldActive()
 * @method \Cms\Orm\Page\Query orderAscActive()
 * @method \Cms\Orm\Page\Query orderDescActive()
 * @method \Cms\Orm\Page\Query groupByActive()
 * @method \Cms\Orm\Page\QueryField whereDateAdd()
 * @method \Cms\Orm\Page\QueryField andFieldDateAdd()
 * @method \Cms\Orm\Page\QueryField orFieldDateAdd()
 * @method \Cms\Orm\Page\Query orderAscDateAdd()
 * @method \Cms\Orm\Page\Query orderDescDateAdd()
 * @method \Cms\Orm\Page\Query groupByDateAdd()
 * @method \Cms\Orm\Page\QueryField whereDateModify()
 * @method \Cms\Orm\Page\QueryField andFieldDateModify()
 * @method \Cms\Orm\Page\QueryField orFieldDateModify()
 * @method \Cms\Orm\Page\Query orderAscDateModify()
 * @method \Cms\Orm\Page\Query orderDescDateModify()
 * @method \Cms\Orm\Page\Query groupByDateModify()
 * @method \Cms\Orm\Page\QueryField whereCmsAuthId()
 * @method \Cms\Orm\Page\QueryField andFieldCmsAuthId()
 * @method \Cms\Orm\Page\QueryField orFieldCmsAuthId()
 * @method \Cms\Orm\Page\Query orderAscCmsAuthId()
 * @method \Cms\Orm\Page\Query orderDescCmsAuthId()
 * @method \Cms\Orm\Page\Query groupByCmsAuthId()
 * @method \Cms\Orm\Page\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Page\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Page\Record[] find()
 * @method \Cms\Orm\Page\Record findFirst()
 * @method \Cms\Orm\Page\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_page';

	/**
	 * @return \Cms\Orm\Page\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * 
	 * @return \Cms\Orm\Page\Query
	 */
	public static function activeById($id) {
		return self::factory()
				->whereId()->equals($id)
				->andFieldActive()->equals(true);
	}

}
