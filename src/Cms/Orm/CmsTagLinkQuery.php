<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsTagLinkQuery">
/**
 * @method CmsTagLinkQuery limit($limit = null)
 * @method CmsTagLinkQuery offset($offset = null)
 * @method CmsTagLinkQuery orderAsc($fieldName, $tableName = null)
 * @method CmsTagLinkQuery orderDesc($fieldName, $tableName = null)
 * @method CmsTagLinkQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsTagLinkQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsTagLinkQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsTagLinkQuery resetOrder()
 * @method CmsTagLinkQuery resetWhere()
 * @method QueryHelper\CmsTagLinkQueryField whereId()
 * @method QueryHelper\CmsTagLinkQueryField andFieldId()
 * @method QueryHelper\CmsTagLinkQueryField orFieldId()
 * @method CmsTagLinkQuery orderAscId()
 * @method CmsTagLinkQuery orderDescId()
 * @method CmsTagLinkQuery groupById()
 * @method QueryHelper\CmsTagLinkQueryField whereCmsTagId()
 * @method QueryHelper\CmsTagLinkQueryField andFieldCmsTagId()
 * @method QueryHelper\CmsTagLinkQueryField orFieldCmsTagId()
 * @method CmsTagLinkQuery orderAscCmsTagId()
 * @method CmsTagLinkQuery orderDescCmsTagId()
 * @method CmsTagLinkQuery groupByCmsTagId()
 * @method QueryHelper\CmsTagLinkQueryField whereObject()
 * @method QueryHelper\CmsTagLinkQueryField andFieldObject()
 * @method QueryHelper\CmsTagLinkQueryField orFieldObject()
 * @method CmsTagLinkQuery orderAscObject()
 * @method CmsTagLinkQuery orderDescObject()
 * @method CmsTagLinkQuery groupByObject()
 * @method QueryHelper\CmsTagLinkQueryField whereObjectId()
 * @method QueryHelper\CmsTagLinkQueryField andFieldObjectId()
 * @method QueryHelper\CmsTagLinkQueryField orFieldObjectId()
 * @method CmsTagLinkQuery orderAscObjectId()
 * @method CmsTagLinkQuery orderDescObjectId()
 * @method CmsTagLinkQuery groupByObjectId()
 * @method QueryHelper\CmsTagLinkQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsTagLinkQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsTagLinkQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsTagLinkQueryJoin join($tableName, $targetTableName = null)
 * @method QueryHelper\CmsTagLinkQueryJoin joinLeft($tableName, $targetTableName = null)
 * @method CmsTagLinkRecord[] find()
 * @method CmsTagLinkRecord findFirst()
 * @method CmsTagLinkRecord findPk($value)
 */
//</editor-fold>
class CmsTagLinkQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_tag_link';

	/**
	 * @return CmsTagLinkQuery
	 */
	public static function factory($tableName = null) {
		return new self($tableName);
	}

	/**
	 * Tagi po obiekcie tagi
	 * @param string $object
	 * @param integer $objectId
	 * @return CmsTagLinkQuery
	 */
	public static function tagsByObject($object, $objectId = null) {
		return self::factory()
				->join('cms_tag')->on('cms_tag_id')
				->whereObject()->equals($object)
				->andFieldObjectId()->equals($objectId)
				->orderAsc('tag', 'cms_tag');
	}

}
