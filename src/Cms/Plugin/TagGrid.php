<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Plugin;

class TagGrid extends \Mmi\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Tag\Query::factory());
		$this->setOption('locked', true);

		$this->addColumn('text', 'tag', [
			'label' => 'tag',
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje'
		]);
	}

}
