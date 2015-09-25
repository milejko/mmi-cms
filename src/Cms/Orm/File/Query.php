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
 * @method \Cms\Orm\File\QueryField whereId()
 * @method \Cms\Orm\File\QueryField andFieldId()
 * @method \Cms\Orm\File\QueryField orFieldId()
 * @method \Cms\Orm\File\Query orderAscId()
 * @method \Cms\Orm\File\Query orderDescId()
 * @method \Cms\Orm\File\Query groupById()
 * @method \Cms\Orm\File\QueryField whereClass()
 * @method \Cms\Orm\File\QueryField andFieldClass()
 * @method \Cms\Orm\File\QueryField orFieldClass()
 * @method \Cms\Orm\File\Query orderAscClass()
 * @method \Cms\Orm\File\Query orderDescClass()
 * @method \Cms\Orm\File\Query groupByClass()
 * @method \Cms\Orm\File\QueryField whereMimeType()
 * @method \Cms\Orm\File\QueryField andFieldMimeType()
 * @method \Cms\Orm\File\QueryField orFieldMimeType()
 * @method \Cms\Orm\File\Query orderAscMimeType()
 * @method \Cms\Orm\File\Query orderDescMimeType()
 * @method \Cms\Orm\File\Query groupByMimeType()
 * @method \Cms\Orm\File\QueryField whereName()
 * @method \Cms\Orm\File\QueryField andFieldName()
 * @method \Cms\Orm\File\QueryField orFieldName()
 * @method \Cms\Orm\File\Query orderAscName()
 * @method \Cms\Orm\File\Query orderDescName()
 * @method \Cms\Orm\File\Query groupByName()
 * @method \Cms\Orm\File\QueryField whereOriginal()
 * @method \Cms\Orm\File\QueryField andFieldOriginal()
 * @method \Cms\Orm\File\QueryField orFieldOriginal()
 * @method \Cms\Orm\File\Query orderAscOriginal()
 * @method \Cms\Orm\File\Query orderDescOriginal()
 * @method \Cms\Orm\File\Query groupByOriginal()
 * @method \Cms\Orm\File\QueryField whereTitle()
 * @method \Cms\Orm\File\QueryField andFieldTitle()
 * @method \Cms\Orm\File\QueryField orFieldTitle()
 * @method \Cms\Orm\File\Query orderAscTitle()
 * @method \Cms\Orm\File\Query orderDescTitle()
 * @method \Cms\Orm\File\Query groupByTitle()
 * @method \Cms\Orm\File\QueryField whereAuthor()
 * @method \Cms\Orm\File\QueryField andFieldAuthor()
 * @method \Cms\Orm\File\QueryField orFieldAuthor()
 * @method \Cms\Orm\File\Query orderAscAuthor()
 * @method \Cms\Orm\File\Query orderDescAuthor()
 * @method \Cms\Orm\File\Query groupByAuthor()
 * @method \Cms\Orm\File\QueryField whereSource()
 * @method \Cms\Orm\File\QueryField andFieldSource()
 * @method \Cms\Orm\File\QueryField orFieldSource()
 * @method \Cms\Orm\File\Query orderAscSource()
 * @method \Cms\Orm\File\Query orderDescSource()
 * @method \Cms\Orm\File\Query groupBySource()
 * @method \Cms\Orm\File\QueryField whereSize()
 * @method \Cms\Orm\File\QueryField andFieldSize()
 * @method \Cms\Orm\File\QueryField orFieldSize()
 * @method \Cms\Orm\File\Query orderAscSize()
 * @method \Cms\Orm\File\Query orderDescSize()
 * @method \Cms\Orm\File\Query groupBySize()
 * @method \Cms\Orm\File\QueryField whereDateAdd()
 * @method \Cms\Orm\File\QueryField andFieldDateAdd()
 * @method \Cms\Orm\File\QueryField orFieldDateAdd()
 * @method \Cms\Orm\File\Query orderAscDateAdd()
 * @method \Cms\Orm\File\Query orderDescDateAdd()
 * @method \Cms\Orm\File\Query groupByDateAdd()
 * @method \Cms\Orm\File\QueryField whereDateModify()
 * @method \Cms\Orm\File\QueryField andFieldDateModify()
 * @method \Cms\Orm\File\QueryField orFieldDateModify()
 * @method \Cms\Orm\File\Query orderAscDateModify()
 * @method \Cms\Orm\File\Query orderDescDateModify()
 * @method \Cms\Orm\File\Query groupByDateModify()
 * @method \Cms\Orm\File\QueryField whereOrder()
 * @method \Cms\Orm\File\QueryField andFieldOrder()
 * @method \Cms\Orm\File\QueryField orFieldOrder()
 * @method \Cms\Orm\File\Query orderAscOrder()
 * @method \Cms\Orm\File\Query orderDescOrder()
 * @method \Cms\Orm\File\Query groupByOrder()
 * @method \Cms\Orm\File\QueryField whereSticky()
 * @method \Cms\Orm\File\QueryField andFieldSticky()
 * @method \Cms\Orm\File\QueryField orFieldSticky()
 * @method \Cms\Orm\File\Query orderAscSticky()
 * @method \Cms\Orm\File\Query orderDescSticky()
 * @method \Cms\Orm\File\Query groupBySticky()
 * @method \Cms\Orm\File\QueryField whereObject()
 * @method \Cms\Orm\File\QueryField andFieldObject()
 * @method \Cms\Orm\File\QueryField orFieldObject()
 * @method \Cms\Orm\File\Query orderAscObject()
 * @method \Cms\Orm\File\Query orderDescObject()
 * @method \Cms\Orm\File\Query groupByObject()
 * @method \Cms\Orm\File\QueryField whereObjectId()
 * @method \Cms\Orm\File\QueryField andFieldObjectId()
 * @method \Cms\Orm\File\QueryField orFieldObjectId()
 * @method \Cms\Orm\File\Query orderAscObjectId()
 * @method \Cms\Orm\File\Query orderDescObjectId()
 * @method \Cms\Orm\File\Query groupByObjectId()
 * @method \Cms\Orm\File\QueryField whereCmsAuthId()
 * @method \Cms\Orm\File\QueryField andFieldCmsAuthId()
 * @method \Cms\Orm\File\QueryField orFieldCmsAuthId()
 * @method \Cms\Orm\File\Query orderAscCmsAuthId()
 * @method \Cms\Orm\File\Query orderDescCmsAuthId()
 * @method \Cms\Orm\File\Query groupByCmsAuthId()
 * @method \Cms\Orm\File\QueryField whereActive()
 * @method \Cms\Orm\File\QueryField andFieldActive()
 * @method \Cms\Orm\File\QueryField orFieldActive()
 * @method \Cms\Orm\File\Query orderAscActive()
 * @method \Cms\Orm\File\Query orderDescActive()
 * @method \Cms\Orm\File\Query groupByActive()
 * @method \Cms\Orm\File\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\File\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\File\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\File\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\File\QueryJoin joinLeft($tableName, $targetTableName = null)
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
