<?php

namespace Cms\Orm;

/**
 * Rekord artykułu
 */
class CmsArticleRecord extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $title;
	public $uri;
	public $dateAdd;
	public $dateModify;
	public $text;
	public $noindex;

	/**
	 * Zapis z filtracją url
	 * @return boolean
	 */
	public function save() {
		$this->dateModify = date('Y-m-d H:i:s');
		$filter = new \Mmi\Filter\Url();
		$this->uri = $filter->filter(strip_tags($this->title));
		return parent::save() && $this->_deleteCache();
	}

	/**
	 * Usunięcie
	 * @return boolean
	 */
	public function delete() {
		$article = CmsNavigationQuery::byArticleUri($this->uri)
			->findFirst();
		if ($article !== null) {
			$article->delete();
		}
		return parent::delete() && $this->_deleteCache();
	}

	/**
	 * Pobranie pierwszego obrazu
	 * @return CmsFileRecord
	 */
	public function getFirstImage() {
		$cacheKey = 'Cms-Article-Image-' . $this->id;
		if (null !== ($image = \App\Registry::$cache->load($cacheKey))) {
			return $image;
		}
		$image = CmsFileQuery::imagesByObject('cmsarticle', $this->id)->findFirst();
		\App\Registry::$cache->save($image, $cacheKey, 3600);
		return $image;
	}

	/**
	 * Wstawienie rekordu
	 * @return boolean
	 */
	protected function _insert() {
		$this->dateAdd = date('Y-m-d H:i:s');
		return parent::_insert();
	}
	
	/**
	 * Usunięcie cache
	 * @return boolean
	 */
	protected function _deleteCache() {
		\App\Registry::$cache->remove('Cms-Article-' . $this->uri);
		\App\Registry::$cache->remove('Cms-Article-Image-' . $this->id);
		return true;
	}

}
