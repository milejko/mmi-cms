<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsTextQuery">
/**
 * @method CmsTextQuery limit($limit = null)
 * @method CmsTextQuery offset($offset = null)
 * @method CmsTextQuery orderAsc($fieldName, $tableName = null)
 * @method CmsTextQuery orderDesc($fieldName, $tableName = null)
 * @method CmsTextQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsTextQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsTextQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsTextQuery resetOrder()
 * @method CmsTextQuery resetWhere()
 * @method QueryHelper\CmsTextQueryField whereId()
 * @method QueryHelper\CmsTextQueryField andFieldId()
 * @method QueryHelper\CmsTextQueryField orFieldId()
 * @method CmsTextQuery orderAscId()
 * @method CmsTextQuery orderDescId()
 * @method CmsTextQuery groupById()
 * @method QueryHelper\CmsTextQueryField whereLang()
 * @method QueryHelper\CmsTextQueryField andFieldLang()
 * @method QueryHelper\CmsTextQueryField orFieldLang()
 * @method CmsTextQuery orderAscLang()
 * @method CmsTextQuery orderDescLang()
 * @method CmsTextQuery groupByLang()
 * @method QueryHelper\CmsTextQueryField whereKey()
 * @method QueryHelper\CmsTextQueryField andFieldKey()
 * @method QueryHelper\CmsTextQueryField orFieldKey()
 * @method CmsTextQuery orderAscKey()
 * @method CmsTextQuery orderDescKey()
 * @method CmsTextQuery groupByKey()
 * @method QueryHelper\CmsTextQueryField whereContent()
 * @method QueryHelper\CmsTextQueryField andFieldContent()
 * @method QueryHelper\CmsTextQueryField orFieldContent()
 * @method CmsTextQuery orderAscContent()
 * @method CmsTextQuery orderDescContent()
 * @method CmsTextQuery groupByContent()
 * @method QueryHelper\CmsTextQueryField whereDateModify()
 * @method QueryHelper\CmsTextQueryField andFieldDateModify()
 * @method QueryHelper\CmsTextQueryField orFieldDateModify()
 * @method CmsTextQuery orderAscDateModify()
 * @method CmsTextQuery orderDescDateModify()
 * @method CmsTextQuery groupByDateModify()
 * @method QueryHelper\CmsTextQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsTextQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsTextQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsTextQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsTextQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsTextRecord[] find()
 * @method CmsTextRecord findFirst()
 * @method CmsTextRecord findPk($value)
 */
//</editor-fold>
class CmsTextQuery extends \Mmi\Orm\Query
{

    protected $_tableName = 'cms_text';

    /**
     * Zapytanie po langu z requesta
     * @return CmsTextQuery
     */
    public static function lang()
    {
        if (!\Mmi\App\FrontController::getInstance()->getRequest()->lang) {
            return new self;
        }
        return (new self)
                ->whereLang()->equals(\Mmi\App\FrontController::getInstance()->getRequest()->lang)
                ->orFieldLang()->equals(null)
                ->orderDescLang();
    }

    /**
     * 
     * @param string $lang
     * @return CmsTextQuery
     */
    public static function byLang($lang)
    {
        return (new self)
                ->whereLang()->equals($lang);
    }

    /**
     * 
     * @param string $key
     * @param string $lang
     * @return CmsTextQuery
     */
    public static function byKeyLang($key, $lang)
    {
        return self::byLang($lang)
                ->andFieldKey()->equals($key);
    }

}
