<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsCategoryWidgetCategoryQuery">
/**
 * @method CmsCategoryWidgetCategoryQuery limit($limit = null)
 * @method CmsCategoryWidgetCategoryQuery offset($offset = null)
 * @method CmsCategoryWidgetCategoryQuery orderAsc($fieldName, $tableName = null)
 * @method CmsCategoryWidgetCategoryQuery orderDesc($fieldName, $tableName = null)
 * @method CmsCategoryWidgetCategoryQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsCategoryWidgetCategoryQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsCategoryWidgetCategoryQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsCategoryWidgetCategoryQuery resetOrder()
 * @method CmsCategoryWidgetCategoryQuery resetWhere()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField whereId()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField andFieldId()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField orFieldId()
 * @method CmsCategoryWidgetCategoryQuery orderAscId()
 * @method CmsCategoryWidgetCategoryQuery orderDescId()
 * @method CmsCategoryWidgetCategoryQuery groupById()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField whereCmsCategoryWidgetId()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField andFieldCmsCategoryWidgetId()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField orFieldCmsCategoryWidgetId()
 * @method CmsCategoryWidgetCategoryQuery orderAscCmsCategoryWidgetId()
 * @method CmsCategoryWidgetCategoryQuery orderDescCmsCategoryWidgetId()
 * @method CmsCategoryWidgetCategoryQuery groupByCmsCategoryWidgetId()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField whereCmsCategoryId()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField andFieldCmsCategoryId()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField orFieldCmsCategoryId()
 * @method CmsCategoryWidgetCategoryQuery orderAscCmsCategoryId()
 * @method CmsCategoryWidgetCategoryQuery orderDescCmsCategoryId()
 * @method CmsCategoryWidgetCategoryQuery groupByCmsCategoryId()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField whereConfigJson()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField andFieldConfigJson()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField orFieldConfigJson()
 * @method CmsCategoryWidgetCategoryQuery orderAscConfigJson()
 * @method CmsCategoryWidgetCategoryQuery orderDescConfigJson()
 * @method CmsCategoryWidgetCategoryQuery groupByConfigJson()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField whereOrder()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField andFieldOrder()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField orFieldOrder()
 * @method CmsCategoryWidgetCategoryQuery orderAscOrder()
 * @method CmsCategoryWidgetCategoryQuery orderDescOrder()
 * @method CmsCategoryWidgetCategoryQuery groupByOrder()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField whereActive()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField andFieldActive()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField orFieldActive()
 * @method CmsCategoryWidgetCategoryQuery orderAscActive()
 * @method CmsCategoryWidgetCategoryQuery orderDescActive()
 * @method CmsCategoryWidgetCategoryQuery groupByActive()
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsCategoryWidgetCategoryQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsCategoryWidgetCategoryRecord[] find()
 * @method CmsCategoryWidgetCategoryRecord findFirst()
 * @method CmsCategoryWidgetCategoryRecord findPk($value)
 */
//</editor-fold>
class CmsCategoryWidgetCategoryQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_category_widget_category';
	
	/**
	 * Zapytanie filtrujące state na podstawie użytkowników z daną rolą
	 * @return CmsAuthQuery
	 */
	public function testActive() {	
		//jezeli nie administrator - to tylko aktywne
		if( !\App\Registry::$auth->hasRole('admin') ){
			return $this->whereActive()->equals(1);
		}
		
		//jezeli modul administraotra widzi wszystkie
		if (\Mmi\App\FrontController::getInstance()->getRequest()->module === 'cmsAdmin') {
			return $this->whereActive()->equals([0,1,2]);
		}
		
		//jezeli front, widzi tylko aktywne/robocze
		return $this->whereActive()->equals([1,2]);
	}

}