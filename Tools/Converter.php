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
        $offset = false;

        if ($day >= 1 && $day <= 14) {
            $offset = self::getNextDayIfNot(15, $hour < $morning);
        } elseif ($day == 15 && $hour < $morning) {
            $offset = 15;
        }

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