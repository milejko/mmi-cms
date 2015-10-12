<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Page;

/**
 * Formularz artykułów cms w nawigatorze
 */
class Article extends PageAbstract {

	public function init() {
		//menu label
		$this->addElementText('label')
			->setLabel('Nazwa w menu')
			->setRequired()
			->addValidatorStringLength(2, 64);

		//opcjonalny opis
		$this->addElementTextarea('description')
			->setLabel('Opis strony (meta/description)')
			->addValidatorStringLength(3, 1024);

		//opcjonalne keywords
		$this->addElementText('keywords')
			->setLabel('Słowa kluczowe (meta/keywords)')
			->addValidatorStringLength(3, 64);

		//uri artykułu
		$articleId = null;
		//wyszukiwanie artykułu
		if (preg_match('/^uri=([a-z0-9\-\_]+)$/i', $this->getRecord()->params, $matches) && null !== ($article = \Cms\Orm\CmsArticleQuery::byUri($matches[1])
				->findFirst())) {
			$articleId = $article->id;
		}
		//id artykułu
		$this->addElementSelect('articleId')
			->setLabel('Artykuł')
			->setMultiOptions(\Cms\Model\Article::getMultioptions())
			->setValue($articleId);
		
		//nadrzędne pola
		parent::init();
	}

	/**
	 * Wiązanie artykułu przed zapisem
	 * @return boolean
	 */
	public function beforeSave() {
		if (null === ($article = \Cms\Orm\CmsArticleQuery::factory()->findPk($this->getElement('articleId')->getValue()))) {
			return true;
		}
		$this->getRecord()->module = 'cms';
		$this->getRecord()->controller = 'article';
		$this->getRecord()->action = 'index';
		$this->getRecord()->params = 'uri=' . $article->uri;
		$this->getRecord()->uri = null;
		return true;
	}
	
}
