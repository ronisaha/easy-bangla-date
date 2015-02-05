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
    private static $magicArray = array(
        '1_4_JanuaryOrApril' => array(13, 16, 14, 16),
        '2_February' => array(12, 17, 13, 17),
        '5_6_MayOrJune' => array(14, 16, 15, 16),
        '7_8_9_JulyOrAugustOrSeptember' => array(15, 15, 16, 15),
        '10_October' => array(15, 14, 16, 14),
        '11_12_NovemberOrDecember' => array(14, 15, 15, 15),
    );

    private static $indexByMonth = array(
        "0",
        '1_4_JanuaryOrApril',
        '2_February',
        "3",
        '1_4_JanuaryOrApril',
        '5_6_MayOrJune',
        '5_6_MayOrJune',
        '7_8_9_JulyOrAugustOrSeptember',
        '7_8_9_JulyOrAugustOrSeptember',
        '7_8_9_JulyOrAugustOrSeptember',
        '10_October',
        '11_12_NovemberOrDecember',
        '11_12_NovemberOrDecember',
    );

    public static function getEnglishTimeFromBanglaDate(\DateTime $time, $bnDateArray, $morning )
    {
        self::adjustYearAndMonth($time, $bnDateArray, $morning);

        $currentBnDate = self::getBengaliDateMonthYear($time, $morning);

        $diff = self::getDiffInDays($currentBnDate, $bnDateArray);

        if($diff == 0) {
            return $time;
        }

        $time->modify(sprintf('%+d day', $diff));

        return self::getEnglishTimeFromBanglaDate($time, $bnDateArray, $morning);

    }

    private static function getArrayValueDifference($lhs, $rhs)
    {
        $ret = array();

        foreach (array('year', 'month', 'day') as $key) {
            $ret[$key] = $rhs[$key] - $lhs[$key];
        }

        return $ret;
    }

    /**
     * @param \DateTime $time
     * @param int $morning
     * @return array
     */
    public static function getBengaliDateMonthYear(\DateTime $time, $morning)
    {

        $enMonth = $time->format('n');
        $day = (int)$time->format('j');
        $hour = (int)$time->format('G');

        list($bnDate, $bnMonth) = self::getBengaliDateAndMonth($enMonth, $day, $hour, $morning, $time->format('L'));

        return array(
            'year'  => self::getBengaliYear($enMonth, $day, $hour, $morning, (int)$time->format('Y')),
            'month' => $bnMonth,
            'day'  => $bnDate,
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

    private static function getMagicArrayForMonth($month)
    {
        return self::$magicArray[self::$indexByMonth[$month]];
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

        if (self::isUpCommingMorning($day, $hour, $morning, $offsetsArray)) {
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
        return $enMonth < 4 || self::isAprilButBefore14th($enMonth, $day, $hour, $morning);
    }

    /**
     * @param $month
     * @return mixed
     */
    private static function guessBanglaMonth($month)
    {
        return $month > 4 ? $month - 4 : $month + 8;
    }

    /**
     * @param \DateTime $time
     * @param $bnDateArray
     * @param $morning
     */
    protected static function adjustYearAndMonth(\DateTime $time, $bnDateArray, $morning)
    {
        $diffArray = self::getArrayValueDifference(
            self::getBengaliDateMonthYear($time, $morning),
            $bnDateArray
        );

        $time->modify(sprintf('%+d year %+d month', $diffArray['year'], $diffArray['month']));
    }

    private static function getDiffInDays($lhs , $rhs)
    {
        return self::getDaysFromDateArray($rhs) - self::getDaysFromDateArray($lhs);
    }

    private static function getDaysFromDateArray($arr)
    {
        $days = $arr['year'] * 365.25;

        if ($arr['month'] < 6) {
            $days += ($arr['month'] * 31);
        } else {
            $days += (5 * 31) + (($arr['month'] - 5) * 30);
        }

        return $days + $arr['day'];
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
     * @param $enMonth
     * @param $day
     * @param $hour
     * @param $morning
     * @return bool
     */
    private static function isAprilButBefore14th($enMonth, $day, $hour, $morning)
    {
        return ($enMonth == 4 && ($day < 14 || self::isBeforeMorningOf14thApril($day, $hour, $morning)));
    }

    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @return bool
     */
    private static function isBeforeMorningOf14thApril($day, $hour, $morning)
    {
        return ($day == 14 && $hour < $morning);
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
    private static function isUpCommingMorning($day, $hour, $morning, $offsetsArray)
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
}