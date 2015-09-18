<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Article;

class Record extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $title;
	public $uri;
	public $dateAdd;
	public $dateModify;
	public $text;
	public $noindex;

	public function save() {
		$this->dateModify = date('Y-m-d H:i:s');
		$filter = new \Mmi\Filter\Url();
		$this->uri = $filter->filter(strip_tags($this->title));
		$result = parent::save();
		\App\Registry::$cache->remove('Cms-Article-' . $this->uri);
		\App\Registry::$cache->remove('Cms-Article-Image' . $this->id);
		return $result;
	}

	public function delete() {
		$article = \Cms\Orm\Navigation\Query::byArticleUri($this->uri)
			->findFirst();
		if ($article !== null) {
			$article->delete();
		}
		return parent::delete();
	}

	public function getFirstImage() {
		$cacheKey = 'Cms-Article-Image-' . $this->id;
		if (null !== ($image = \App\Registry::$cache->load($cacheKey))) {
			return $image;
		}
		$image = \Cms\Orm\File\Query::imagesByObject('cmsarticle', $this->id)->findFirst();
		\App\Registry::$cache->save($image, $cacheKey, 3600);
		return $image;
	}

	protected function _insert() {
		$this->dateAdd = date('Y-m-d H:i:s');
		return parent::_insert();
	}

}
