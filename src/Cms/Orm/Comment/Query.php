<?php

namespace Cms\Orm\Comment;

//<editor-fold defaultstate="collapsed" desc="cms_comment Query">
/**
 * @method \Cms\Orm\Comment\Query limit($limit = null)
 * @method \Cms\Orm\Comment\Query offset($offset = null)
 * @method \Cms\Orm\Comment\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Comment\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Comment\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Comment\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Comment\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Comment\Query resetOrder()
 * @method \Cms\Orm\Comment\Query resetWhere()
 * @method \Cms\Orm\Comment\QueryField whereId()
 * @method \Cms\Orm\Comment\QueryField andFieldId()
 * @method \Cms\Orm\Comment\QueryField orFieldId()
 * @method \Cms\Orm\Comment\Query orderAscId()
 * @method \Cms\Orm\Comment\Query orderDescId()
 * @method \Cms\Orm\Comment\Query groupById()
 * @method \Cms\Orm\Comment\QueryField whereCmsAuthId()
 * @method \Cms\Orm\Comment\QueryField andFieldCmsAuthId()
 * @method \Cms\Orm\Comment\QueryField orFieldCmsAuthId()
 * @method \Cms\Orm\Comment\Query orderAscCmsAuthId()
 * @method \Cms\Orm\Comment\Query orderDescCmsAuthId()
 * @method \Cms\Orm\Comment\Query groupByCmsAuthId()
 * @method \Cms\Orm\Comment\QueryField whereParentId()
 * @method \Cms\Orm\Comment\QueryField andFieldParentId()
 * @method \Cms\Orm\Comment\QueryField orFieldParentId()
 * @method \Cms\Orm\Comment\Query orderAscParentId()
 * @method \Cms\Orm\Comment\Query orderDescParentId()
 * @method \Cms\Orm\Comment\Query groupByParentId()
 * @method \Cms\Orm\Comment\QueryField whereDateAdd()
 * @method \Cms\Orm\Comment\QueryField andFieldDateAdd()
 * @method \Cms\Orm\Comment\QueryField orFieldDateAdd()
 * @method \Cms\Orm\Comment\Query orderAscDateAdd()
 * @method \Cms\Orm\Comment\Query orderDescDateAdd()
 * @method \Cms\Orm\Comment\Query groupByDateAdd()
 * @method \Cms\Orm\Comment\QueryField whereTitle()
 * @method \Cms\Orm\Comment\QueryField andFieldTitle()
 * @method \Cms\Orm\Comment\QueryField orFieldTitle()
 * @method \Cms\Orm\Comment\Query orderAscTitle()
 * @method \Cms\Orm\Comment\Query orderDescTitle()
 * @method \Cms\Orm\Comment\Query groupByTitle()
 * @method \Cms\Orm\Comment\QueryField whereText()
 * @method \Cms\Orm\Comment\QueryField andFieldText()
 * @method \Cms\Orm\Comment\QueryField orFieldText()
 * @method \Cms\Orm\Comment\Query orderAscText()
 * @method \Cms\Orm\Comment\Query orderDescText()
 * @method \Cms\Orm\Comment\Query groupByText()
 * @method \Cms\Orm\Comment\QueryField whereSignature()
 * @method \Cms\Orm\Comment\QueryField andFieldSignature()
 * @method \Cms\Orm\Comment\QueryField orFieldSignature()
 * @method \Cms\Orm\Comment\Query orderAscSignature()
 * @method \Cms\Orm\Comment\Query orderDescSignature()
 * @method \Cms\Orm\Comment\Query groupBySignature()
 * @method \Cms\Orm\Comment\QueryField whereIp()
 * @method \Cms\Orm\Comment\QueryField andFieldIp()
 * @method \Cms\Orm\Comment\QueryField orFieldIp()
 * @method \Cms\Orm\Comment\Query orderAscIp()
 * @method \Cms\Orm\Comment\Query orderDescIp()
 * @method \Cms\Orm\Comment\Query groupByIp()
 * @method \Cms\Orm\Comment\QueryField whereStars()
 * @method \Cms\Orm\Comment\QueryField andFieldStars()
 * @method \Cms\Orm\Comment\QueryField orFieldStars()
 * @method \Cms\Orm\Comment\Query orderAscStars()
 * @method \Cms\Orm\Comment\Query orderDescStars()
 * @method \Cms\Orm\Comment\Query groupByStars()
 * @method \Cms\Orm\Comment\QueryField whereObject()
 * @method \Cms\Orm\Comment\QueryField andFieldObject()
 * @method \Cms\Orm\Comment\QueryField orFieldObject()
 * @method \Cms\Orm\Comment\Query orderAscObject()
 * @method \Cms\Orm\Comment\Query orderDescObject()
 * @method \Cms\Orm\Comment\Query groupByObject()
 * @method \Cms\Orm\Comment\QueryField whereObjectId()
 * @method \Cms\Orm\Comment\QueryField andFieldObjectId()
 * @method \Cms\Orm\Comment\QueryField orFieldObjectId()
 * @method \Cms\Orm\Comment\Query orderAscObjectId()
 * @method \Cms\Orm\Comment\Query orderDescObjectId()
 * @method \Cms\Orm\Comment\Query groupByObjectId()
 * @method \Cms\Orm\Comment\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Comment\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Comment\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Comment\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Comment\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Comment\Record[] find()
 * @method \Cms\Orm\Comment\Record findFirst()
 * @method \Cms\Orm\Comment\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_comment';

	/**
	 * @return \Cms\Orm\Comment\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * Zapytanie o komentarze po obiekcie, id obiektu z sortowaniem
	 * @param string $object
	 * @param integer $objectId
	 * @param boolean $descending
	 * @return \Cms\Orm\Comment\Query
	 */
	public static function byObject($object, $objectId, $descending = false) {
		$q = self::factory()
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
