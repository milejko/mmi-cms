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

class Stat
{

    /**
     * 
     * @param string $object
     * @param integer $objectId
     * @return boolean
     */
    public static function hit($object, $objectId = null)
    {
        $stat = new Orm\CmsStatRecord;
        $stat->object = $object;
        $stat->objectId = is_numeric($objectId) ? intval($objectId) : null;
        $stat->dateTime = date('Y-m-d H:i:s');
        return $stat->save();
    }

    /**
     * 
     * @return array
     */
    public static function agregate()
    {
        $start = microtime(true);
        $processed = 0;
        foreach ((new Orm\CmsStatQuery)->limit(10000)->find() as $item) {
            $processed++;
            $dateTime = explode(' ', $item->dateTime);
            $date = explode('-', $dateTime[0]);
            $time = explode(':', $dateTime[1]);
            $dateTime = strtotime($item->dateTime);
            $objectId = $item->objectId;
            if (!$item->objectId) {
                $objectId = null;
            }
            if ($objectId !== null) {
                //godziny ogólnie - same obiekty
                self::_push($item->object, null, $time[0], null, null, null);
                //dni w dokładnej dacie - osame obiekty
                self::_push($item->object, null, null, $date[2], $date[1], $date[0]);
                //miesiące w dokładnej dacie - same obiekty
                self::_push($item->object, null, null, null, $date[1], $date[0]);
                //lata - same obiekty
                self::_push($item->object, null, null, null, null, $date[0]);
                //od poczatku - same obiekty
                self::_push($item->object, null, null, null, null, null);
            }
            //godziny ogólnie - obiekty z id
            self::_push($item->object, $objectId, $time[0], null, null, null);
            //godziny ogólnie w roku/miesiącu - same obiekty
            self::_push($item->object, null, $time[0], null, $date[1], $date[0]);
            //dni w dokładnej dacie - obiekty z id
            self::_push($item->object, $objectId, null, $date[2], $date[1], $date[0]);
            //miesiące w dokładnej dacie - obiekty z id
            self::_push($item->object, $objectId, null, null, $date[1], $date[0]);
            //lata - obiekty z id
            self::_push($item->object, $objectId, null, null, null, $date[0]);
            //od poczatku - obiekty z id
            self::_push($item->object, $objectId, null, null, null, null);
            $item->delete();
        }
        $time = microtime(true) - $start;
        return [$processed, $time];
    }

    /**
     * 
     * @param string $object
     * @param integer $objectId
     * @param integer $hour
     * @param integer $day
     * @param integer $month
     * @param integer $year
     * @return boolean
     */
    protected static function _push($object, $objectId, $hour, $day, $month, $year)
    {
        $o = (new Orm\CmsStatDateQuery)
            ->whereObject()->equals($object)
            ->andFieldObjectId()->equals($objectId)
            ->andFieldHour()->equals($hour)
            ->andFieldDay()->equals($day)
            ->andFieldMonth()->equals($month)
            ->andFieldYear()->equals($year)
            ->findFirst();
        if ($o === null) {
            $o = new Orm\CmsStatDateRecord;
        }
        $o->count = intval($o->count) + 1;
        $o->object = $object;
        $o->objectId = $objectId;
        $o->hour = $hour;
        $o->day = $day;
        $o->month = $month;
        $o->year = $year;
        return $o->save();
    }

    /**
     * Pobiera unikalne obiekty
     * @return array
     */
    public static function getUniqueObjects()
    {
        $all = (new Orm\CmsStatDateQuery)
            ->whereHour()->equals(null)
            ->andFieldDay()->equals(null)
            ->andFieldMonth()->equals(null)
            ->andFieldObjectId()->equals(null)
            ->orderAscObject()
            ->find();
        $objects = [];
        foreach ($all as $object) {
            if (!isset($objects[$object->object])) {
                $objects[$object->object] = $object->object;
            }
        }
        return $objects;
    }

    /**
     * Statystyki średnie godzinowe
     * @param string $object obiekt
     * @param integer $objectId id obiektu
     * @param integer $year rok
     * @param string $month miesiąc
     * @return array
     */
    public static function avgHourly($object, $objectId, $year, $month)
    {
        $statArray = [];
        //pobieranie wierszy
        foreach (self::getRows($object, $objectId, $year, $month, null, true) as $stat) {
            $statArray[$stat->hour] = $stat->count;
        }
        $stats = [];
        //układanie wierszy w kubełkach godzin
        for ($i = 0; $i <= 23; $i++) {
            $hour = $i;
            $count = 0;
            if (isset($statArray[$hour])) {
                $count = $statArray[$hour];
            }
            $stats[$hour] = $count;
        }
        return $stats;
    }

