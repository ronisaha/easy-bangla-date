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
    /** @var  DateTime */
    private $_dateTime;

    /** @var  \DateTime */
    private $_phpDateTime;

    protected static $bnMonths = array('', 'বৈশাখ','জ্যৈষ্ঠ','আষাঢ়','শ্রাবণ','ভাদ্র','আশ্বিন','কার্তিক','অগ্রহায়ণ','পৌষ','মাঘ','ফাল্গুন','চৈত্র');
    protected static $daysInMonth = array('', 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 30, 30);

    private $morning = 6;

    public function __construct($time = 'now', \DateTimeZone $timezone = null)
    {
        parent::__construct($time, $timezone);
        $this->_dateTime = new DateTime();
        $this->_phpDateTime = new \DateTime();
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

    public function setDate($year, $month, $day)
    {
        $engTime = Converter::getEnglishTimeFromBanglaDate(
            $this->getNativeDateTimeObject(),
            array('day' => $day, 'month' => $month, 'year' => $year),
            $this->morning
        );

        $this->setTimestamp($engTime->getTimestamp());
    }

    /**
     * @return DateTime
     */
    public function getDateTime()
    {
        return $this->_dateTime
            ->setTimestamp($this->getTimestamp())
            ->setTimezone($this->getTimezone())
            ;
    }

    protected function replaceDateNumbers($format)
    {
        $bnDate = $this->getBengaliDateMonthYear();

        $format = str_replace('S', $this->getSuffix($bnDate['day']), $format);
        $format = str_replace('d', str_pad($bnDate['day'], 2, 0, STR_PAD_LEFT), $format);
        $format = str_replace('j', $bnDate['day'], $format);
        $format = str_replace('t', $this->getDayInMonth($bnDate['month']), $format);
        $format = str_replace('m', str_pad($bnDate['month'], 2, 0, STR_PAD_LEFT), $format);
        $format = str_replace('n', $bnDate['month'], $format);
        $format = str_replace('F', self::$bnMonths[$bnDate['month']], $format);
        $format = str_replace('M', self::$bnMonths[$bnDate['month']], $format);
        $format = str_replace('Y', $bnDate['year'], $format);
        $format = str_replace('y', substr($bnDate['year'], -2), $format);

        return $format;
    }

    /**
     * @return DateTime
     */
    private function getNativeDateTimeObject()
    {
        return $this->_phpDateTime
            ->setTimestamp($this->getTimestamp())
            ->setTimezone($this->getTimezone())
            ;
    }

    private function getBengaliDateMonthYear()
    {
        return Converter::getBengaliDateMonthYear($this->getNativeDateTimeObject(), $this->morning);
    }

    private function getDayInMonth($month)
    {
        if($month == 11 && $this->_format('L')) {
            return 31;
        }

        return self::$daysInMonth[$month];
    }
}