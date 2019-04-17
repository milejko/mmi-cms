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

class Cron
{

    /**
     * Pobiera zadania crona
     */
    public static function run()
    {
        foreach (Orm\CmsCronQuery::active()->find() as $cron) {
            if (!self::_getToExecute($cron)) {
                continue;
            }
            $output = '';
            try {
                $start = microtime(true);
                $output = \Mmi\Mvc\ActionHelper::getInstance()->action(new \Mmi\Http\Request(['module' => $cron->module, 'controller' => $cron->controller, 'action' => $cron->action]));
                $elapsed = round(microtime(true) - $start, 2);
            } catch (\Exception $e) {
                //error logging
                \Mmi\App\FrontController::getInstance()->getLogger()->error('CRON failed: @' . gethostname() . $cron->name . ' ' . $e->getMessage());
                return;
            }
            //zmień datę ostatniego wywołania
            $cron->dateLastExecute = date('Y-m-d H:i:s');
            //ponowne łączenie
            \App\Registry::$db->connect();
            //zapis do bazy bez modyfikowania daty ostatniej modyfikacji
            $cron->saveWithoutLastDateModify();
            //logowanie uruchomienia
            \Mmi\App\FrontController::getInstance()->getLogger()->info('CRON done: @' . gethostname() . ' ' . $cron->name . ' ' . $output .  ' in ' . $elapsed . 's');
        }
    }

    /**
     * Sprawdza czy dane zadanie jest do wykonania
     * @param \Cms\Orm\CmsCronRecord $record
     */
    protected static function _getToExecute($record)
    {
        return self::_valueMatch(date('i'), $record->minute) &&
            self::_valueMatch(date('H'), $record->hour) &&
            self::_valueMatch(date('d'), $record->dayOfMonth) &&
            self::_valueMatch(date('m'), $record->month) &&
            self::_valueMatch(date('N'), $record->dayOfWeek);
    }

    /**
     * Dopasowuje 
     * @param integer $current
     * @param integer $value
     * @return boolean
     */
    protected static function _valueMatch($current, $value)
    {
        //Wszystko
        if ($value == '*') {
            return true;
        }
        //Każda wartość podzielna przez
        if (false !== strpos($value, '/')) {
            if ($current % intval(ltrim($value, '*/ ')) == 0) {
                return true;
            }
        }
        //Lista wartości
        $values = explode(',', $value);
        foreach ($values as $val) {
            if (intval($val) == $val && $val == $current) {
                return true;
            }
        }
        //zakres wartości
        if (false !== strpos($value, '-')) {
            $range = explode('-', $value);
            if ($current >= intval($range[0]) && $current <= intval($range[1])) {
                return true;
            }
        }
        return false;
    }
}
