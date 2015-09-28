<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

class CommentGrid extends \CmsAdmin\Plugin\Grid {

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
