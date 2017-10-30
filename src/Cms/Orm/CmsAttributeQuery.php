<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsAttributeQuery">
/**
 * @method CmsAttributeQuery limit($limit = null)
 * @method CmsAttributeQuery offset($offset = null)
 * @method CmsAttributeQuery orderAsc($fieldName, $tableName = null)
 * @method CmsAttributeQuery orderDesc($fieldName, $tableName = null)
 * @method CmsAttributeQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsAttributeQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsAttributeQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsAttributeQuery resetOrder()
 * @method CmsAttributeQuery resetWhere()
 * @method QueryHelper\CmsAttributeQueryField whereId()
 * @method QueryHelper\CmsAttributeQueryField andFieldId()
 * @method QueryHelper\CmsAttributeQueryField orFieldId()
 * @method CmsAttributeQuery orderAscId()
 * @method CmsAttributeQuery orderDescId()
 * @method CmsAttributeQuery groupById()
 * @method QueryHelper\CmsAttributeQueryField whereCmsAttributeTypeId()
 * @method QueryHelper\CmsAttributeQueryField andFieldCmsAttributeTypeId()
 * @method QueryHelper\CmsAttributeQueryField orFieldCmsAttributeTypeId()
 * @method CmsAttributeQuery orderAscCmsAttributeTypeId()
 * @method CmsAttributeQuery orderDescCmsAttributeTypeId()
 * @method CmsAttributeQuery groupByCmsAttributeTypeId()
 * @method QueryHelper\CmsAttributeQueryField whereLang()
 * @method QueryHelper\CmsAttributeQueryField andFieldLang()
 * @method QueryHelper\CmsAttributeQueryField orFieldLang()
 * @method CmsAttributeQuery orderAscLang()
 * @method CmsAttributeQuery orderDescLang()
 * @method CmsAttributeQuery groupByLang()
 * @method QueryHelper\CmsAttributeQueryField whereName()
 * @method QueryHelper\CmsAttributeQueryField andFieldName()
 * @method QueryHelper\CmsAttributeQueryField orFieldName()
 * @method CmsAttributeQuery orderAscName()
 * @method CmsAttributeQuery orderDescName()
 * @method CmsAttributeQuery groupByName()
 * @method QueryHelper\CmsAttributeQueryField whereKey()
 * @method QueryHelper\CmsAttributeQueryField andFieldKey()
 * @method QueryHelper\CmsAttributeQueryField orFieldKey()
 * @method CmsAttributeQuery orderAscKey()
 * @method CmsAttributeQuery orderDescKey()
 * @method CmsAttributeQuery groupByKey()
 * @method QueryHelper\CmsAttributeQueryField whereDescription()
 * @method QueryHelper\CmsAttributeQueryField andFieldDescription()
 * @method QueryHelper\CmsAttributeQueryField orFieldDescription()
 * @method CmsAttributeQuery orderAscDescription()
 * @method CmsAttributeQuery orderDescDescription()
 * @method CmsAttributeQuery groupByDescription()
 * @method QueryHelper\CmsAttributeQueryField whereFieldOptions()
 * @method QueryHelper\CmsAttributeQueryField andFieldFieldOptions()
 * @method QueryHelper\CmsAttributeQueryField orFieldFieldOptions()
 * @method CmsAttributeQuery orderAscFieldOptions()
 * @method CmsAttributeQuery orderDescFieldOptions()
 * @method CmsAttributeQuery groupByFieldOptions()
 * @method QueryHelper\CmsAttributeQueryField whereIndexWeight()
 * @method QueryHelper\CmsAttributeQueryField andFieldIndexWeight()
 * @method QueryHelper\CmsAttributeQueryField orFieldIndexWeight()
 * @method CmsAttributeQuery orderAscIndexWeight()
 * @method CmsAttributeQuery orderDescIndexWeight()
 * @method CmsAttributeQuery groupByIndexWeight()
 * @method QueryHelper\CmsAttributeQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsAttributeQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsAttributeQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsAttributeQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsAttributeQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsAttributeRecord[] find()
 * @method CmsAttributeRecord findFirst()
 * @method CmsAttributeRecord findPk($value)
 */
//</editor-fold>
class CmsAttributeQuery extends \Mmi\Orm\Query
{

    protected $_tableName = 'cms_attribute';

    /**
     * Po kluczu atrybutu
     * @param $key
     * @return CmsAttributeQuery
     */
    public function withTypeByKey($key)
    {
        return $this
            ->join('cms_attribute_type')->on('cms_attribute_type_id')
            ->whereKey()->equals($key);
    }

}
