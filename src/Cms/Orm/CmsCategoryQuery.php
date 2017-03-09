<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsCategoryQuery">
/**
 * @method CmsCategoryQuery limit($limit = null)
 * @method CmsCategoryQuery offset($offset = null)
 * @method CmsCategoryQuery orderAsc($fieldName, $tableName = null)
 * @method CmsCategoryQuery orderDesc($fieldName, $tableName = null)
 * @method CmsCategoryQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsCategoryQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsCategoryQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsCategoryQuery resetOrder()
 * @method CmsCategoryQuery resetWhere()
 * @method QueryHelper\CmsCategoryQueryField whereId()
 * @method QueryHelper\CmsCategoryQueryField andFieldId()
 * @method QueryHelper\CmsCategoryQueryField orFieldId()
 * @method CmsCategoryQuery orderAscId()
 * @method CmsCategoryQuery orderDescId()
 * @method CmsCategoryQuery groupById()
 * @method QueryHelper\CmsCategoryQueryField whereCmsCategoryTypeId()
 * @method QueryHelper\CmsCategoryQueryField andFieldCmsCategoryTypeId()
 * @method QueryHelper\CmsCategoryQueryField orFieldCmsCategoryTypeId()
 * @method CmsCategoryQuery orderAscCmsCategoryTypeId()
 * @method CmsCategoryQuery orderDescCmsCategoryTypeId()
 * @method CmsCategoryQuery groupByCmsCategoryTypeId()
 * @method QueryHelper\CmsCategoryQueryField whereLang()
 * @method QueryHelper\CmsCategoryQueryField andFieldLang()
 * @method QueryHelper\CmsCategoryQueryField orFieldLang()
 * @method CmsCategoryQuery orderAscLang()
 * @method CmsCategoryQuery orderDescLang()
 * @method CmsCategoryQuery groupByLang()
 * @method QueryHelper\CmsCategoryQueryField whereName()
 * @method QueryHelper\CmsCategoryQueryField andFieldName()
 * @method QueryHelper\CmsCategoryQueryField orFieldName()
 * @method CmsCategoryQuery orderAscName()
 * @method CmsCategoryQuery orderDescName()
 * @method CmsCategoryQuery groupByName()
 * @method QueryHelper\CmsCategoryQueryField whereTitle()
 * @method QueryHelper\CmsCategoryQueryField andFieldTitle()
 * @method QueryHelper\CmsCategoryQueryField orFieldTitle()
 * @method CmsCategoryQuery orderAscTitle()
 * @method CmsCategoryQuery orderDescTitle()
 * @method CmsCategoryQuery groupByTitle()
 * @method QueryHelper\CmsCategoryQueryField whereDescription()
 * @method QueryHelper\CmsCategoryQueryField andFieldDescription()
 * @method QueryHelper\CmsCategoryQueryField orFieldDescription()
 * @method CmsCategoryQuery orderAscDescription()
 * @method CmsCategoryQuery orderDescDescription()
 * @method CmsCategoryQuery groupByDescription()
 * @method QueryHelper\CmsCategoryQueryField whereUri()
 * @method QueryHelper\CmsCategoryQueryField andFieldUri()
 * @method QueryHelper\CmsCategoryQueryField orFieldUri()
 * @method CmsCategoryQuery orderAscUri()
 * @method CmsCategoryQuery orderDescUri()
 * @method CmsCategoryQuery groupByUri()
 * @method QueryHelper\CmsCategoryQueryField whereCustomUri()
 * @method QueryHelper\CmsCategoryQueryField andFieldCustomUri()
 * @method QueryHelper\CmsCategoryQueryField orFieldCustomUri()
 * @method CmsCategoryQuery orderAscCustomUri()
 * @method CmsCategoryQuery orderDescCustomUri()
 * @method CmsCategoryQuery groupByCustomUri()
 * @method QueryHelper\CmsCategoryQueryField whereRedirectUri()
 * @method QueryHelper\CmsCategoryQueryField andFieldRedirectUri()
 * @method QueryHelper\CmsCategoryQueryField orFieldRedirectUri()
 * @method CmsCategoryQuery orderAscRedirectUri()
 * @method CmsCategoryQuery orderDescRedirectUri()
 * @method CmsCategoryQuery groupByRedirectUri()
 * @method QueryHelper\CmsCategoryQueryField whereMvcParams()
 * @method QueryHelper\CmsCategoryQueryField andFieldMvcParams()
 * @method QueryHelper\CmsCategoryQueryField orFieldMvcParams()
 * @method CmsCategoryQuery orderAscMvcParams()
 * @method CmsCategoryQuery orderDescMvcParams()
 * @method CmsCategoryQuery groupByMvcParams()
 * @method QueryHelper\CmsCategoryQueryField whereHttps()
 * @method QueryHelper\CmsCategoryQueryField andFieldHttps()
 * @method QueryHelper\CmsCategoryQueryField orFieldHttps()
 * @method CmsCategoryQuery orderAscHttps()
 * @method CmsCategoryQuery orderDescHttps()
 * @method CmsCategoryQuery groupByHttps()
 * @method QueryHelper\CmsCategoryQueryField whereBlank()
 * @method QueryHelper\CmsCategoryQueryField andFieldBlank()
 * @method QueryHelper\CmsCategoryQueryField orFieldBlank()
 * @method CmsCategoryQuery orderAscBlank()
 * @method CmsCategoryQuery orderDescBlank()
 * @method CmsCategoryQuery groupByBlank()
 * @method QueryHelper\CmsCategoryQueryField whereFollow()
 * @method QueryHelper\CmsCategoryQueryField andFieldFollow()
 * @method QueryHelper\CmsCategoryQueryField orFieldFollow()
 * @method CmsCategoryQuery orderAscFollow()
 * @method CmsCategoryQuery orderDescFollow()
 * @method CmsCategoryQuery groupByFollow()
 * @method QueryHelper\CmsCategoryQueryField whereConfigJson()
 * @method QueryHelper\CmsCategoryQueryField andFieldConfigJson()
 * @method QueryHelper\CmsCategoryQueryField orFieldConfigJson()
 * @method CmsCategoryQuery orderAscConfigJson()
 * @method CmsCategoryQuery orderDescConfigJson()
 * @method CmsCategoryQuery groupByConfigJson()
 * @method QueryHelper\CmsCategoryQueryField whereParentId()
 * @method QueryHelper\CmsCategoryQueryField andFieldParentId()
 * @method QueryHelper\CmsCategoryQueryField orFieldParentId()
 * @method CmsCategoryQuery orderAscParentId()
 * @method CmsCategoryQuery orderDescParentId()
 * @method CmsCategoryQuery groupByParentId()
 * @method QueryHelper\CmsCategoryQueryField whereOrder()
 * @method QueryHelper\CmsCategoryQueryField andFieldOrder()
 * @method QueryHelper\CmsCategoryQueryField orFieldOrder()
 * @method CmsCategoryQuery orderAscOrder()
 * @method CmsCategoryQuery orderDescOrder()
 * @method CmsCategoryQuery groupByOrder()
 * @method QueryHelper\CmsCategoryQueryField whereDateAdd()
 * @method QueryHelper\CmsCategoryQueryField andFieldDateAdd()
 * @method QueryHelper\CmsCategoryQueryField orFieldDateAdd()
 * @method CmsCategoryQuery orderAscDateAdd()
 * @method CmsCategoryQuery orderDescDateAdd()
 * @method CmsCategoryQuery groupByDateAdd()
 * @method QueryHelper\CmsCategoryQueryField whereDateModify()
 * @method QueryHelper\CmsCategoryQueryField andFieldDateModify()
 * @method QueryHelper\CmsCategoryQueryField orFieldDateModify()
 * @method CmsCategoryQuery orderAscDateModify()
 * @method CmsCategoryQuery orderDescDateModify()
 * @method CmsCategoryQuery groupByDateModify()
 * @method QueryHelper\CmsCategoryQueryField whereCacheLifetime()
 * @method QueryHelper\CmsCategoryQueryField andFieldCacheLifetime()
 * @method QueryHelper\CmsCategoryQueryField orFieldCacheLifetime()
 * @method CmsCategoryQuery orderAscCacheLifetime()
 * @method CmsCategoryQuery orderDescCacheLifetime()
 * @method CmsCategoryQuery groupByCacheLifetime()
 * @method QueryHelper\CmsCategoryQueryField whereActive()
 * @method QueryHelper\CmsCategoryQueryField andFieldActive()
 * @method QueryHelper\CmsCategoryQueryField orFieldActive()
 * @method CmsCategoryQuery orderAscActive()
 * @method CmsCategoryQuery orderDescActive()
 * @method CmsCategoryQuery groupByActive()
 * @method QueryHelper\CmsCategoryQueryField whereDateStart()
 * @method QueryHelper\CmsCategoryQueryField andFieldDateStart()
 * @method QueryHelper\CmsCategoryQueryField orFieldDateStart()
 * @method CmsCategoryQuery orderAscDateStart()
 * @method CmsCategoryQuery orderDescDateStart()
 * @method CmsCategoryQuery groupByDateStart()
 * @method QueryHelper\CmsCategoryQueryField whereDateEnd()
 * @method QueryHelper\CmsCategoryQueryField andFieldDateEnd()
 * @method QueryHelper\CmsCategoryQueryField orFieldDateEnd()
 * @method CmsCategoryQuery orderAscDateEnd()
 * @method CmsCategoryQuery orderDescDateEnd()
 * @method CmsCategoryQuery groupByDateEnd()
 * @method QueryHelper\CmsCategoryQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsCategoryQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsCategoryQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsCategoryQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsCategoryQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsCategoryRecord[] find()
 * @method CmsCategoryRecord findFirst()
 * @method CmsCategoryRecord findPk($value)
 */
