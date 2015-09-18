<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Plugin;

class CommentGrid extends \Mmi\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Comment\Query::factory());

		$this->addColumn('text', 'dateAdd', [
			'label' => 'data dodania'
		]);
		$this->addColumn('text', 'text', [
			'label' => 'komentarz'
		]);
		$this->addColumn('text', 'signature', [
			'label' => 'podpis'
		]);

		$this->addColumn('text', 'object', [
			'label' => 'zasób'
		]);

		$this->addColumn('text', 'objectId', [
			'label' => 'id zasobu'
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje',
			'links' => [
				'edit' => null
			]
		]);
	}

}
