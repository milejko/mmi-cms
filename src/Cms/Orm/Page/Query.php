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
 * @method \Cms\Orm\Page\Query\Field whereId()
 * @method \Cms\Orm\Page\Query\Field andFieldId()
 * @method \Cms\Orm\Page\Query\Field orFieldId()
 * @method \Cms\Orm\Page\Query orderAscId()
 * @method \Cms\Orm\Page\Query orderDescId()
 * @method \Cms\Orm\Page\Query groupById()
 * @method \Cms\Orm\Page\Query\Field whereName()
 * @method \Cms\Orm\Page\Query\Field andFieldName()
 * @method \Cms\Orm\Page\Query\Field orFieldName()
 * @method \Cms\Orm\Page\Query orderAscName()
 * @method \Cms\Orm\Page\Query orderDescName()
 * @method \Cms\Orm\Page\Query groupByName()
 * @method \Cms\Orm\Page\Query\Field whereCmsNavigationId()
 * @method \Cms\Orm\Page\Query\Field andFieldCmsNavigationId()
 * @method \Cms\Orm\Page\Query\Field orFieldCmsNavigationId()
 * @method \Cms\Orm\Page\Query orderAscCmsNavigationId()
 * @method \Cms\Orm\Page\Query orderDescCmsNavigationId()
 * @method \Cms\Orm\Page\Query groupByCmsNavigationId()
 * @method \Cms\Orm\Page\Query\Field whereCmsRouteId()
 * @method \Cms\Orm\Page\Query\Field andFieldCmsRouteId()
 * @method \Cms\Orm\Page\Query\Field orFieldCmsRouteId()
 * @method \Cms\Orm\Page\Query orderAscCmsRouteId()
 * @method \Cms\Orm\Page\Query orderDescCmsRouteId()
 * @method \Cms\Orm\Page\Query groupByCmsRouteId()
 * @method \Cms\Orm\Page\Query\Field whereText()
 * @method \Cms\Orm\Page\Query\Field andFieldText()
 * @method \Cms\Orm\Page\Query\Field orFieldText()
 * @method \Cms\Orm\Page\Query orderAscText()
 * @method \Cms\Orm\Page\Query orderDescText()
 * @method \Cms\Orm\Page\Query groupByText()
 * @method \Cms\Orm\Page\Query\Field whereActive()
 * @method \Cms\Orm\Page\Query\Field andFieldActive()
 * @method \Cms\Orm\Page\Query\Field orFieldActive()
 * @method \Cms\Orm\Page\Query orderAscActive()
 * @method \Cms\Orm\Page\Query orderDescActive()
 * @method \Cms\Orm\Page\Query groupByActive()
 * @method \Cms\Orm\Page\Query\Field whereCmsAuthId()
 * @method \Cms\Orm\Page\Query\Field andFieldCmsAuthId()
 * @method \Cms\Orm\Page\Query\Field orFieldCmsAuthId()
 * @method \Cms\Orm\Page\Query orderAscCmsAuthId()
 * @method \Cms\Orm\Page\Query orderDescCmsAuthId()
 * @method \Cms\Orm\Page\Query groupByCmsAuthId()
 * @method \Cms\Orm\Page\Query\Field whereDateAdd()
 * @method \Cms\Orm\Page\Query\Field andFieldDateAdd()
 * @method \Cms\Orm\Page\Query\Field orFieldDateAdd()
 * @method \Cms\Orm\Page\Query orderAscDateAdd()
 * @method \Cms\Orm\Page\Query orderDescDateAdd()
 * @method \Cms\Orm\Page\Query groupByDateAdd()
 * @method \Cms\Orm\Page\Query\Field whereDateModify()
 * @method \Cms\Orm\Page\Query\Field andFieldDateModify()
 * @method \Cms\Orm\Page\Query\Field orFieldDateModify()
 * @method \Cms\Orm\Page\Query orderAscDateModify()
 * @method \Cms\Orm\Page\Query orderDescDateModify()
 * @method \Cms\Orm\Page\Query groupByDateModify()
 * @method \Cms\Orm\Page\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Page\Query\Join joinLeft($tableName, $targetTableName = null)
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
