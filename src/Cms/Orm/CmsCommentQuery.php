<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsCommentQuery">
/**
 * @method CmsCommentQuery limit($limit = null)
 * @method CmsCommentQuery offset($offset = null)
 * @method CmsCommentQuery orderAsc($fieldName, $tableName = null)
 * @method CmsCommentQuery orderDesc($fieldName, $tableName = null)
 * @method CmsCommentQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsCommentQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsCommentQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsCommentQuery resetOrder()
 * @method CmsCommentQuery resetWhere()
 * @method QueryHelper\CmsCommentQueryField whereId()
 * @method QueryHelper\CmsCommentQueryField andFieldId()
 * @method QueryHelper\CmsCommentQueryField orFieldId()
 * @method CmsCommentQuery orderAscId()
 * @method CmsCommentQuery orderDescId()
 * @method CmsCommentQuery groupById()
 * @method QueryHelper\CmsCommentQueryField whereCmsAuthId()
 * @method QueryHelper\CmsCommentQueryField andFieldCmsAuthId()
 * @method QueryHelper\CmsCommentQueryField orFieldCmsAuthId()
 * @method CmsCommentQuery orderAscCmsAuthId()
 * @method CmsCommentQuery orderDescCmsAuthId()
 * @method CmsCommentQuery groupByCmsAuthId()
 * @method QueryHelper\CmsCommentQueryField whereParentId()
 * @method QueryHelper\CmsCommentQueryField andFieldParentId()
 * @method QueryHelper\CmsCommentQueryField orFieldParentId()
 * @method CmsCommentQuery orderAscParentId()
 * @method CmsCommentQuery orderDescParentId()
 * @method CmsCommentQuery groupByParentId()
 * @method QueryHelper\CmsCommentQueryField whereDateAdd()
 * @method QueryHelper\CmsCommentQueryField andFieldDateAdd()
 * @method QueryHelper\CmsCommentQueryField orFieldDateAdd()
 * @method CmsCommentQuery orderAscDateAdd()
 * @method CmsCommentQuery orderDescDateAdd()
 * @method CmsCommentQuery groupByDateAdd()
 * @method QueryHelper\CmsCommentQueryField whereTitle()
 * @method QueryHelper\CmsCommentQueryField andFieldTitle()
 * @method QueryHelper\CmsCommentQueryField orFieldTitle()
 * @method CmsCommentQuery orderAscTitle()
 * @method CmsCommentQuery orderDescTitle()
 * @method CmsCommentQuery groupByTitle()
 * @method QueryHelper\CmsCommentQueryField whereText()
 * @method QueryHelper\CmsCommentQueryField andFieldText()
 * @method QueryHelper\CmsCommentQueryField orFieldText()
 * @method CmsCommentQuery orderAscText()
 * @method CmsCommentQuery orderDescText()
 * @method CmsCommentQuery groupByText()
 * @method QueryHelper\CmsCommentQueryField whereSignature()
 * @method QueryHelper\CmsCommentQueryField andFieldSignature()
 * @method QueryHelper\CmsCommentQueryField orFieldSignature()
 * @method CmsCommentQuery orderAscSignature()
 * @method CmsCommentQuery orderDescSignature()
 * @method CmsCommentQuery groupBySignature()
 * @method QueryHelper\CmsCommentQueryField whereIp()
 * @method QueryHelper\CmsCommentQueryField andFieldIp()
 * @method QueryHelper\CmsCommentQueryField orFieldIp()
 * @method CmsCommentQuery orderAscIp()
 * @method CmsCommentQuery orderDescIp()
 * @method CmsCommentQuery groupByIp()
 * @method QueryHelper\CmsCommentQueryField whereStars()
 * @method QueryHelper\CmsCommentQueryField andFieldStars()
 * @method QueryHelper\CmsCommentQueryField orFieldStars()
 * @method CmsCommentQuery orderAscStars()
 * @method CmsCommentQuery orderDescStars()
 * @method CmsCommentQuery groupByStars()
 * @method QueryHelper\CmsCommentQueryField whereObject()
 * @method QueryHelper\CmsCommentQueryField andFieldObject()
 * @method QueryHelper\CmsCommentQueryField orFieldObject()
 * @method CmsCommentQuery orderAscObject()
 * @method CmsCommentQuery orderDescObject()
 * @method CmsCommentQuery groupByObject()
 * @method QueryHelper\CmsCommentQueryField whereObjectId()
 * @method QueryHelper\CmsCommentQueryField andFieldObjectId()
 * @method QueryHelper\CmsCommentQueryField orFieldObjectId()
 * @method CmsCommentQuery orderAscObjectId()
 * @method CmsCommentQuery orderDescObjectId()
 * @method CmsCommentQuery groupByObjectId()
 * @method QueryHelper\CmsCommentQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsCommentQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsCommentQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsCommentQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsCommentQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsCommentRecord[] find()
 * @method CmsCommentRecord findFirst()
 * @method CmsCommentRecord findPk($value)
 */
//</editor-fold>
class CmsCommentQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_comment';

	/**
	 * Zapytanie o komentarze po obiekcie, id obiektu z sortowaniem
	 * @param string $object
	 * @param integer $objectId
	 * @param boolean $descending
	 * @return CmsCommentQuery
	 */
	public static function byObject($object, $objectId, $descending = false) {
		$q = (new self)
				->whereObject()->equals($object)
				->andFieldObjectId()->equals($objectId);
		if ($descending) {
			$q->orderDesc('dateAdd');
		} else {
			$q->orderAsc('dateAdd');
		}
		return $q;
	}

}
