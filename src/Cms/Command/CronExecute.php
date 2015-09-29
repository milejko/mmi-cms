<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Command;

//kalkulacja ścieżki
foreach ([__DIR__ . '/..', __DIR__ . '/../../..', __DIR__ . '/../../../../../..'] as $path) {
	if (file_exists($path . '/vendor/mmi')) {
		include $path . '/vendor/mmi/mmi/src/Mmi/Tools/CliAbstract.php';
	}
}

/**
 * Usuwa pliki bez powiązań w strukturze
 */
class CronExecute extends \Mmi\Tools\CliAbstract {

	public function run() {
		\Cms\Model\Cron::run();
	}

}

new CronExecute(isset($argv[1]) ? $argv[1] : null);
