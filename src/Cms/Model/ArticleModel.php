<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

class ArticleModel {

	/**
	 * Multiopcje z artykułami
	 * @return array
	 */
	public function getMultioptions() {
		return [null => '---'] + (new \Cms\Orm\CmsArticleQuery)
				->orderAscTitle()
				->findPairs('id', 'title');
	}

	/**
	 * Wyszukiwanie po uri (z kategorią
	 * @param string $path
	 * @return CmsArticleRecord
	 */
	public function searchByPath($path) {
		//rozdzielenie ścieżek kategorii od artykułu
		$parts = explode('/', $path);
		$articleUri = $parts[count($parts) - 1];
		unset($parts[count($parts) - 1]);
		$categoryUri = implode('/', $parts);
		//wyszukiwanie z kategorią
		$articleQuery = (new \Cms\Orm\CmsArticleQuery)->joinedTypeByUri($articleUri);
		if ($categoryUri) {
			//ustawianie możliwych dla wybranej kategorii - id artykułów
			$articleQuery->andFieldId()->equals((new \Cms\Orm\CmsCategoryRelationQuery)
					->join('cms_category')->on('cms_category_id')
					->whereObject()->equals('article')
					->andField('uri', 'cms_category')->equals($categoryUri)
					->findUnique('objectId'));
		}
		//wyszukiwanie z kategorią
		$article = $articleQuery->findFirst();
		//podłączenia kategorii
		return $article === null ? null : $article->setOption('category', (new CategoryModel)->getCategoryByUri($categoryUri));
	}

}
