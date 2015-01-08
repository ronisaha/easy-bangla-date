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
            'year'  => self::getBengaliYear($enMonth, $day, $hour, $morning, $time)
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
        switch ($month) {
            case 1:
            case 4:
                return self::convertDatesOfJanuaryOrApril($day, $hour, $morning, $month + 8);
            case 5:
            case 6:
                return self::convertDatesOfMayOrJune($day, $hour, $morning, $month - 4);
            case 7:
            case 8:
            case 9:
                return self::convertDatesOfJulyOrAugustOrSeptember($day, $hour, $morning, $month - 4);
            case 2:
                return self::convertDatesOfFebruary($day, $hour, $morning);
            case 3:
                return self::convertDatesOfMarch($day, $hour, $morning, $isLeapYear);
            case 10:
                return self::convertDatesOfOctober($day, $hour, $morning);
            case 11:
            default :
                return self::convertDatesOfNovemberOrDecember($day, $hour, $morning, $month - 4);
        }
    }

    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @param $bnMonth
     * @return array
     */
    private static function convertDatesOfJanuaryOrApril($day, $hour, $morning, $bnMonth)
    {
        return self::handleCommonConversionLogic($day, $hour, $morning, $bnMonth, array(13, 16, 14, 16));
    }

    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @param $bnMonth
     * @param $offsetsArray
     * @return array
     */
    private static function handleCommonConversionLogic($day, $hour, $morning, $bnMonth, $offsetsArray)
    {
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
     * @param $bnMonth
     * @return array
     */
    private static function convertDatesOfMayOrJune($day, $hour, $morning, $bnMonth)
    {
        return self::handleCommonConversionLogic($day, $hour, $morning, $bnMonth, array(14, 16, 15, 16));
    }

    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @param $bnMonth
     * @return array
     */
    private static function convertDatesOfJulyOrAugustOrSeptember($day, $hour, $morning, $bnMonth)
    {
        return self::handleCommonConversionLogic($day, $hour, $morning, $bnMonth, array(15, 15, 16, 15));
    }

    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @return array
     */
    private static function convertDatesOfFebruary($day, $hour, $morning)
    {
        return self::handleCommonConversionLogic($day, $hour, $morning, 10, array(12, 17, 13, 17));
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

    private static function convertDatesOfOctober($day, $hour, $morning)
    {
        return self::handleCommonConversionLogic($day, $hour, $morning, 6, array(15, 14, 16, 14));
    }

    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @param $bnMonth
     * @return array
     */
    private static function convertDatesOfNovemberOrDecember($day, $hour, $morning, $bnMonth)
    {
        return self::handleCommonConversionLogic($day, $hour, $morning, $bnMonth, array(14, 15, 15, 15));
    }

    /**
     * @param $enMonth
     * @param $day
     * @param $hour
     * @param $morning
     * @param \DateTime $time
     * @return int
     */
    private static function getBengaliYear($enMonth, $day, $hour, $morning, \DateTime $time)
    {
        $engYear = (int)$time->format('Y');

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
}