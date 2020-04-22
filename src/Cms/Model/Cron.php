<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsCronQuery;

class Cron
{
    const MIN_EXECUTION_OFFSET = 200000;
    const MAX_EXECUTION_OFFSET = 500000;
    const SECONDS_PER_MINUTE = 60;

    /**
     * Pobiera zadania crona
     */
    public static function run()
    {
        //czyszczenie zablokowanych rekordów
        foreach ((new CmsCronQuery)
            ->activeLockExpired()
            ->find() as $lockedCronRecord) {
            $lockedCronRecord->unlockAfterExecution();
        }
        //iteracja po liście zadań do wykonania
        foreach ((new CmsCronQuery)->activeUnlocked()->find() as $listedCronRecord) {
            //sprawdzanie warunku wykonania np. */3 * * * *
            if (!self::_getToExecute($listedCronRecord)) {
                continue;
            }
            //opóźnienie 200ms - 500ms (dzięki temu mniej baza danych ma więcej czasu na załozenie blokady)
            usleep(rand(self::MIN_EXECUTION_OFFSET, self::MAX_EXECUTION_OFFSET));
            //ponowne łączenie - mogło upłynąć sporo czasu przy procesowaniu poprzedniego crona
            \App\Registry::$db->connect();
            //sprawdzenie czy rekord jest odblokowany
            if (null === ($cronRecord = (new CmsCronQuery)->activeUnlocked()->findPk($listedCronRecord->id))) {
                continue;
            }
            //wykonany juz w tej minucie
            if (time() - strtotime($cronRecord->dateLastExecute) < self::SECONDS_PER_MINUTE) {
                continue;
            }
            //blokuje rekord na czas wykonania
            $cronRecord->lock();
            $output = '';
            try {
                $start = microtime(true);
                $output = \Mmi\Mvc\ActionHelper::getInstance()->action(new \Mmi\Http\Request(['module' => $cronRecord->module, 'controller' => $cronRecord->controller, 'action' => $cronRecord->action]));
                $elapsed = round(microtime(true) - $start, 2);
            } catch (\Exception $e) {
                //error logging
                \Mmi\App\FrontController::getInstance()->getLogger()->error('CRON failed: @' . gethostname() . $cronRecord->name . ' ' . $e->getMessage());
                return;
            }
            //ponowne łączenie
            \App\Registry::$db->connect();
            //zapisywanie wiadomości
            $cronRecord->message = $output;
            //zapis do bazy bez modyfikowania daty ostatniej modyfikacji
            $cronRecord->unlockAfterExecution();
            //logowanie uruchomienia
            \Mmi\App\FrontController::getInstance()->getLogger()->info('CRON done: @' . gethostname() . ' ' . $cronRecord->name . ' ' . $output .  ' in ' . $elapsed . 's');
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
