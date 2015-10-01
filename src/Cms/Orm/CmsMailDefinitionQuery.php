<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsMailDefinitionQuery">
/**
 * @method CmsMailDefinitionQuery limit($limit = null)
 * @method CmsMailDefinitionQuery offset($offset = null)
 * @method CmsMailDefinitionQuery orderAsc($fieldName, $tableName = null)
 * @method CmsMailDefinitionQuery orderDesc($fieldName, $tableName = null)
 * @method CmsMailDefinitionQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsMailDefinitionQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsMailDefinitionQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsMailDefinitionQuery resetOrder()
 * @method CmsMailDefinitionQuery resetWhere()
 * @method QueryHelper\CmsMailDefinitionQueryField whereId()
 * @method QueryHelper\CmsMailDefinitionQueryField andFieldId()
 * @method QueryHelper\CmsMailDefinitionQueryField orFieldId()
 * @method CmsMailDefinitionQuery orderAscId()
 * @method CmsMailDefinitionQuery orderDescId()
 * @method CmsMailDefinitionQuery groupById()
 * @method QueryHelper\CmsMailDefinitionQueryField whereLang()
 * @method QueryHelper\CmsMailDefinitionQueryField andFieldLang()
 * @method QueryHelper\CmsMailDefinitionQueryField orFieldLang()
 * @method CmsMailDefinitionQuery orderAscLang()
 * @method CmsMailDefinitionQuery orderDescLang()
 * @method CmsMailDefinitionQuery groupByLang()
 * @method QueryHelper\CmsMailDefinitionQueryField whereCmsMailServerId()
 * @method QueryHelper\CmsMailDefinitionQueryField andFieldCmsMailServerId()
 * @method QueryHelper\CmsMailDefinitionQueryField orFieldCmsMailServerId()
 * @method CmsMailDefinitionQuery orderAscCmsMailServerId()
 * @method CmsMailDefinitionQuery orderDescCmsMailServerId()
 * @method CmsMailDefinitionQuery groupByCmsMailServerId()
 * @method QueryHelper\CmsMailDefinitionQueryField whereName()
 * @method QueryHelper\CmsMailDefinitionQueryField andFieldName()
 * @method QueryHelper\CmsMailDefinitionQueryField orFieldName()
 * @method CmsMailDefinitionQuery orderAscName()
 * @method CmsMailDefinitionQuery orderDescName()
 * @method CmsMailDefinitionQuery groupByName()
 * @method QueryHelper\CmsMailDefinitionQueryField whereReplyTo()
 * @method QueryHelper\CmsMailDefinitionQueryField andFieldReplyTo()
 * @method QueryHelper\CmsMailDefinitionQueryField orFieldReplyTo()
 * @method CmsMailDefinitionQuery orderAscReplyTo()
 * @method CmsMailDefinitionQuery orderDescReplyTo()
 * @method CmsMailDefinitionQuery groupByReplyTo()
 * @method QueryHelper\CmsMailDefinitionQueryField whereFromName()
 * @method QueryHelper\CmsMailDefinitionQueryField andFieldFromName()
 * @method QueryHelper\CmsMailDefinitionQueryField orFieldFromName()
 * @method CmsMailDefinitionQuery orderAscFromName()
 * @method CmsMailDefinitionQuery orderDescFromName()
 * @method CmsMailDefinitionQuery groupByFromName()
 * @method QueryHelper\CmsMailDefinitionQueryField whereSubject()
 * @method QueryHelper\CmsMailDefinitionQueryField andFieldSubject()
 * @method QueryHelper\CmsMailDefinitionQueryField orFieldSubject()
 * @method CmsMailDefinitionQuery orderAscSubject()
 * @method CmsMailDefinitionQuery orderDescSubject()
 * @method CmsMailDefinitionQuery groupBySubject()
 * @method QueryHelper\CmsMailDefinitionQueryField whereMessage()
 * @method QueryHelper\CmsMailDefinitionQueryField andFieldMessage()
 * @method QueryHelper\CmsMailDefinitionQueryField orFieldMessage()
 * @method CmsMailDefinitionQuery orderAscMessage()
 * @method CmsMailDefinitionQuery orderDescMessage()
 * @method CmsMailDefinitionQuery groupByMessage()
 * @method QueryHelper\CmsMailDefinitionQueryField whereHtml()
 * @method QueryHelper\CmsMailDefinitionQueryField andFieldHtml()
 * @method QueryHelper\CmsMailDefinitionQueryField orFieldHtml()
 * @method CmsMailDefinitionQuery orderAscHtml()
 * @method CmsMailDefinitionQuery orderDescHtml()
 * @method CmsMailDefinitionQuery groupByHtml()
 * @method QueryHelper\CmsMailDefinitionQueryField whereDateAdd()
 * @method QueryHelper\CmsMailDefinitionQueryField andFieldDateAdd()
 * @method QueryHelper\CmsMailDefinitionQueryField orFieldDateAdd()
 * @method CmsMailDefinitionQuery orderAscDateAdd()
 * @method CmsMailDefinitionQuery orderDescDateAdd()
 * @method CmsMailDefinitionQuery groupByDateAdd()
 * @method QueryHelper\CmsMailDefinitionQueryField whereDateModify()
 * @method QueryHelper\CmsMailDefinitionQueryField andFieldDateModify()
 * @method QueryHelper\CmsMailDefinitionQueryField orFieldDateModify()
 * @method CmsMailDefinitionQuery orderAscDateModify()
 * @method CmsMailDefinitionQuery orderDescDateModify()
 * @method CmsMailDefinitionQuery groupByDateModify()
 * @method QueryHelper\CmsMailDefinitionQueryField whereActive()
 * @method QueryHelper\CmsMailDefinitionQueryField andFieldActive()
 * @method QueryHelper\CmsMailDefinitionQueryField orFieldActive()
 * @method CmsMailDefinitionQuery orderAscActive()
 * @method CmsMailDefinitionQuery orderDescActive()
 * @method CmsMailDefinitionQuery groupByActive()
 * @method QueryHelper\CmsMailDefinitionQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsMailDefinitionQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsMailDefinitionQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsMailDefinitionQueryJoin join($tableName, $targetTableName = null)
 * @method QueryHelper\CmsMailDefinitionQueryJoin joinLeft($tableName, $targetTableName = null)
 * @method CmsMailDefinitionRecord[] find()
 * @method CmsMailDefinitionRecord findFirst()
 * @method CmsMailDefinitionRecord findPk($value)
 */
//</editor-fold>
class CmsMailDefinitionQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_mail_definition';

	/**
	 * @return CmsMailDefinitionQuery
	 */
	public static function factory($tableName = null) {
		return new self($tableName);
	}

	/**
	 * Definicje zgodne z językiem
	 * @return CmsMailDefinitionQuery
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
	 * Definicję językowe po nazwie
	 * @param string $name
	 * @return CmsMailDefinitionQuery
	 */
	public static function langByName($name) {
		return self::lang()
				->whereName()->equals($name);
	}

}
