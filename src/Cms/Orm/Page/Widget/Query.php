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
 * @method \Cms\Orm\Page\Widget\QueryField whereId()
 * @method \Cms\Orm\Page\Widget\QueryField andFieldId()
 * @method \Cms\Orm\Page\Widget\QueryField orFieldId()
 * @method \Cms\Orm\Page\Widget\Query orderAscId()
 * @method \Cms\Orm\Page\Widget\Query orderDescId()
 * @method \Cms\Orm\Page\Widget\Query groupById()
 * @method \Cms\Orm\Page\Widget\QueryField whereName()
 * @method \Cms\Orm\Page\Widget\QueryField andFieldName()
 * @method \Cms\Orm\Page\Widget\QueryField orFieldName()
 * @method \Cms\Orm\Page\Widget\Query orderAscName()
 * @method \Cms\Orm\Page\Widget\Query orderDescName()
 * @method \Cms\Orm\Page\Widget\Query groupByName()
 * @method \Cms\Orm\Page\Widget\QueryField whereModule()
 * @method \Cms\Orm\Page\Widget\QueryField andFieldModule()
 * @method \Cms\Orm\Page\Widget\QueryField orFieldModule()
 * @method \Cms\Orm\Page\Widget\Query orderAscModule()
 * @method \Cms\Orm\Page\Widget\Query orderDescModule()
 * @method \Cms\Orm\Page\Widget\Query groupByModule()
 * @method \Cms\Orm\Page\Widget\QueryField whereController()
 * @method \Cms\Orm\Page\Widget\QueryField andFieldController()
 * @method \Cms\Orm\Page\Widget\QueryField orFieldController()
 * @method \Cms\Orm\Page\Widget\Query orderAscController()
 * @method \Cms\Orm\Page\Widget\Query orderDescController()
 * @method \Cms\Orm\Page\Widget\Query groupByController()
 * @method \Cms\Orm\Page\Widget\QueryField whereAction()
 * @method \Cms\Orm\Page\Widget\QueryField andFieldAction()
 * @method \Cms\Orm\Page\Widget\QueryField orFieldAction()
 * @method \Cms\Orm\Page\Widget\Query orderAscAction()
 * @method \Cms\Orm\Page\Widget\Query orderDescAction()
 * @method \Cms\Orm\Page\Widget\Query groupByAction()
 * @method \Cms\Orm\Page\Widget\QueryField whereParams()
 * @method \Cms\Orm\Page\Widget\QueryField andFieldParams()
 * @method \Cms\Orm\Page\Widget\QueryField orFieldParams()
 * @method \Cms\Orm\Page\Widget\Query orderAscParams()
 * @method \Cms\Orm\Page\Widget\Query orderDescParams()
 * @method \Cms\Orm\Page\Widget\Query groupByParams()
 * @method \Cms\Orm\Page\Widget\QueryField whereActive()
 * @method \Cms\Orm\Page\Widget\QueryField andFieldActive()
 * @method \Cms\Orm\Page\Widget\QueryField orFieldActive()
 * @method \Cms\Orm\Page\Widget\Query orderAscActive()
 * @method \Cms\Orm\Page\Widget\Query orderDescActive()
 * @method \Cms\Orm\Page\Widget\Query groupByActive()
 * @method \Cms\Orm\Page\Widget\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\Widget\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\Widget\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Page\Widget\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Page\Widget\QueryJoin joinLeft($tableName, $targetTableName = null)
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
