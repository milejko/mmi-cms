<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use \Cms\Orm;

/**
 * Model logu
 */
class Log
{

    /**
     * Dodaje zdarzenie do logu
     * @param string $operation operacja
     * @param array $data dane
     * @return bool czy dodano
     */
    public static function add($operation = null, array $data = [])
    {
        //logger disabled
        \Mmi\App\FrontController::getInstance()->getLogger()->error('Internal logging permanently disabled, use front controller, PSR logger');
    }
}
