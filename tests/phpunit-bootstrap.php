<?php

/**
 * Multiportals CMS instance (content repository)
 *
 * @copyright Copyright (c) 2021 Nowa Era (http://nowaera.pl) All rights reserved.
 * @license   Proprietary and confidential
 */

declare(strict_types=1);

use Mmi\App\AppTesting;

//definicja katalogu bazowego
define('BASE_PATH', __DIR__ . '/../');

//dołączenie autoloadera
require BASE_PATH . 'vendor/autoload.php';

//zmienne testowe
putenv('APP_DEBUG_ENABLED=0');
putenv('DB_HOST=' . BASE_PATH . '/var/test-db.sqlite');
putenv('DB_DRIVER=sqlite');

//iteracja po katalogach do utworzenia
foreach (['var/cache', 'var/compile', 'var/coverage', 'var/data', 'var/log', 'var/session'] as $dir) {
    //tworzenie katalogu
    !file_exists(BASE_PATH . '/' . $dir) ? mkdir(BASE_PATH . '/' . $dir, 0777, true) : null;
}

//kopiowanie testowej bazy danych do tmp
#copy(BASE_PATH . '/tests/data/db.sqlite', BASE_PATH . '/var/test-db.sqlite');

//run application
(new AppTesting())->run();
