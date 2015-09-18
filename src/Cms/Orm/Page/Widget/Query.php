<?php

namespace Cms\Orm\Page\Widget;

//<editor-fold defaultstate="collapsed" desc="cms_page_widget Query">
/**
 * @method \Cms\Orm\Page\Widget\Query limit($limit = null)
 * @method \Cms\Orm\Page\Widget\Query offset($offset = null)
 * @method \Cms\Orm\Page\Widget\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\Widget\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\Widget\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Page\Widget\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Page\Widget\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Page\Widget\Query resetOrder()
 * @method \Cms\Orm\Page\Widget\Query resetWhere()
 * @method \Cms\Orm\Page\Widget\Query\Field whereId()
 * @method \Cms\Orm\Page\Widget\Query\Field andFieldId()
 * @method \Cms\Orm\Page\Widget\Query\Field orFieldId()
 * @method \Cms\Orm\Page\Widget\Query orderAscId()
 * @method \Cms\Orm\Page\Widget\Query orderDescId()
 * @method \Cms\Orm\Page\Widget\Query groupById()
 * @method \Cms\Orm\Page\Widget\Query\Field whereName()
 * @method \Cms\Orm\Page\Widget\Query\Field andFieldName()
 * @method \Cms\Orm\Page\Widget\Query\Field orFieldName()
 * @method \Cms\Orm\Page\Widget\Query orderAscName()
 * @method \Cms\Orm\Page\Widget\Query orderDescName()
 * @method \Cms\Orm\Page\Widget\Query groupByName()
 * @method \Cms\Orm\Page\Widget\Query\Field whereModule()
 * @method \Cms\Orm\Page\Widget\Query\Field andFieldModule()
 * @method \Cms\Orm\Page\Widget\Query\Field orFieldModule()
 * @method \Cms\Orm\Page\Widget\Query orderAscModule()
 * @method \Cms\Orm\Page\Widget\Query orderDescModule()
 * @method \Cms\Orm\Page\Widget\Query groupByModule()
 * @method \Cms\Orm\Page\Widget\Query\Field whereController()
 * @method \Cms\Orm\Page\Widget\Query\Field andFieldController()
 * @method \Cms\Orm\Page\Widget\Query\Field orFieldController()
 * @method \Cms\Orm\Page\Widget\Query orderAscController()
 * @method \Cms\Orm\Page\Widget\Query orderDescController()
 * @method \Cms\Orm\Page\Widget\Query groupByController()
 * @method \Cms\Orm\Page\Widget\Query\Field whereAction()
 * @method \Cms\Orm\Page\Widget\Query\Field andFieldAction()
 * @method \Cms\Orm\Page\Widget\Query\Field orFieldAction()
 * @method \Cms\Orm\Page\Widget\Query orderAscAction()
 * @method \Cms\Orm\Page\Widget\Query orderDescAction()
 * @method \Cms\Orm\Page\Widget\Query groupByAction()
 * @method \Cms\Orm\Page\Widget\Query\Field whereParams()
 * @method \Cms\Orm\Page\Widget\Query\Field andFieldParams()
 * @method \Cms\Orm\Page\Widget\Query\Field orFieldParams()
 * @method \Cms\Orm\Page\Widget\Query orderAscParams()
 * @method \Cms\Orm\Page\Widget\Query orderDescParams()
 * @method \Cms\Orm\Page\Widget\Query groupByParams()
 * @method \Cms\Orm\Page\Widget\Query\Field whereActive()
 * @method \Cms\Orm\Page\Widget\Query\Field andFieldActive()
 * @method \Cms\Orm\Page\Widget\Query\Field orFieldActive()
 * @method \Cms\Orm\Page\Widget\Query orderAscActive()
 * @method \Cms\Orm\Page\Widget\Query orderDescActive()
 * @method \Cms\Orm\Page\Widget\Query groupByActive()
 * @method \Cms\Orm\Page\Widget\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\Widget\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\Widget\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\Widget\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Page\Widget\Query\Join joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Page\Widget\Record[] find()
 * @method \Cms\Orm\Page\Widget\Record findFirst()
 * @method \Cms\Orm\Page\Widget\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_page_widget';

	/**
	 * @return \Cms\Orm\Page\Widget\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * 
	 * @return \Cms\Orm\Page\Widget\Query
	 */
	public static function active() {
		return \Cms\Orm\Page\Widget\Query::factory()
				->whereActive()->equals(true);
	}

}
