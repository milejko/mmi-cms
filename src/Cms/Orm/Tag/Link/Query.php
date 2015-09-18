<?php

namespace Cms\Orm\Tag\Link;

//<editor-fold defaultstate="collapsed" desc="cms_tag_link Query">
/**
 * @method \Cms\Orm\Tag\Link\Query limit($limit = null)
 * @method \Cms\Orm\Tag\Link\Query offset($offset = null)
 * @method \Cms\Orm\Tag\Link\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Tag\Link\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Tag\Link\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Tag\Link\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Tag\Link\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Tag\Link\Query resetOrder()
 * @method \Cms\Orm\Tag\Link\Query resetWhere()
 * @method \Cms\Orm\Tag\Link\Query\Field whereId()
 * @method \Cms\Orm\Tag\Link\Query\Field andFieldId()
 * @method \Cms\Orm\Tag\Link\Query\Field orFieldId()
 * @method \Cms\Orm\Tag\Link\Query orderAscId()
 * @method \Cms\Orm\Tag\Link\Query orderDescId()
 * @method \Cms\Orm\Tag\Link\Query groupById()
 * @method \Cms\Orm\Tag\Link\Query\Field whereCmsTagId()
 * @method \Cms\Orm\Tag\Link\Query\Field andFieldCmsTagId()
 * @method \Cms\Orm\Tag\Link\Query\Field orFieldCmsTagId()
 * @method \Cms\Orm\Tag\Link\Query orderAscCmsTagId()
 * @method \Cms\Orm\Tag\Link\Query orderDescCmsTagId()
 * @method \Cms\Orm\Tag\Link\Query groupByCmsTagId()
 * @method \Cms\Orm\Tag\Link\Query\Field whereObject()
 * @method \Cms\Orm\Tag\Link\Query\Field andFieldObject()
 * @method \Cms\Orm\Tag\Link\Query\Field orFieldObject()
 * @method \Cms\Orm\Tag\Link\Query orderAscObject()
 * @method \Cms\Orm\Tag\Link\Query orderDescObject()
 * @method \Cms\Orm\Tag\Link\Query groupByObject()
 * @method \Cms\Orm\Tag\Link\Query\Field whereObjectId()
 * @method \Cms\Orm\Tag\Link\Query\Field andFieldObjectId()
 * @method \Cms\Orm\Tag\Link\Query\Field orFieldObjectId()
 * @method \Cms\Orm\Tag\Link\Query orderAscObjectId()
 * @method \Cms\Orm\Tag\Link\Query orderDescObjectId()
 * @method \Cms\Orm\Tag\Link\Query groupByObjectId()
 * @method \Cms\Orm\Tag\Link\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Tag\Link\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Tag\Link\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Tag\Link\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Tag\Link\Query\Join joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Tag\Link\Record[] find()
 * @method \Cms\Orm\Tag\Link\Record findFirst()
 * @method \Cms\Orm\Tag\Link\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_tag_link';

	/**
	 * @return \Cms\Orm\Tag\Link\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * Znajduje tagi
	 * @param string $object
	 * @param integer $objectId
	 * @return \Cms\Orm\Tag\Link\Query
	 */
	public static function tagsByObject($object, $objectId = null) {
		return self::factory()
				->join('cms_tag')->on('cms_tag_id')
				->whereObject()->equals($object)
				->andFieldObjectId()->equals($objectId)
				->orderAsc('tag', 'cms_tag');
	}

}
