<?php

/*
 * This file is part of the EasyBanglaDate package.
 *
 * Copyright (c) 2015 Roni Saha
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace EasyBanglaDate\Types;

use EasyBanglaDate\Common\BaseDateTime;
use EasyBanglaDate\Tools\Converter;

class BnDateTime  extends BaseDateTime
{

    protected static $bnMonths = array('', 'বৈশাখ','জ্যৈষ্ঠ','আষাঢ়','শ্রাবণ','ভাদ্র','আশ্বিন','কার্তিক','অগ্রহায়ণ','পৌষ','মাঘ','ফাল্গুন','চৈত্র');
    protected static $daysInMonth = array('', 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 30, 30);

    private $morning = 6;

    protected function replaceDateNumbers($format)
    {
        $bnDate = $this->getBengaliDateMonthYear();

        $format = str_replace('S', $this->getSuffix($bnDate['date']), $format);
        $format = str_replace('d', str_pad($bnDate['date'], 2, 0, STR_PAD_LEFT), $format);
        $format = str_replace('j', $bnDate['date'], $format);
        $format = str_replace('t', $this->getDayInMonth($bnDate['month']), $format);
        $format = str_replace('m', str_pad($bnDate['month'], 2, 0, STR_PAD_LEFT), $format);
        $format = str_replace('n', $bnDate['month'], $format);
        $format = str_replace('F', self::$bnMonths[$bnDate['month']], $format);
        $format = str_replace('M', self::$bnMonths[$bnDate['month']], $format);
        $format = str_replace('Y', $bnDate['year'], $format);
        $format = str_replace('y', substr($bnDate['year'], -2), $format);

        return $format;
    }

    public function format($format)
    {
        $out = $this->replaceTimes($format);
        $out = $this->replaceTimePrefix($out);
        $out = $this->replaceDateNumbers($out);
        $out = $this->replaceDays($out);
        $out = $this->replaceMeridian($out);

        return $this->translateNumbers($out);
    }

    /**
     * @param int $morning
     */
    public function setMorning($morning)
    {
        $this->morning = $morning;
    }

    private function getBengaliDateMonthYear()
    {
        return Converter::getBengaliDateMonthYear($this->getNativeDateTimeObject(), $this->morning);
    }

    /**
     * @return \DateTime
     */
    private function getNativeDateTimeObject()
    {
        $dateTime = new \DateTime();

        return $dateTime
            ->setTimestamp($this->getTimestamp())
            ->setTimezone($this->getTimezone())
            ;
    }

    private function getDayInMonth($month)
    {
        if($month == 11 && $this->_format('L')) {
            return 31;
        }

        return self::$daysInMonth[$month];
    }
}