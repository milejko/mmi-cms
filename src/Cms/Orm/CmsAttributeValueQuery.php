<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsAttributeValueQuery">
/**
 * @method CmsAttributeValueQuery limit($limit = null)
 * @method CmsAttributeValueQuery offset($offset = null)
 * @method CmsAttributeValueQuery orderAsc($fieldName, $tableName = null)
 * @method CmsAttributeValueQuery orderDesc($fieldName, $tableName = null)
 * @method CmsAttributeValueQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsAttributeValueQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsAttributeValueQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsAttributeValueQuery resetOrder()
 * @method CmsAttributeValueQuery resetWhere()
 * @method QueryHelper\CmsAttributeValueQueryField whereId()
 * @method QueryHelper\CmsAttributeValueQueryField andFieldId()
 * @method QueryHelper\CmsAttributeValueQueryField orFieldId()
 * @method CmsAttributeValueQuery orderAscId()
 * @method CmsAttributeValueQuery orderDescId()
 * @method CmsAttributeValueQuery groupById()
 * @method QueryHelper\CmsAttributeValueQueryField whereCmsAttributeId()
 * @method QueryHelper\CmsAttributeValueQueryField andFieldCmsAttributeId()
 * @method QueryHelper\CmsAttributeValueQueryField orFieldCmsAttributeId()
 * @method CmsAttributeValueQuery orderAscCmsAttributeId()
 * @method CmsAttributeValueQuery orderDescCmsAttributeId()
 * @method CmsAttributeValueQuery groupByCmsAttributeId()
 * @method QueryHelper\CmsAttributeValueQueryField whereValue()
 * @method QueryHelper\CmsAttributeValueQueryField andFieldValue()
 * @method QueryHelper\CmsAttributeValueQueryField orFieldValue()
 * @method CmsAttributeValueQuery orderAscValue()
 * @method CmsAttributeValueQuery orderDescValue()
 * @method CmsAttributeValueQuery groupByValue()
 * @method QueryHelper\CmsAttributeValueQueryField whereLabel()
 * @method QueryHelper\CmsAttributeValueQueryField andFieldLabel()
 * @method QueryHelper\CmsAttributeValueQueryField orFieldLabel()
 * @method CmsAttributeValueQuery orderAscLabel()
 * @method CmsAttributeValueQuery orderDescLabel()
 * @method CmsAttributeValueQuery groupByLabel()
 * @method QueryHelper\CmsAttributeValueQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsAttributeValueQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsAttributeValueQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsAttributeValueQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsAttributeValueQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsAttributeValueRecord[] find()
 * @method CmsAttributeValueRecord findFirst()
 * @method CmsAttributeValueRecord findPk($value)
 */
//</editor-fold>
class CmsAttributeValueQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_attribute_value';
	
	/**
	 * Zapytanie o wartości atrybutu po jego kluczu
	 * @param string $key klucz atrybutu
	 * @return \Cms\Orm\CmsAttributeValueQuery
	 */
	public function byAttributeKey($key) {
		return $this->join('cms_attribute')->on('cms_attribute_id')
				->where('key', 'cms_attribute')->equals($key);
	}

}