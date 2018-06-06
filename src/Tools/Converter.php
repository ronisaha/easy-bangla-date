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

        list($bnDate, $bnMonth) = BanglaCalender::getBengaliDateAndMonth($enMonth, $day, $hour, $morning, $time->format('L'));

        return array(
            'year'  => self::getBengaliYear($enMonth, $day, $hour, $morning, (int)$time->format('Y')),
            'month' => $bnMonth,
            'day'  => $bnDate,
        );
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

}