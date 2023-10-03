<?php

namespace Cms\Orm;

use Mmi\Orm\Query;

//<editor-fold defaultstate="collapsed" desc="CmsTagQuery">
/**
 * @method CmsTagQuery limit($limit = null)
 * @method CmsTagQuery offset($offset = null)
 * @method CmsTagQuery orderAsc($fieldName, $tableName = null)
 * @method CmsTagQuery orderDesc($fieldName, $tableName = null)
 * @method CmsTagQuery andQuery(Query $query)
 * @method CmsTagQuery whereQuery(Query $query)
 * @method CmsTagQuery orQuery(Query $query)
 * @method CmsTagQuery resetOrder()
 * @method CmsTagQuery resetWhere()
 * @method QueryHelper\CmsTagQueryField whereId()
 * @method QueryHelper\CmsTagQueryField andFieldId()
 * @method QueryHelper\CmsTagQueryField orFieldId()
 * @method CmsTagQuery orderAscId()
 * @method CmsTagQuery orderDescId()
 * @method CmsTagQuery groupById()
 * @method QueryHelper\CmsTagQueryField whereScope()
 * @method QueryHelper\CmsTagQueryField andFieldScope()
 * @method QueryHelper\CmsTagQueryField orFieldScope()
 * @method CmsTagQuery orderAscScope()
 * @method CmsTagQuery orderDescScope()
 * @method CmsTagQuery groupByScope()
 * @method QueryHelper\CmsTagQueryField whereLang()
 * @method QueryHelper\CmsTagQueryField andFieldLang()
 * @method QueryHelper\CmsTagQueryField orFieldLang()
 * @method CmsTagQuery orderAscLang()
 * @method CmsTagQuery orderDescLang()
 * @method CmsTagQuery groupByLang()
 * @method QueryHelper\CmsTagQueryField whereTag()
 * @method QueryHelper\CmsTagQueryField andFieldTag()
 * @method QueryHelper\CmsTagQueryField orFieldTag()
 * @method CmsTagQuery orderAscTag()
 * @method CmsTagQuery orderDescTag()
 * @method CmsTagQuery groupByTag()
 * @method QueryHelper\CmsTagQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsTagQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsTagQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsTagQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsTagQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsTagRecord[] find()
 * @method CmsTagRecord findFirst()
 * @method CmsTagRecord findPk($value)
 */
//</editor-fold>
class CmsTagQuery extends Query
{
    protected $_tableName = 'cms_tag';

    /**
     * Po nazwie
     * @param string $tagName
     * @param string|null $lang
     * @param string|null $scope
     * @return CmsTagQuery
     */
    public static function byName(string $tagName, ?string $lang = null, ?string $scope = null): CmsTagQuery
    {
        return (new self())
            ->whereTag()->equals($tagName)
            ->whereLang()->equals($lang)
            ->whereScope()->equals($scope);
    }
}