//</editor-fold>
class CmsCategoryQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_category';

	/**
	 * Definicje zgodne z językiem
	 * @return CmsCategoryQuery
	 */
	public function lang() {
		if (!\Mmi\App\FrontController::getInstance()->getRequest()->lang) {
			return (new self);
		}
		return (new CmsCategoryQuery)
				->whereLang()->equals(\Mmi\App\FrontController::getInstance()->getRequest()->lang)
				->orFieldLang()->equals(null)
				->orderDescLang();
	}

	/**
	 * Zapytanie wyszukujące kategorie po kluczu typu (szablonu)
	 * @param string $typeKey
	 * @return CmsCategoryQuery
	 */
	public function searchByTypeKey($typeKey) {
		return (new CmsCategoryQuery)
				->join('cms_category_type')->on('cms_category_type_id')
				->where('key', 'cms_category_type')->equals($typeKey);
	}

	/**
	 * Zapytanie wyszukujące po uri
	 * @param string $uri
	 * @return CmsCategoryQuery
	 */
	public function searchByUri($uri) {
		return (new CmsCategoryQuery)->whereUri()->equals($uri)
				->orFieldCustomUri()->equals($uri);
	}

	/**
	 * Wyszukuje kategorię po uri z uwzględnieniem priorytetu
	 * @param string $uri
	 * @return \Cms\Orm\CmsCategoryRecord
	 */
	public function getCategoryByUri($uri) {
		$redirectCategory = null;
		//iteracja po kategoriach
		foreach ($this->searchByUri($uri)
			->joinLeft('cms_category_type')->on('cms_category_type_id')
			->find() as $category) {
			//kategoria jest przekierowaniem
			if ($category->redirectUri) {
				//używane jest pierwsze znalezione przekierowanie
				$redirectCategory = $redirectCategory ? $redirectCategory : $category;
				continue;
			}
			//zwrot treści, lub mvc (priorytet)
			return $category;
		}
		//zwrot przekierowania
		return $redirectCategory;
	}

}
