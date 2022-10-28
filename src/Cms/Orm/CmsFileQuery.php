<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsFileQuery">
use Mmi\Orm\OrmException;

/**
 * @method CmsFileQuery limit($limit = null)
 * @method CmsFileQuery offset($offset = null)
 * @method CmsFileQuery orderAsc($fieldName, $tableName = null)
 * @method CmsFileQuery orderDesc($fieldName, $tableName = null)
 * @method CmsFileQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsFileQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsFileQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsFileQuery resetOrder()
 * @method CmsFileQuery resetWhere()
 * @method QueryHelper\CmsFileQueryField whereId()
 * @method QueryHelper\CmsFileQueryField andFieldId()
 * @method QueryHelper\CmsFileQueryField orFieldId()
 * @method CmsFileQuery orderAscId()
 * @method CmsFileQuery orderDescId()
 * @method CmsFileQuery groupById()
 * @method QueryHelper\CmsFileQueryField whereClass()
 * @method QueryHelper\CmsFileQueryField andFieldClass()
 * @method QueryHelper\CmsFileQueryField orFieldClass()
 * @method CmsFileQuery orderAscClass()
 * @method CmsFileQuery orderDescClass()
 * @method CmsFileQuery groupByClass()
 * @method QueryHelper\CmsFileQueryField whereMimeType()
 * @method QueryHelper\CmsFileQueryField andFieldMimeType()
 * @method QueryHelper\CmsFileQueryField orFieldMimeType()
 * @method CmsFileQuery orderAscMimeType()
 * @method CmsFileQuery orderDescMimeType()
 * @method CmsFileQuery groupByMimeType()
 * @method QueryHelper\CmsFileQueryField whereName()
 * @method QueryHelper\CmsFileQueryField andFieldName()
 * @method QueryHelper\CmsFileQueryField orFieldName()
 * @method CmsFileQuery orderAscName()
 * @method CmsFileQuery orderDescName()
 * @method CmsFileQuery groupByName()
 * @method QueryHelper\CmsFileQueryField whereOriginal()
 * @method QueryHelper\CmsFileQueryField andFieldOriginal()
 * @method QueryHelper\CmsFileQueryField orFieldOriginal()
 * @method CmsFileQuery orderAscOriginal()
 * @method CmsFileQuery orderDescOriginal()
 * @method CmsFileQuery groupByOriginal()
 * @method QueryHelper\CmsFileQueryField whereData()
 * @method QueryHelper\CmsFileQueryField andFieldData()
 * @method QueryHelper\CmsFileQueryField orFieldData()
 * @method CmsFileQuery orderAscData()
 * @method CmsFileQuery orderDescData()
 * @method CmsFileQuery groupByData()
 * @method QueryHelper\CmsFileQueryField whereSize()
 * @method QueryHelper\CmsFileQueryField andFieldSize()
 * @method QueryHelper\CmsFileQueryField orFieldSize()
 * @method CmsFileQuery orderAscSize()
 * @method CmsFileQuery orderDescSize()
 * @method CmsFileQuery groupBySize()
 * @method QueryHelper\CmsFileQueryField whereDateAdd()
 * @method QueryHelper\CmsFileQueryField andFieldDateAdd()
 * @method QueryHelper\CmsFileQueryField orFieldDateAdd()
 * @method CmsFileQuery orderAscDateAdd()
 * @method CmsFileQuery orderDescDateAdd()
 * @method CmsFileQuery groupByDateAdd()
 * @method QueryHelper\CmsFileQueryField whereDateModify()
 * @method QueryHelper\CmsFileQueryField andFieldDateModify()
 * @method QueryHelper\CmsFileQueryField orFieldDateModify()
 * @method CmsFileQuery orderAscDateModify()
 * @method CmsFileQuery orderDescDateModify()
 * @method CmsFileQuery groupByDateModify()
 * @method QueryHelper\CmsFileQueryField whereOrder()
 * @method QueryHelper\CmsFileQueryField andFieldOrder()
 * @method QueryHelper\CmsFileQueryField orFieldOrder()
 * @method CmsFileQuery orderAscOrder()
 * @method CmsFileQuery orderDescOrder()
 * @method CmsFileQuery groupByOrder()
 * @method QueryHelper\CmsFileQueryField whereSticky()
 * @method QueryHelper\CmsFileQueryField andFieldSticky()
 * @method QueryHelper\CmsFileQueryField orFieldSticky()
 * @method CmsFileQuery orderAscSticky()
 * @method CmsFileQuery orderDescSticky()
 * @method CmsFileQuery groupBySticky()
 * @method QueryHelper\CmsFileQueryField whereObject()
 * @method QueryHelper\CmsFileQueryField andFieldObject()
 * @method QueryHelper\CmsFileQueryField orFieldObject()
 * @method CmsFileQuery orderAscObject()
 * @method CmsFileQuery orderDescObject()
 * @method CmsFileQuery groupByObject()
 * @method QueryHelper\CmsFileQueryField whereObjectId()
 * @method QueryHelper\CmsFileQueryField andFieldObjectId()
 * @method QueryHelper\CmsFileQueryField orFieldObjectId()
 * @method CmsFileQuery orderAscObjectId()
 * @method CmsFileQuery orderDescObjectId()
 * @method CmsFileQuery groupByObjectId()
 * @method QueryHelper\CmsFileQueryField whereCmsAuthId()
 * @method QueryHelper\CmsFileQueryField andFieldCmsAuthId()
 * @method QueryHelper\CmsFileQueryField orFieldCmsAuthId()
 * @method CmsFileQuery orderAscCmsAuthId()
 * @method CmsFileQuery orderDescCmsAuthId()
 * @method CmsFileQuery groupByCmsAuthId()
 * @method QueryHelper\CmsFileQueryField whereActive()
 * @method QueryHelper\CmsFileQueryField andFieldActive()
 * @method QueryHelper\CmsFileQueryField orFieldActive()
 * @method CmsFileQuery orderAscActive()
 * @method CmsFileQuery orderDescActive()
 * @method CmsFileQuery groupByActive()
 * @method QueryHelper\CmsFileQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsFileQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsFileQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsFileQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsFileQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsFileRecord[] find()
 * @method CmsFileRecord findFirst()
 * @method CmsFileRecord findPk($value)
 */
