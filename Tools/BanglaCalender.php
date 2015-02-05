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


class BanglaCalender
{

    /**
     * @param $month
     * @param $day
     * @param $hour
     * @param $morning
     * @param $isLeapYear
     * @return array
     */
    public static function getBengaliDateAndMonth($month, $day, $hour, $morning, $isLeapYear)
    {
        if ($month == 3) {
            return self::convertDatesOfMarch($day, $hour, $morning, $isLeapYear);
        }

        return self::handleCommonConversionLogic($day, $hour, $morning, $month, MagicNumbers::getMagicArrayForMonth($month));
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
        $bnMonth = self::guessBanglaMonth($enMonth);

        if (self::is1stHalfOfMonth($day, $offsetsArray)) {
            return array(self::getNextDayIfNot($day + $offsetsArray[1], $hour < $morning), $bnMonth);
        }

        if (self::isUpComingMorning($day, $hour, $morning, $offsetsArray)) {
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
        $offset = self::getOffsetValueDependingOnDateAndMorningForMarch($day, $hour, $morning);

        return $offset ? array(self::getNextDayIfNot($day + $offset, !$isLeapYear), 11)
            : self::getDateForNextMonth($day, $hour, $morning, 11, 15);
    }

    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @return bool|int|mixed
     */
    private static function getOffsetValueDependingOnDateAndMorningForMarch($day, $hour, $morning)
    {
        if ($day >= 1 && $day <= 14) {
            return self::getNextDayIfNot(15, $hour < $morning);
        } elseif (self::isBefore15thMorning($day, $hour, $morning)) {
            return 15;
        }

        return false;
    }


    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @return bool
     */
    private static function isBefore15thMorning($day, $hour, $morning)
    {
        return $day == 15 && $hour < $morning;
    }

    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @param $offsetsArray
     * @return bool
     */
    private static function isUpComingMorning($day, $hour, $morning, $offsetsArray)
    {
        return $day == $offsetsArray[2] && $hour < $morning;
    }

    /**
     * @param $day
     * @param $offsetsArray
     * @return bool
     */
    private static function is1stHalfOfMonth($day, $offsetsArray)
    {
        return $day >= 1 && $day <= $offsetsArray[0];
    }

    /**
     * @param $month
     * @return mixed
     */
    private static function guessBanglaMonth($month)
    {
        return $month > 4 ? $month - 4 : $month + 8;
    }
}