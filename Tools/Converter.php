<?php

/*
 * This file is part of the EasyBanglaDate package.
 *
 * Copyright (c) 2015 Roni Saha
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyBanglaDate\Tools;

class Converter
{
    const JANUARY = 1;
    const FEBRUARY = 2;
    const MARCH = 3;
    const APRIL = 4;
    const MAY = 5;
    const JUNE = 6;
    const JULY = 7;
    const AUGUST = 8;
    const SEPTEMBER = 9;
    const OCTOBER = 10;
    const NOVEMBER = 11;
    const DECEMBER = 12;

    /**
     * @param \DateTime $time
     * @param int $morning
     * @return array
     */
    public static function getBengaliDateMonthYear(\DateTime $time, $morning = 6)
    {

        $enMonth = $time->format('n');
        $day = (int)$time->format('j');
        $hour = (int)$time->format('G');

        list($bnDate, $bnMonth) = self::getBengaliDateAndMonth($enMonth, $day, $hour, $morning, $time->format('L'));

        return array(
            'date'  => $bnDate,
            'month' => $bnMonth,
            'year'  => self::getBengaliYear($enMonth, $day, $hour, $morning, (int)$time->format('Y'))
        );
    }


    /**
     * @param $month
     * @param $day
     * @param $hour
     * @param $morning
     * @param $isLeapYear
     * @return array
     */
    private static function getBengaliDateAndMonth($month, $day, $hour, $morning, $isLeapYear)
    {
        if($month == 3) {
            return self::convertDatesOfMarch($day, $hour, $morning, $isLeapYear);
        }

        return self::handleCommonConversionLogic($day, $hour, $morning, $month, self::getMagicArrayForMonth($month));
    }

    private static function getMagicArrayForMonth($month) {

        switch ($month) {
            case self::JANUARY:
            case self::APRIL:
                return array(13, 16, 14, 16);
            case self::MAY:
            case self::JUNE:
                return array(14, 16, 15, 16);
            case self::JULY:
            case self::AUGUST:
            case self::SEPTEMBER:
                return array(15, 15, 16, 15);
            case self::FEBRUARY:
                return array(12, 17, 13, 17);
            case self::OCTOBER:
                return array(15, 14, 16, 14);
            default :
                return array(14, 15, 15, 15);
        }
    }

    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @param $enMonth
     * @param $offsetsArray
     * @return array
     */
    private static function handleCommonConversionLogic($day, $hour, $morning, $enMonth, $offsetsArray)
    {
        $bnMonth = self::guessBenglaMonth($enMonth);

        if ($day >= 1 && $day <= $offsetsArray[0]) {
            return array(self::getNextDayIfNot($day + $offsetsArray[1], $hour < $morning), $bnMonth);
        }

        if ($day == $offsetsArray[2] && $hour < $morning) {
            return array($day + $offsetsArray[3], $bnMonth);
        }

        return self::getDateForNextMonth($day, $hour, $morning, $bnMonth, $offsetsArray[2]);
    }

    /**
     * @param $day
     * @param $stillYesterday
     * @return mixed
     */
    private static function getNextDayIfNot($day, $stillYesterday)
    {
        return $stillYesterday ? $day : $day + 1;
    }

    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @param $month
     * @param $offset
     * @return array
     */
    private static function getDateForNextMonth($day, $hour, $morning, $month, $offset)
    {
        return array(self::getNextDayIfNot($day - $offset, $hour < $morning), self::getNextMonth($month));
    }

    /**
     * @param $month
     * @return mixed
     */
    private static function getNextMonth($month)
    {
        return $month == 12 ? 1 : $month + 1;
    }

    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @param $isLeapYear
     * @return array
     */
    private static function convertDatesOfMarch($day, $hour, $morning, $isLeapYear)
    {
        $bnMonth = 11;

        if ($day >= 1 && $day <= 14) {
            return array(self::getNextDayIfNot($day + self::getNextDayIfNot(15, $hour < $morning), !$isLeapYear), $bnMonth);
        }

        if ($day == 15 && $hour < $morning) {
            return array(self::getNextDayIfNot($day + 15, !$isLeapYear), $bnMonth);
        }

        return self::getDateForNextMonth($day, $hour, $morning, $bnMonth, 15);
    }

    /**
     * @param $enMonth
     * @param $day
     * @param $hour
     * @param $morning
     * @param $engYear
     * @return int
     */
    private static function getBengaliYear($enMonth, $day, $hour, $morning, $engYear)
    {
        if (self::isBeforeNewYear($enMonth, $day, $hour, $morning)) {
            return $engYear - 594;
        }

        return $engYear - 593;
    }

    /**
     * @param $enMonth
     * @param $day
     * @param $hour
     * @param $morning
     * @return bool
     */
    private static function isBeforeNewYear($enMonth, $day, $hour, $morning)
    {
        return $enMonth < 4 || ($enMonth == 4 && (($day < 14) || ($day == 14 && $hour < $morning)));
    }

    /**
     * @param $month
     * @return mixed
     */
    private static function guessBenglaMonth($month)
    {
        return $month > 4 ? $month - 4 : $month + 8;
    }
}