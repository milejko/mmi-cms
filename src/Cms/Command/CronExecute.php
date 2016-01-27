#!/usr/bin/env php
<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Command;

//nie ma tu jeszcze autoloadera ładowanie CliAbstract
require_once 'CommandAbstract.php';

/**
 * Usuwa pliki bez powiązań w strukturze
 */
class CronExecute extends \Mmi\Command\CommandAbstract {

	public function run() {
		\Cms\Model\Cron::run();
	}

}

new CronExecute(isset($argv[1]) ? $argv[1] : null);
