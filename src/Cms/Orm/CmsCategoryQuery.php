<?php

namespace Cms\Orm;

use Mmi\App\App;
use Mmi\Http\Request;
use Mmi\Orm\Query;

//<editor-fold defaultstate="collapsed" desc="CmsCategoryQuery">
/**
 * @method CmsCategoryQuery limit($limit = null)
 * @method CmsCategoryQuery offset($offset = null)
 * @method CmsCategoryQuery orderAsc($fieldName, $tableName = null)
 * @method CmsCategoryQuery orderDesc($fieldName, $tableName = null)
 * @method CmsCategoryQuery andQuery(Query $query)
 * @method CmsCategoryQuery whereQuery(Query $query)
 * @method CmsCategoryQuery orQuery(Query $query)
 * @method CmsCategoryQuery resetOrder()
 * @method CmsCategoryQuery resetWhere()
 * @method QueryHelper\CmsCategoryQueryField whereId()
 * @method QueryHelper\CmsCategoryQueryField andFieldId()
 * @method QueryHelper\CmsCategoryQueryField orFieldId()
 * @method CmsCategoryQuery orderAscId()
 * @method CmsCategoryQuery orderDescId()
 * @method CmsCategoryQuery groupById()
 * @method QueryHelper\CmsCategoryQueryField whereCmsAuthId()
 * @method QueryHelper\CmsCategoryQueryField andFieldCmsAuthId()
 * @method QueryHelper\CmsCategoryQueryField orFieldCmsAuthId()
 * @method CmsCategoryQuery orderAscCmsAuthId()
 * @method CmsCategoryQuery orderDescCmsAuthId()
 * @method CmsCategoryQuery groupByCmsAuthId()
 * @method QueryHelper\CmsCategoryQueryField whereTemplate()
 * @method QueryHelper\CmsCategoryQueryField andFieldTemplate()
 * @method QueryHelper\CmsCategoryQueryField orFieldTemplate()
 * @method CmsCategoryQuery orderAscTemplate()
 * @method CmsCategoryQuery orderDescTemplate()
 * @method CmsCategoryQuery groupByTemplate()
 * @method QueryHelper\CmsCategoryQueryField whereCmsCategoryOriginalId()
 * @method QueryHelper\CmsCategoryQueryField andFieldCmsCategoryOriginalId()
 * @method QueryHelper\CmsCategoryQueryField orFieldCmsCategoryOriginalId()
 * @method CmsCategoryQuery orderAscCmsCategoryOriginalId()
 * @method CmsCategoryQuery orderDescCmsCategoryOriginalId()
 * @method CmsCategoryQuery groupByCmsCategoryOriginalId()
 * @method QueryHelper\CmsCategoryQueryField whereStatus()
 * @method QueryHelper\CmsCategoryQueryField andFieldStatus()
 * @method QueryHelper\CmsCategoryQueryField orFieldStatus()
 * @method CmsCategoryQuery orderAscStatus()
 * @method CmsCategoryQuery orderDescStatus()
 * @method CmsCategoryQuery groupByStatus()
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
 * @method QueryHelper\CmsCategoryQueryField wherePath()
 * @method QueryHelper\CmsCategoryQueryField andFieldPath()
 * @method QueryHelper\CmsCategoryQueryField orFieldPath()
 * @method CmsCategoryQuery orderAscPath()
 * @method CmsCategoryQuery orderDescPath()
 * @method CmsCategoryQuery groupByPath()
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
 * @method QueryHelper\CmsCategoryQueryField whereBlank()
 * @method QueryHelper\CmsCategoryQueryField andFieldBlank()
 * @method QueryHelper\CmsCategoryQueryField orFieldBlank()
 * @method CmsCategoryQuery orderAscBlank()
 * @method CmsCategoryQuery orderDescBlank()
 * @method CmsCategoryQuery groupByBlank()
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
 * @method QueryHelper\CmsCategoryQueryField whereActive()
 * @method QueryHelper\CmsCategoryQueryField andFieldActive()
 * @method QueryHelper\CmsCategoryQueryField orFieldActive()
 * @method CmsCategoryQuery orderAscActive()
 * @method CmsCategoryQuery orderDescActive()
 * @method CmsCategoryQuery groupByActive()
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
class CmsCategoryQuery extends Query
{

    protected $_tableName = 'cms_category';

    /**
     * Definicje zgodne z językiem
     * @return CmsCategoryQuery
     */
    public function lang()
    {
        if (!App::$di->get(Request::class)->lang) {
            return (new self());
        }
        return $this
            ->whereLang()->equals(App::$di->get(Request::class)->lang)
            ->orFieldLang()->equals(null)
            ->orderDescLang();
    }

    /**
     * Zapytanie wyszukujące po uri
     * @param string $uri
     * @return CmsCategoryQuery
     */
    public function searchByUri($uri)
    {
        return $this->whereUri()->equals($uri)
            ->orFieldCustomUri()->equals($uri);
    }

    /**
     * Zapytanie o aktywne opublikowane
     */
    public function publishedActive(): self
    {
        return $this
            ->whereStatus()->equals(\Cms\Orm\CmsCategoryRecord::STATUS_ACTIVE)
            ->whereActive()->equals(true);
    }

    /**
     * Wyszukuje kategorię po uri z uwzględnieniem priorytetu
     * @param string $uri
     * @return CmsCategoryRecord
     */
    public function getCategoryByUri(string $uri, string $scope)
    {
        $redirectCategory = null;
        //iteracja po kategoriach
        foreach (
            (new self())
                ->andFieldStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
                ->andFieldActive()->equals(true)
                ->andFieldTemplate()->like($scope . '%')
                ->andQuery((new CmsCategoryQuery())->searchByUri($uri))
                ->find() as $category
        ) {
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

    /**
     * Wyszukanie po historycznym uri
     * @param string $uri
     * @return self
     */
    public function byHistoryUri(string $uri, string $scope)
    {
        return (new self())
            ->whereActive()->equals(true)
            ->whereStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
            ->whereTemplate()->like($scope . '%')
            ->join('cms_category', 'cms_category', 'currentCategory')->on('id', 'cms_category_original_id')
            ->where('status', 'currentCategory')->notEquals(CmsCategoryRecord::STATUS_ACTIVE)
            ->where('active', 'currentCategory')->equals(true)
            ->whereQuery(
                (new self())
                    ->where('uri', 'currentCategory')->equals($uri)
                    ->orField('customUri', 'currentCategory')->equals($uri)
            );
    }

    /**
     * Wyszukiwanie, czy jakas strona o danym uri jest juz aktywna
     * @param string $uri
     * @param string $scope
     * @param int|null $ignoreId
     * @return bool
     */
    public function isSimilarActivePage(string $uri, string $scope = null, int $ignoreId = null): bool
    {
        $query = (new self())
            ->whereQuery((new self())->searchByUri($uri))
            ->whereStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
            ->whereActive()->equals(true);

        if ($scope) {
            $query->whereTemplate()->like($scope . '/%');
        }

        if ($ignoreId) {
            $query->whereId()->notEquals($ignoreId);
        }

        return $query->count() > 0;
    }
}
