<?php

namespace Cms\Orm\Mail\Definition;

//<editor-fold defaultstate="collapsed" desc="cms_mail_definition Query">
/**
 * @method \Cms\Orm\Mail\Definition\Query limit($limit = null)
 * @method \Cms\Orm\Mail\Definition\Query offset($offset = null)
 * @method \Cms\Orm\Mail\Definition\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Mail\Definition\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Mail\Definition\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Mail\Definition\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Mail\Definition\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Mail\Definition\Query resetOrder()
 * @method \Cms\Orm\Mail\Definition\Query resetWhere()
 * @method \Cms\Orm\Mail\Definition\QueryField whereId()
 * @method \Cms\Orm\Mail\Definition\QueryField andFieldId()
 * @method \Cms\Orm\Mail\Definition\QueryField orFieldId()
 * @method \Cms\Orm\Mail\Definition\Query orderAscId()
 * @method \Cms\Orm\Mail\Definition\Query orderDescId()
 * @method \Cms\Orm\Mail\Definition\Query groupById()
 * @method \Cms\Orm\Mail\Definition\QueryField whereLang()
 * @method \Cms\Orm\Mail\Definition\QueryField andFieldLang()
 * @method \Cms\Orm\Mail\Definition\QueryField orFieldLang()
 * @method \Cms\Orm\Mail\Definition\Query orderAscLang()
 * @method \Cms\Orm\Mail\Definition\Query orderDescLang()
 * @method \Cms\Orm\Mail\Definition\Query groupByLang()
 * @method \Cms\Orm\Mail\Definition\QueryField whereCmsMailServerId()
 * @method \Cms\Orm\Mail\Definition\QueryField andFieldCmsMailServerId()
 * @method \Cms\Orm\Mail\Definition\QueryField orFieldCmsMailServerId()
 * @method \Cms\Orm\Mail\Definition\Query orderAscCmsMailServerId()
 * @method \Cms\Orm\Mail\Definition\Query orderDescCmsMailServerId()
 * @method \Cms\Orm\Mail\Definition\Query groupByCmsMailServerId()
 * @method \Cms\Orm\Mail\Definition\QueryField whereName()
 * @method \Cms\Orm\Mail\Definition\QueryField andFieldName()
 * @method \Cms\Orm\Mail\Definition\QueryField orFieldName()
 * @method \Cms\Orm\Mail\Definition\Query orderAscName()
 * @method \Cms\Orm\Mail\Definition\Query orderDescName()
 * @method \Cms\Orm\Mail\Definition\Query groupByName()
 * @method \Cms\Orm\Mail\Definition\QueryField whereReplyTo()
 * @method \Cms\Orm\Mail\Definition\QueryField andFieldReplyTo()
 * @method \Cms\Orm\Mail\Definition\QueryField orFieldReplyTo()
 * @method \Cms\Orm\Mail\Definition\Query orderAscReplyTo()
 * @method \Cms\Orm\Mail\Definition\Query orderDescReplyTo()
 * @method \Cms\Orm\Mail\Definition\Query groupByReplyTo()
 * @method \Cms\Orm\Mail\Definition\QueryField whereFromName()
 * @method \Cms\Orm\Mail\Definition\QueryField andFieldFromName()
 * @method \Cms\Orm\Mail\Definition\QueryField orFieldFromName()
 * @method \Cms\Orm\Mail\Definition\Query orderAscFromName()
 * @method \Cms\Orm\Mail\Definition\Query orderDescFromName()
 * @method \Cms\Orm\Mail\Definition\Query groupByFromName()
 * @method \Cms\Orm\Mail\Definition\QueryField whereSubject()
 * @method \Cms\Orm\Mail\Definition\QueryField andFieldSubject()
 * @method \Cms\Orm\Mail\Definition\QueryField orFieldSubject()
 * @method \Cms\Orm\Mail\Definition\Query orderAscSubject()
 * @method \Cms\Orm\Mail\Definition\Query orderDescSubject()
 * @method \Cms\Orm\Mail\Definition\Query groupBySubject()
 * @method \Cms\Orm\Mail\Definition\QueryField whereMessage()
 * @method \Cms\Orm\Mail\Definition\QueryField andFieldMessage()
 * @method \Cms\Orm\Mail\Definition\QueryField orFieldMessage()
 * @method \Cms\Orm\Mail\Definition\Query orderAscMessage()
 * @method \Cms\Orm\Mail\Definition\Query orderDescMessage()
 * @method \Cms\Orm\Mail\Definition\Query groupByMessage()
 * @method \Cms\Orm\Mail\Definition\QueryField whereHtml()
 * @method \Cms\Orm\Mail\Definition\QueryField andFieldHtml()
 * @method \Cms\Orm\Mail\Definition\QueryField orFieldHtml()
 * @method \Cms\Orm\Mail\Definition\Query orderAscHtml()
 * @method \Cms\Orm\Mail\Definition\Query orderDescHtml()
 * @method \Cms\Orm\Mail\Definition\Query groupByHtml()
 * @method \Cms\Orm\Mail\Definition\QueryField whereDateAdd()
 * @method \Cms\Orm\Mail\Definition\QueryField andFieldDateAdd()
 * @method \Cms\Orm\Mail\Definition\QueryField orFieldDateAdd()
 * @method \Cms\Orm\Mail\Definition\Query orderAscDateAdd()
 * @method \Cms\Orm\Mail\Definition\Query orderDescDateAdd()
 * @method \Cms\Orm\Mail\Definition\Query groupByDateAdd()
 * @method \Cms\Orm\Mail\Definition\QueryField whereDateModify()
 * @method \Cms\Orm\Mail\Definition\QueryField andFieldDateModify()
 * @method \Cms\Orm\Mail\Definition\QueryField orFieldDateModify()
 * @method \Cms\Orm\Mail\Definition\Query orderAscDateModify()
 * @method \Cms\Orm\Mail\Definition\Query orderDescDateModify()
 * @method \Cms\Orm\Mail\Definition\Query groupByDateModify()
 * @method \Cms\Orm\Mail\Definition\QueryField whereActive()
 * @method \Cms\Orm\Mail\Definition\QueryField andFieldActive()
 * @method \Cms\Orm\Mail\Definition\QueryField orFieldActive()
 * @method \Cms\Orm\Mail\Definition\Query orderAscActive()
 * @method \Cms\Orm\Mail\Definition\Query orderDescActive()
 * @method \Cms\Orm\Mail\Definition\Query groupByActive()
 * @method \Cms\Orm\Mail\Definition\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Mail\Definition\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Mail\Definition\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Mail\Definition\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Mail\Definition\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Mail\Definition\Record[] find()
 * @method \Cms\Orm\Mail\Definition\Record findFirst()
 * @method \Cms\Orm\Mail\Definition\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_mail_definition';

	/**
	 * @return \Cms\Orm\Mail\Definition\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * 
	 * @return \Cms\Orm\Mail\Definition\Query
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
	 * @param string $name
	 * @return \Cms\Orm\Mail\Definition\Query
	 */
	public static function langByName($name) {
		return self::lang()
				->whereName()->equals($name);
	}

}