    /**
     * Statystyki dzienne
     * @param string $object
     * @param integer $objectId
     * @param integer $year
     * @param string $month
     * @return array
     */
    public static function daily($object, $objectId, $year, $month)
    {
        $days = date('t', strtotime($year . '-' . $month . '01 00:00:00'));
        $statArray = [];
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        //pobieranie wierszy
        foreach (self::getRows($object, $objectId, $year, $month, true, null) as $stat) {
            $stat->day = str_pad($stat->day, 2, '0', STR_PAD_LEFT);
            $statArray[$stat->year . '-' . $month . '-' . $stat->day] = $stat->count;
        }
        $stats = [];
        //układanie wierszy w kubełkach dni
        for ($i = 1; $i <= $days; $i++) {
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $count = 0;
            if (isset($statArray[$year . '-' . $month . '-' . $day])) {
                $count = $statArray[$year . '-' . $month . '-' . $day];
            }
            $stats[$year . '-' . $month . '-' . $day] = $count;
        }
        return $stats;
    }

    /**
     * Statystyki do dnia (wraz z poprzednim miesiącem
     * @param string $object
     * @param integer $objectId
     * @param integer $year
     * @param string $month
     * @param string $day
     * @return string
     */
    public static function toDate($object, $objectId, $year, $month, $day)
    {
        $now = strtotime($year . '-' . $month . '-' . $day);
        $prev = strtotime('-1 month', $now);

        $raw = [];
        //uzupełnianie danych surowych poprzednim miesiącem
        foreach (self::getRows($object, $objectId, date('Y', $prev), date('m', $prev), true, null) as $stat) {
            $raw[$stat->year . '-' . $stat->month . '-' . $stat->day] = $stat->count;
        }
        //uzupełnianie danych surowych obecnym miesiącem
        foreach (self::getRows($object, $objectId, date('Y', $now), date('m', $now), true, null) as $stat) {
            $raw[$stat->year . '-' . $stat->month . '-' . $stat->day] = $stat->count;
        }
        $statArray = [];

        //uzupełnianie dni
        for ($i = 30; $i > -1; $i--) {
            $curTime = strtotime('-' . $i . ' day', $now);
            $count = 0;
            if (isset($raw[date('Y', $curTime) . '-' . ltrim(date('m', $curTime), '0') . '-' . ltrim(date('d', $curTime), '0')])) {
                $count = $raw[date('Y', $curTime) . '-' . ltrim(date('m', $curTime), '0') . '-' . ltrim(date('d', $curTime), '0')];
            }
            $statArray[date('m.d', $curTime)] = $count;
        }
        return $statArray;
    }

    /**
     * Statystyki miesięczne
     * @param string $object
     * @param integer $objectId
     * @param integer $year
     * @return array
     */
    public static function monthly($object, $objectId, $year)
    {
        $statArray = [];
        //pobieranie rekordów
        foreach (self::getRows($object, $objectId, $year, true, null, null) as $stat) {
            $stat->month = str_pad($stat->month, 2, '0', STR_PAD_LEFT);
            $statArray[$stat->year . '-' . $stat->month] = $stat->count;
        }
        $stats = [];
        //dodawanie do kubełków miesięcy
        for ($i = 1; $i <= 12; $i++) {
            $month = str_pad($i, 2, '0', STR_PAD_LEFT);
            $count = 0;
            if (isset($statArray[$year . '-' . $month])) {
                $count = $statArray[$year . '-' . $month];
            }
            $stats[$year . '-' . $month] = $count;
        }
        return $stats;
    }

    /**
     * Statystyki roczne
     * @param string $object
     * @param integer $objectId
     * @return array
     */
    public static function yearly($object, $objectId)
    {
        $statArray = [];
        foreach (self::getRows($object, $objectId, true, null, null, null) as $stat) {
            $statArray[$stat->year] = $stat->count;
        }
        return $statArray;
    }

    /**
     * Pobranie wierszy z DB
     * @param string $object
     * @param integer $objectId
     * @param integer $year
     * @param integer $month
     * @param integer $day
     * @param integer $hour
     * @return \Mmi\Orm\RecordCollection
     */
    public static function getRows($object, $objectId, $year = null, $month = null, $day = null, $hour = null)
    {
        //nowa quera filtrująca po obiekcie i ID
        $q = (new Orm\CmsStatDateQuery)
                ->whereObject()->equals($object)
                ->andFieldObjectId()->equals($objectId);
        //wiązanie roku
        self::_bindParam($q, 'year', $year);
        //wiązanie miesiąca
        self::_bindParam($q, 'month', $month);
        //wiązanie dnia
        self::_bindParam($q, 'day', $day);
        //wiązanie godziny
        self::_bindParam($q, 'hour', $hour);

        //sortowanie i zwrot
        return $q->orderAsc('day')
                ->orderAsc('month')
                ->orderAsc('year')
                ->orderAsc('hour')
                ->find();
    }

    /**
     * Wiązanie parametru do zapytania jeśli określony
     * @param \Mmi\Orm\Query $q
     * @param string $name
     * @param mixed $value
     */
    protected static function _bindParam(\Mmi\Orm\Query $q, $name, $value)
    {
        if ($value === true) {
            $q->andField($name)->notEquals(null);
            return;
        }
        $q->andField($name)->equals($value);
    }

}
