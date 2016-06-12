<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsArticleQuery">
/**
 * @method CmsArticleQuery limit($limit = null)
 * @method CmsArticleQuery offset($offset = null)
 * @method CmsArticleQuery orderAsc($fieldName, $tableName = null)
 * @method CmsArticleQuery orderDesc($fieldName, $tableName = null)
 * @method CmsArticleQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsArticleQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsArticleQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsArticleQuery resetOrder()
 * @method CmsArticleQuery resetWhere()
 * @method QueryHelper\CmsArticleQueryField whereId()
 * @method QueryHelper\CmsArticleQueryField andFieldId()
 * @method QueryHelper\CmsArticleQueryField orFieldId()
 * @method CmsArticleQuery orderAscId()
 * @method CmsArticleQuery orderDescId()
 * @method CmsArticleQuery groupById()
 * @method QueryHelper\CmsArticleQueryField whereLang()
 * @method QueryHelper\CmsArticleQueryField andFieldLang()
 * @method QueryHelper\CmsArticleQueryField orFieldLang()
 * @method CmsArticleQuery orderAscLang()
 * @method CmsArticleQuery orderDescLang()
 * @method CmsArticleQuery groupByLang()
 * @method QueryHelper\CmsArticleQueryField whereTitle()
 * @method QueryHelper\CmsArticleQueryField andFieldTitle()
 * @method QueryHelper\CmsArticleQueryField orFieldTitle()
 * @method CmsArticleQuery orderAscTitle()
 * @method CmsArticleQuery orderDescTitle()
 * @method CmsArticleQuery groupByTitle()
 * @method QueryHelper\CmsArticleQueryField whereUri()
 * @method QueryHelper\CmsArticleQueryField andFieldUri()
 * @method QueryHelper\CmsArticleQueryField orFieldUri()
 * @method CmsArticleQuery orderAscUri()
 * @method CmsArticleQuery orderDescUri()
 * @method CmsArticleQuery groupByUri()
 * @method QueryHelper\CmsArticleQueryField whereDateAdd()
 * @method QueryHelper\CmsArticleQueryField andFieldDateAdd()
 * @method QueryHelper\CmsArticleQueryField orFieldDateAdd()
 * @method CmsArticleQuery orderAscDateAdd()
 * @method CmsArticleQuery orderDescDateAdd()
 * @method CmsArticleQuery groupByDateAdd()
 * @method QueryHelper\CmsArticleQueryField whereDateModify()
 * @method QueryHelper\CmsArticleQueryField andFieldDateModify()
 * @method QueryHelper\CmsArticleQueryField orFieldDateModify()
 * @method CmsArticleQuery orderAscDateModify()
 * @method CmsArticleQuery orderDescDateModify()
 * @method CmsArticleQuery groupByDateModify()
 * @method QueryHelper\CmsArticleQueryField whereLead()
 * @method QueryHelper\CmsArticleQueryField andFieldLead()
 * @method QueryHelper\CmsArticleQueryField orFieldLead()
 * @method CmsArticleQuery orderAscLead()
 * @method CmsArticleQuery orderDescLead()
 * @method CmsArticleQuery groupByLead()
 * @method QueryHelper\CmsArticleQueryField whereText()
 * @method QueryHelper\CmsArticleQueryField andFieldText()
 * @method QueryHelper\CmsArticleQueryField orFieldText()
 * @method CmsArticleQuery orderAscText()
 * @method CmsArticleQuery orderDescText()
 * @method CmsArticleQuery groupByText()
 * @method QueryHelper\CmsArticleQueryField whereIndex()
 * @method QueryHelper\CmsArticleQueryField andFieldIndex()
 * @method QueryHelper\CmsArticleQueryField orFieldIndex()
 * @method CmsArticleQuery orderAscIndex()
 * @method CmsArticleQuery orderDescIndex()
 * @method CmsArticleQuery groupByIndex()
 * @method QueryHelper\CmsArticleQueryField whereActive()
 * @method QueryHelper\CmsArticleQueryField andFieldActive()
 * @method QueryHelper\CmsArticleQueryField orFieldActive()
 * @method CmsArticleQuery orderAscActive()
 * @method CmsArticleQuery orderDescActive()
 * @method CmsArticleQuery groupByActive()
 * @method QueryHelper\CmsArticleQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsArticleQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsArticleQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsArticleQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsArticleQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsArticleRecord[] find()
 * @method CmsArticleRecord findFirst()
 * @method CmsArticleRecord findPk($value)
 */
//</editor-fold>
class CmsArticleQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_article';

	/**
	 * Po uri
	 * @param string $uri
	 * @return CmsArticleQuery
	 */
	public static function byUri($uri) {
		return (new self)->whereUri()->equals($uri);
	}

}
