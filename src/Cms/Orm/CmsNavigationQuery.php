<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsNavigationQuery">
/**
 * @method CmsNavigationQuery limit($limit = null)
 * @method CmsNavigationQuery offset($offset = null)
 * @method CmsNavigationQuery orderAsc($fieldName, $tableName = null)
 * @method CmsNavigationQuery orderDesc($fieldName, $tableName = null)
 * @method CmsNavigationQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsNavigationQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsNavigationQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsNavigationQuery resetOrder()
 * @method CmsNavigationQuery resetWhere()
 * @method QueryHelper\CmsNavigationQueryField whereId()
 * @method QueryHelper\CmsNavigationQueryField andFieldId()
 * @method QueryHelper\CmsNavigationQueryField orFieldId()
 * @method CmsNavigationQuery orderAscId()
 * @method CmsNavigationQuery orderDescId()
 * @method CmsNavigationQuery groupById()
 * @method QueryHelper\CmsNavigationQueryField whereLang()
 * @method QueryHelper\CmsNavigationQueryField andFieldLang()
 * @method QueryHelper\CmsNavigationQueryField orFieldLang()
 * @method CmsNavigationQuery orderAscLang()
 * @method CmsNavigationQuery orderDescLang()
 * @method CmsNavigationQuery groupByLang()
 * @method QueryHelper\CmsNavigationQueryField whereParentId()
 * @method QueryHelper\CmsNavigationQueryField andFieldParentId()
 * @method QueryHelper\CmsNavigationQueryField orFieldParentId()
 * @method CmsNavigationQuery orderAscParentId()
 * @method CmsNavigationQuery orderDescParentId()
 * @method CmsNavigationQuery groupByParentId()
 * @method QueryHelper\CmsNavigationQueryField whereOrder()
 * @method QueryHelper\CmsNavigationQueryField andFieldOrder()
 * @method QueryHelper\CmsNavigationQueryField orFieldOrder()
 * @method CmsNavigationQuery orderAscOrder()
 * @method CmsNavigationQuery orderDescOrder()
 * @method CmsNavigationQuery groupByOrder()
 * @method QueryHelper\CmsNavigationQueryField whereModule()
 * @method QueryHelper\CmsNavigationQueryField andFieldModule()
 * @method QueryHelper\CmsNavigationQueryField orFieldModule()
 * @method CmsNavigationQuery orderAscModule()
 * @method CmsNavigationQuery orderDescModule()
 * @method CmsNavigationQuery groupByModule()
 * @method QueryHelper\CmsNavigationQueryField whereController()
 * @method QueryHelper\CmsNavigationQueryField andFieldController()
 * @method QueryHelper\CmsNavigationQueryField orFieldController()
 * @method CmsNavigationQuery orderAscController()
 * @method CmsNavigationQuery orderDescController()
 * @method CmsNavigationQuery groupByController()
 * @method QueryHelper\CmsNavigationQueryField whereAction()
 * @method QueryHelper\CmsNavigationQueryField andFieldAction()
 * @method QueryHelper\CmsNavigationQueryField orFieldAction()
 * @method CmsNavigationQuery orderAscAction()
 * @method CmsNavigationQuery orderDescAction()
 * @method CmsNavigationQuery groupByAction()
 * @method QueryHelper\CmsNavigationQueryField whereParams()
 * @method QueryHelper\CmsNavigationQueryField andFieldParams()
 * @method QueryHelper\CmsNavigationQueryField orFieldParams()
 * @method CmsNavigationQuery orderAscParams()
 * @method CmsNavigationQuery orderDescParams()
 * @method CmsNavigationQuery groupByParams()
 * @method QueryHelper\CmsNavigationQueryField whereLabel()
 * @method QueryHelper\CmsNavigationQueryField andFieldLabel()
 * @method QueryHelper\CmsNavigationQueryField orFieldLabel()
 * @method CmsNavigationQuery orderAscLabel()
 * @method CmsNavigationQuery orderDescLabel()
 * @method CmsNavigationQuery groupByLabel()
 * @method QueryHelper\CmsNavigationQueryField whereTitle()
 * @method QueryHelper\CmsNavigationQueryField andFieldTitle()
 * @method QueryHelper\CmsNavigationQueryField orFieldTitle()
 * @method CmsNavigationQuery orderAscTitle()
 * @method CmsNavigationQuery orderDescTitle()
 * @method CmsNavigationQuery groupByTitle()
 * @method QueryHelper\CmsNavigationQueryField whereKeywords()
 * @method QueryHelper\CmsNavigationQueryField andFieldKeywords()
 * @method QueryHelper\CmsNavigationQueryField orFieldKeywords()
 * @method CmsNavigationQuery orderAscKeywords()
 * @method CmsNavigationQuery orderDescKeywords()
 * @method CmsNavigationQuery groupByKeywords()
 * @method QueryHelper\CmsNavigationQueryField whereDescription()
 * @method QueryHelper\CmsNavigationQueryField andFieldDescription()
 * @method QueryHelper\CmsNavigationQueryField orFieldDescription()
 * @method CmsNavigationQuery orderAscDescription()
 * @method CmsNavigationQuery orderDescDescription()
 * @method CmsNavigationQuery groupByDescription()
 * @method QueryHelper\CmsNavigationQueryField whereUri()
 * @method QueryHelper\CmsNavigationQueryField andFieldUri()
 * @method QueryHelper\CmsNavigationQueryField orFieldUri()
 * @method CmsNavigationQuery orderAscUri()
 * @method CmsNavigationQuery orderDescUri()
 * @method CmsNavigationQuery groupByUri()
 * @method QueryHelper\CmsNavigationQueryField whereVisible()
 * @method QueryHelper\CmsNavigationQueryField andFieldVisible()
 * @method QueryHelper\CmsNavigationQueryField orFieldVisible()
 * @method CmsNavigationQuery orderAscVisible()
 * @method CmsNavigationQuery orderDescVisible()
 * @method CmsNavigationQuery groupByVisible()
 * @method QueryHelper\CmsNavigationQueryField whereHttps()
 * @method QueryHelper\CmsNavigationQueryField andFieldHttps()
 * @method QueryHelper\CmsNavigationQueryField orFieldHttps()
 * @method CmsNavigationQuery orderAscHttps()
 * @method CmsNavigationQuery orderDescHttps()
 * @method CmsNavigationQuery groupByHttps()
 * @method QueryHelper\CmsNavigationQueryField whereAbsolute()
 * @method QueryHelper\CmsNavigationQueryField andFieldAbsolute()
 * @method QueryHelper\CmsNavigationQueryField orFieldAbsolute()
 * @method CmsNavigationQuery orderAscAbsolute()
 * @method CmsNavigationQuery orderDescAbsolute()
 * @method CmsNavigationQuery groupByAbsolute()
 * @method QueryHelper\CmsNavigationQueryField whereIndependent()
 * @method QueryHelper\CmsNavigationQueryField andFieldIndependent()
 * @method QueryHelper\CmsNavigationQueryField orFieldIndependent()
 * @method CmsNavigationQuery orderAscIndependent()
 * @method CmsNavigationQuery orderDescIndependent()
 * @method CmsNavigationQuery groupByIndependent()
 * @method QueryHelper\CmsNavigationQueryField whereNofollow()
 * @method QueryHelper\CmsNavigationQueryField andFieldNofollow()
 * @method QueryHelper\CmsNavigationQueryField orFieldNofollow()
 * @method CmsNavigationQuery orderAscNofollow()
 * @method CmsNavigationQuery orderDescNofollow()
 * @method CmsNavigationQuery groupByNofollow()
 * @method QueryHelper\CmsNavigationQueryField whereBlank()
 * @method QueryHelper\CmsNavigationQueryField andFieldBlank()
 * @method QueryHelper\CmsNavigationQueryField orFieldBlank()
 * @method CmsNavigationQuery orderAscBlank()
 * @method CmsNavigationQuery orderDescBlank()
 * @method CmsNavigationQuery groupByBlank()
 * @method QueryHelper\CmsNavigationQueryField whereActive()
 * @method QueryHelper\CmsNavigationQueryField andFieldActive()
 * @method QueryHelper\CmsNavigationQueryField orFieldActive()
 * @method CmsNavigationQuery orderAscActive()
 * @method CmsNavigationQuery orderDescActive()
 * @method CmsNavigationQuery groupByActive()
 * @method QueryHelper\CmsNavigationQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsNavigationQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsNavigationQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsNavigationQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsNavigationQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsNavigationRecord[] find()
 * @method CmsNavigationRecord findFirst()
 * @method CmsNavigationRecord findPk($value)
 */
//</editor-fold>
class CmsNavigationQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_navigation';

	/**
	 * Filtruje bieżący język
	 * @return CmsNavigationQuery
	 */
	public static function lang() {
		if (!\Mmi\App\FrontController::getInstance()->getRequest()->lang) {
			return new self;
		}
		return (new self)
				->whereLang()->equals(\Mmi\App\FrontController::getInstance()->getRequest()->lang)
				->orFieldLang()->equals(null)
				->orderDescLang();
	}

	/**
	 * Po uri artykułu
	 * @param string $uri
	 * @return CmsNavigationQuery
	 */
	public static function byArticleUri($uri) {
		return (new self)
				->whereModule()->equals('cms')
				->andFieldController()->equals('article')
				->andFieldAction()->equals('index')
				->andFieldParams()->equals('uri=' . $uri);
	}

	/**
	 * Po rodzicu
	 * @param integer $parentId
	 * @return CmsNavigationQuery
	 */
	public static function byParentId($parentId) {
		return (new self)
				->whereParentId()->equals($parentId);
	}

}
