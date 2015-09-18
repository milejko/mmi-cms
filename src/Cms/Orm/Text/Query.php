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
 * @method \Cms\Orm\Text\Query\Field whereId()
 * @method \Cms\Orm\Text\Query\Field andFieldId()
 * @method \Cms\Orm\Text\Query\Field orFieldId()
 * @method \Cms\Orm\Text\Query orderAscId()
 * @method \Cms\Orm\Text\Query orderDescId()
 * @method \Cms\Orm\Text\Query groupById()
 * @method \Cms\Orm\Text\Query\Field whereLang()
 * @method \Cms\Orm\Text\Query\Field andFieldLang()
 * @method \Cms\Orm\Text\Query\Field orFieldLang()
 * @method \Cms\Orm\Text\Query orderAscLang()
 * @method \Cms\Orm\Text\Query orderDescLang()
 * @method \Cms\Orm\Text\Query groupByLang()
 * @method \Cms\Orm\Text\Query\Field whereKey()
 * @method \Cms\Orm\Text\Query\Field andFieldKey()
 * @method \Cms\Orm\Text\Query\Field orFieldKey()
 * @method \Cms\Orm\Text\Query orderAscKey()
 * @method \Cms\Orm\Text\Query orderDescKey()
 * @method \Cms\Orm\Text\Query groupByKey()
 * @method \Cms\Orm\Text\Query\Field whereContent()
 * @method \Cms\Orm\Text\Query\Field andFieldContent()
 * @method \Cms\Orm\Text\Query\Field orFieldContent()
 * @method \Cms\Orm\Text\Query orderAscContent()
 * @method \Cms\Orm\Text\Query orderDescContent()
 * @method \Cms\Orm\Text\Query groupByContent()
 * @method \Cms\Orm\Text\Query\Field whereDateModify()
 * @method \Cms\Orm\Text\Query\Field andFieldDateModify()
 * @method \Cms\Orm\Text\Query\Field orFieldDateModify()
 * @method \Cms\Orm\Text\Query orderAscDateModify()
 * @method \Cms\Orm\Text\Query orderDescDateModify()
 * @method \Cms\Orm\Text\Query groupByDateModify()
 * @method \Cms\Orm\Text\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Text\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Text\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Text\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Text\Query\Join joinLeft($tableName, $targetTableName = null)
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
		if (!\Mmi\Controller\Front::getInstance()->getRequest()->lang) {
			return self::factory();
		}
		return self::factory()
				->whereLang()->equals(\Mmi\Controller\Front::getInstance()->getRequest()->lang)
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
