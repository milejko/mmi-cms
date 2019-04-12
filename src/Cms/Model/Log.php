<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

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
        \Mmi\App\FrontController::getInstance()->getLogger()->info('Legacy log: ' . $operation);
        \Mmi\App\FrontController::getInstance()->getLogger()->warning('\Cms\Log\Model deprecated, use MMi PSR logger instead');
    }
}
