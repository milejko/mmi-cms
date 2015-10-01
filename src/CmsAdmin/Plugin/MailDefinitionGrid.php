<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

class MailDefinitionGrid extends \CmsAdmin\Plugin\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\CmsMailDefinitionQuery::lang());

		$this->addColumn('text', 'lang', [
			'label' => 'język'
		]);

		$this->addColumn('text', 'name', [
			'label' => 'nazwa'
		]);

		$this->addColumn('checkbox', 'html', [
			'label' => 'HTML'
		]);

		$this->addColumn('text', 'subject', [
			'label' => 'temat'
		]);

		$this->addColumn('text', 'fromName', [
			'label' => 'nazwa od'
		]);

		$this->addColumn('text', 'replyTo', [
			'label' => 'odpowiedz'
		]);

		$this->addColumn('text', 'mailServerId', [
			'label' => 'id połączenia'
		]);

		$this->addColumn('text', 'dateAdd', [
			'label' => 'data dodania'
		]);

		$this->addColumn('text', 'dateModify', [
			'label' => 'data modyfikacji'
		]);

		$this->addColumn('checkbox', 'active', [
			'label' => 'aktywny'
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje'
		]);
	}

}