//</editor-fold>
class CmsFileQuery extends \Mmi\Orm\Query
{
    protected $_tableName = 'cms_file';

    /**
     * Po obiekcie i id
     * @param string $object
     * @param string $objectId
     * @return CmsFileQuery
     */
    public static function byObject($object = null, $objectId = null)
    {
        //zapytanie o pliki po obiektach i id
        return (new self())
            ->whereObject()->equals($object)
            ->andFieldObjectId()->equals($objectId)
            //posortowane po kolejności
            ->orderAscOrder()
            //sortowanie po ID, jeśli ordery są NULL
            ->orderAscId();
    }

    /**
     * Po obiekcie i id
     * @param string $object
     * @param string $objectId
     * @param string $class
     * @return CmsFileQuery
     */
    public static function byObjectAndClass($object = null, $objectId = null, $class = 'image')
    {
        //zapytanie o pliki po obiektach i id
        return (new self())
            ->whereObject()->equals($object)
            ->andFieldObjectId()->equals($objectId)
            ->whereClass()->equals($class)
            //posortowane po kolejności
            ->orderAscOrder()
            //sortowanie po ID, jeśli ordery są NULL
            ->orderAscId();
    }

    /**
     * Zapytanie o obrazy po obiektach i id
     * @param string $object
     * @param string $objectId
     * @return CmsFileQuery
     */
    public static function imagesByObject($object = null, $objectId = null)
    {
        //zapytanie po obiekcie
        return self::byObject($object, $objectId)
            ->whereClass()->equals('image');
    }

    /**
     * Nie obrazy po obiekcie i id
     * @param string $object
     * @param string $objectId
     * @return CmsFileQuery
     */
    public static function notImagesByObject($object = null, $objectId = null)
    {
        //zapytanie po obiekcie i id
        return self::byObject($object, $objectId)
            ->whereClass()->notEquals('image');
    }

    /**
     * @param string $fileName
     * @param string|null $object
     * @param string|null $objectId
     * @return CmsFileQuery
     * @throws OrmException
     */
    public static function byFileName($fileName, $object = null, $objectId = null)
    {
        return (new self())
            ->whereName()->equals($fileName)
            ->andFieldObject()->equals($object)
            ->andFieldObjectId()->equals($objectId);
    }
}
