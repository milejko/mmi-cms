<?php

namespace Cms\Orm\File;

//<editor-fold defaultstate="collapsed" desc="cms_file Query">
/**
 * @method \Cms\Orm\File\Query limit($limit = null)
 * @method \Cms\Orm\File\Query offset($offset = null)
 * @method \Cms\Orm\File\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\File\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\File\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\File\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\File\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\File\Query resetOrder()
 * @method \Cms\Orm\File\Query resetWhere()
 * @method \Cms\Orm\File\Query\Field whereId()
 * @method \Cms\Orm\File\Query\Field andFieldId()
 * @method \Cms\Orm\File\Query\Field orFieldId()
 * @method \Cms\Orm\File\Query orderAscId()
 * @method \Cms\Orm\File\Query orderDescId()
 * @method \Cms\Orm\File\Query groupById()
 * @method \Cms\Orm\File\Query\Field whereClass()
 * @method \Cms\Orm\File\Query\Field andFieldClass()
 * @method \Cms\Orm\File\Query\Field orFieldClass()
 * @method \Cms\Orm\File\Query orderAscClass()
 * @method \Cms\Orm\File\Query orderDescClass()
 * @method \Cms\Orm\File\Query groupByClass()
 * @method \Cms\Orm\File\Query\Field whereMimeType()
 * @method \Cms\Orm\File\Query\Field andFieldMimeType()
 * @method \Cms\Orm\File\Query\Field orFieldMimeType()
 * @method \Cms\Orm\File\Query orderAscMimeType()
 * @method \Cms\Orm\File\Query orderDescMimeType()
 * @method \Cms\Orm\File\Query groupByMimeType()
 * @method \Cms\Orm\File\Query\Field whereName()
 * @method \Cms\Orm\File\Query\Field andFieldName()
 * @method \Cms\Orm\File\Query\Field orFieldName()
 * @method \Cms\Orm\File\Query orderAscName()
 * @method \Cms\Orm\File\Query orderDescName()
 * @method \Cms\Orm\File\Query groupByName()
 * @method \Cms\Orm\File\Query\Field whereOriginal()
 * @method \Cms\Orm\File\Query\Field andFieldOriginal()
 * @method \Cms\Orm\File\Query\Field orFieldOriginal()
 * @method \Cms\Orm\File\Query orderAscOriginal()
 * @method \Cms\Orm\File\Query orderDescOriginal()
 * @method \Cms\Orm\File\Query groupByOriginal()
 * @method \Cms\Orm\File\Query\Field whereTitle()
 * @method \Cms\Orm\File\Query\Field andFieldTitle()
 * @method \Cms\Orm\File\Query\Field orFieldTitle()
 * @method \Cms\Orm\File\Query orderAscTitle()
 * @method \Cms\Orm\File\Query orderDescTitle()
 * @method \Cms\Orm\File\Query groupByTitle()
 * @method \Cms\Orm\File\Query\Field whereAuthor()
 * @method \Cms\Orm\File\Query\Field andFieldAuthor()
 * @method \Cms\Orm\File\Query\Field orFieldAuthor()
 * @method \Cms\Orm\File\Query orderAscAuthor()
 * @method \Cms\Orm\File\Query orderDescAuthor()
 * @method \Cms\Orm\File\Query groupByAuthor()
 * @method \Cms\Orm\File\Query\Field whereSource()
 * @method \Cms\Orm\File\Query\Field andFieldSource()
 * @method \Cms\Orm\File\Query\Field orFieldSource()
 * @method \Cms\Orm\File\Query orderAscSource()
 * @method \Cms\Orm\File\Query orderDescSource()
 * @method \Cms\Orm\File\Query groupBySource()
 * @method \Cms\Orm\File\Query\Field whereSize()
 * @method \Cms\Orm\File\Query\Field andFieldSize()
 * @method \Cms\Orm\File\Query\Field orFieldSize()
 * @method \Cms\Orm\File\Query orderAscSize()
 * @method \Cms\Orm\File\Query orderDescSize()
 * @method \Cms\Orm\File\Query groupBySize()
 * @method \Cms\Orm\File\Query\Field whereDateAdd()
 * @method \Cms\Orm\File\Query\Field andFieldDateAdd()
 * @method \Cms\Orm\File\Query\Field orFieldDateAdd()
 * @method \Cms\Orm\File\Query orderAscDateAdd()
 * @method \Cms\Orm\File\Query orderDescDateAdd()
 * @method \Cms\Orm\File\Query groupByDateAdd()
 * @method \Cms\Orm\File\Query\Field whereDateModify()
 * @method \Cms\Orm\File\Query\Field andFieldDateModify()
 * @method \Cms\Orm\File\Query\Field orFieldDateModify()
 * @method \Cms\Orm\File\Query orderAscDateModify()
 * @method \Cms\Orm\File\Query orderDescDateModify()
 * @method \Cms\Orm\File\Query groupByDateModify()
 * @method \Cms\Orm\File\Query\Field whereOrder()
 * @method \Cms\Orm\File\Query\Field andFieldOrder()
 * @method \Cms\Orm\File\Query\Field orFieldOrder()
 * @method \Cms\Orm\File\Query orderAscOrder()
 * @method \Cms\Orm\File\Query orderDescOrder()
 * @method \Cms\Orm\File\Query groupByOrder()
 * @method \Cms\Orm\File\Query\Field whereSticky()
 * @method \Cms\Orm\File\Query\Field andFieldSticky()
 * @method \Cms\Orm\File\Query\Field orFieldSticky()
 * @method \Cms\Orm\File\Query orderAscSticky()
 * @method \Cms\Orm\File\Query orderDescSticky()
 * @method \Cms\Orm\File\Query groupBySticky()
 * @method \Cms\Orm\File\Query\Field whereObject()
 * @method \Cms\Orm\File\Query\Field andFieldObject()
 * @method \Cms\Orm\File\Query\Field orFieldObject()
 * @method \Cms\Orm\File\Query orderAscObject()
 * @method \Cms\Orm\File\Query orderDescObject()
 * @method \Cms\Orm\File\Query groupByObject()
 * @method \Cms\Orm\File\Query\Field whereObjectId()
 * @method \Cms\Orm\File\Query\Field andFieldObjectId()
 * @method \Cms\Orm\File\Query\Field orFieldObjectId()
 * @method \Cms\Orm\File\Query orderAscObjectId()
 * @method \Cms\Orm\File\Query orderDescObjectId()
 * @method \Cms\Orm\File\Query groupByObjectId()
 * @method \Cms\Orm\File\Query\Field whereCmsAuthId()
 * @method \Cms\Orm\File\Query\Field andFieldCmsAuthId()
 * @method \Cms\Orm\File\Query\Field orFieldCmsAuthId()
 * @method \Cms\Orm\File\Query orderAscCmsAuthId()
 * @method \Cms\Orm\File\Query orderDescCmsAuthId()
 * @method \Cms\Orm\File\Query groupByCmsAuthId()
 * @method \Cms\Orm\File\Query\Field whereActive()
 * @method \Cms\Orm\File\Query\Field andFieldActive()
 * @method \Cms\Orm\File\Query\Field orFieldActive()
 * @method \Cms\Orm\File\Query orderAscActive()
 * @method \Cms\Orm\File\Query orderDescActive()
 * @method \Cms\Orm\File\Query groupByActive()
 * @method \Cms\Orm\File\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\File\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\File\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\File\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\File\Query\Join joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\File\Record[] find()
 * @method \Cms\Orm\File\Record findFirst()
 * @method \Cms\Orm\File\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_file';

	/**
	 * @return \Cms\Orm\File\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * Po obiekcie i id
	 * @param string $object
	 * @param string $objectId
	 * @return \Cms\Orm\File\Query
	 */
	public static function byObject($object = null, $objectId = null) {
		//zapytanie o pliki po obiektach i id
		return self::factory()
				->whereObject()->equals($object)
				->andFieldObjectId()->equals($objectId)
				//posortowane po kolejności
				->orderAscOrder();
	}

	/**
	 * Zapytanie o obrazy po obiektach i id
	 * @param string $object
	 * @param string $objectId
	 * @return \Cms\Orm\File\Query
	 */
	public static function imagesByObject($object = null, $objectId = null) {
		//zapytanie po obiekcie
		return self::byObject($object, $objectId)
				->whereClass()->equals('image');
	}

	/**
	 * Przyklejone po obiekcie i id
	 * @param string $object
	 * @param string $objectId
	 * @param string $class klasa
	 * @return \Cms\Orm\File\Query
	 */
	public static function stickyByObject($object = null, $objectId = null, $class = null) {
		//zapytanie po obiekcie
		$q = self::byObject($object, $objectId)
				->whereSticky()->equals(1);
		//dodawanie klasy jeśli wyspecyfikowana
		if (null !== $class) {
			$q->andFieldClass()->equals($class);
		}
		return $q;
	}

	/**
	 * Nie obrazy po obiekcie i id
	 * @param string $object
	 * @param string $objectId
	 * @return \Cms\Orm\File\Query
	 */
	public static function notImagesByObject($object = null, $objectId = null) {
		//zapytanie po obiekcie i id
		return self::byObject($object, $objectId)
				->whereClass()->notEquals('image');
	}

}
