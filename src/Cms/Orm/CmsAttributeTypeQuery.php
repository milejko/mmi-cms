<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsAttributeTypeQuery">
/**
 * @method CmsAttributeTypeQuery limit($limit = null)
 * @method CmsAttributeTypeQuery offset($offset = null)
 * @method CmsAttributeTypeQuery orderAsc($fieldName, $tableName = null)
 * @method CmsAttributeTypeQuery orderDesc($fieldName, $tableName = null)
 * @method CmsAttributeTypeQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsAttributeTypeQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsAttributeTypeQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsAttributeTypeQuery resetOrder()
 * @method CmsAttributeTypeQuery resetWhere()
 * @method QueryHelper\CmsAttributeTypeQueryField whereId()
 * @method QueryHelper\CmsAttributeTypeQueryField andFieldId()
 * @method QueryHelper\CmsAttributeTypeQueryField orFieldId()
 * @method CmsAttributeTypeQuery orderAscId()
 * @method CmsAttributeTypeQuery orderDescId()
 * @method CmsAttributeTypeQuery groupById()
 * @method QueryHelper\CmsAttributeTypeQueryField whereName()
 * @method QueryHelper\CmsAttributeTypeQueryField andFieldName()
 * @method QueryHelper\CmsAttributeTypeQueryField orFieldName()
 * @method CmsAttributeTypeQuery orderAscName()
 * @method CmsAttributeTypeQuery orderDescName()
 * @method CmsAttributeTypeQuery groupByName()
 * @method QueryHelper\CmsAttributeTypeQueryField whereFieldClass()
 * @method QueryHelper\CmsAttributeTypeQueryField andFieldFieldClass()
 * @method QueryHelper\CmsAttributeTypeQueryField orFieldFieldClass()
 * @method CmsAttributeTypeQuery orderAscFieldClass()
 * @method CmsAttributeTypeQuery orderDescFieldClass()
 * @method CmsAttributeTypeQuery groupByFieldClass()
 * @method QueryHelper\CmsAttributeTypeQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsAttributeTypeQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsAttributeTypeQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsAttributeTypeQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsAttributeTypeQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsAttributeTypeRecord[] find()
 * @method CmsAttributeTypeRecord findFirst()
 * @method CmsAttributeTypeRecord findPk($value)
 */
//</editor-fold>
class CmsAttributeTypeQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_attribute_type';

}