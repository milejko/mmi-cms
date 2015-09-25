<?php

namespace Cms\Orm\Text;

//<editor-fold defaultstate="collapsed" desc="cms_text Query">
/**
 * @method \Cms\Orm\Text\Query limit($limit = null)
 * @method \Cms\Orm\Text\Query offset($offset = null)
 * @method \Cms\Orm\Text\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Text\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Text\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Text\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Text\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Text\Query resetOrder()
 * @method \Cms\Orm\Text\Query resetWhere()
 * @method \Cms\Orm\Text\QueryField whereId()
 * @method \Cms\Orm\Text\QueryField andFieldId()
 * @method \Cms\Orm\Text\QueryField orFieldId()
 * @method \Cms\Orm\Text\Query orderAscId()
 * @method \Cms\Orm\Text\Query orderDescId()
 * @method \Cms\Orm\Text\Query groupById()
 * @method \Cms\Orm\Text\QueryField whereLang()
 * @method \Cms\Orm\Text\QueryField andFieldLang()
 * @method \Cms\Orm\Text\QueryField orFieldLang()
 * @method \Cms\Orm\Text\Query orderAscLang()
 * @method \Cms\Orm\Text\Query orderDescLang()
 * @method \Cms\Orm\Text\Query groupByLang()
 * @method \Cms\Orm\Text\QueryField whereKey()
 * @method \Cms\Orm\Text\QueryField andFieldKey()
 * @method \Cms\Orm\Text\QueryField orFieldKey()
 * @method \Cms\Orm\Text\Query orderAscKey()
 * @method \Cms\Orm\Text\Query orderDescKey()
 * @method \Cms\Orm\Text\Query groupByKey()
 * @method \Cms\Orm\Text\QueryField whereContent()
 * @method \Cms\Orm\Text\QueryField andFieldContent()
 * @method \Cms\Orm\Text\QueryField orFieldContent()
 * @method \Cms\Orm\Text\Query orderAscContent()
 * @method \Cms\Orm\Text\Query orderDescContent()
 * @method \Cms\Orm\Text\Query groupByContent()
 * @method \Cms\Orm\Text\QueryField whereDateModify()
 * @method \Cms\Orm\Text\QueryField andFieldDateModify()
 * @method \Cms\Orm\Text\QueryField orFieldDateModify()
 * @method \Cms\Orm\Text\Query orderAscDateModify()
 * @method \Cms\Orm\Text\Query orderDescDateModify()
 * @method \Cms\Orm\Text\Query groupByDateModify()
 * @method \Cms\Orm\Text\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Text\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Text\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Text\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Text\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Text\Record[] find()
 * @method \Cms\Orm\Text\Record findFirst()
 * @method \Cms\Orm\Text\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_text';

	/**
	 * @return \Cms\Orm\Text\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * Zapytanie po langu z requesta
	 * @return \Cms\Orm\Text\Query
	 */
	public static function lang() {
		if (!\Mmi\App\FrontController::getInstance()->getRequest()->lang) {
			return self::factory();
		}
		return self::factory()
				->whereLang()->equals(\Mmi\App\FrontController::getInstance()->getRequest()->lang)
				->orFieldLang()->equals(null)
				->orderDescLang();
	}

	/**
	 * 
	 * @param string $lang
	 * @return \Cms\Orm\Text\Query
	 */
	public static function byLang($lang) {
		return self::factory()
				->whereLang()->equals($lang);
	}

	/**
	 * 
	 * @param string $key
	 * @param string $lang
	 * @return \Cms\Orm\Text\Query
	 */
	public static function byKeyLang($key, $lang) {
		return self::byLang($lang)
				->andFieldKey()->equals($key);
	}

}
