<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */
//określanie ścieżki
define('BASE_PATH', realpath(dirname(__FILE__) . '/../../../../'));

//ładowanie autoloadera aplikacji
require BASE_PATH . '/app/autoload.php';

//powołanie i uruchomienie aplikacji
$application = new \Mmi\Application('\Mmi\Application\BootstrapCli');
$application->run();

Cms\Model\Cron::run();
