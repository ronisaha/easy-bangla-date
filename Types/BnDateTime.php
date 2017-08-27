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

    protected static $bnMonths = array(
        'F' => array('বৈশাখ','জ্যৈষ্ঠ','আষাঢ়','শ্রাবণ','ভাদ্র','আশ্বিন','কার্তিক','অগ্রহায়ণ','পৌষ','মাঘ','ফাল্গুন','চৈত্র'),
        'M' => array('বৈশাখ','জ্যৈষ্ঠ','আষাঢ়','শ্রাবণ','ভাদ্র','আশ্বিন','কার্তিক','অগ্র','পৌষ','মাঘ','ফাল্গুন','চৈত্র')
    );

    protected static $enMonths = array(
        'F' => array('Boishakh','Joishtha','Ashar','Srabon','Bhadra','Ashwin','Kartik','Ogrohayon','Poush','Magh','Falgun','Choitra'),
        'M' => array('Boi','Joi','Ash','Sra','Bha','Ash','Kar','Ogr','Pou','Mag','Fal','Cho')
    );
    protected static $daysInMonth = array('', 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 30, 30);
    protected static $enSuffix = array('th', 'st', 'nd', 'rd');

    protected static $parameterList = array('a', 'A', 'l', 'D');

    private $morning = 6;

    public static function create($time = 'now')
    {
        $dateTime = null;

        if (is_string($time)) {
            return new static($time);
        } elseif ($time instanceof BnDateTime) {
            return $time;
        } elseif ($time instanceof \DateTime) {
            $dateTime = new static();
            $dateTime->setTimestamp($time->getTimestamp());
            $dateTime->setTimezone($time->getTimezone());
        } elseif (is_int($time)) {
            $dateTime = new static();
            $dateTime->setTimestamp($time);
        }

        return $dateTime;
    }

    public function __construct($time = 'now', \DateTimeZone $timezone = null)
    {
        parent::__construct($time, $timezone);
        $this->_dateTime = new DateTime();
        $this->_phpDateTime = new \DateTime();
    }

    public function format($format)
    {
        $bnDate = $this->getBengaliDateMonthYear();

        $out = $this->replaceTimes($format);
        $out = $this->replaceTimePrefix($out);
        $out = $this->replaceBnSuffix($out, $bnDate);
        $out = $this->replaceDateNumbers($out, $bnDate);
        $out = $this->replaceMonthString($out, $bnDate, self::$bnMonths, '%s');
        $out = $this->replaceDays($out);
        $out = $this->replaceMeridian($out);

        return $this->translateNumbers($out);
    }

    public function enFormat($format)
    {
        $bnDate = $this->getBengaliDateMonthYear();

        $out = $this->replaceTimes($format);
        $out = $this->replaceDateNumbers($out, $bnDate);
        $out = $this->replaceToPlaceHolders($out);
        $out = $this->replaceMonthString($out, $bnDate, self::$enMonths, '{%s}');
        $out = str_replace('{S}', $this->getEnSuffix($bnDate['day']), $out);

        return $this->replacePlaceHolders($out);
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

        return $this;
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

    private function replaceToPlaceHolders($out) {
        $paramList = array_merge(self::$parameterList , array("S", "M", "F"));

        foreach($paramList as $item) {
            $out = str_replace($item, '{' . $item .'}', $out);
        }

        return $out;
    }

    private function replacePlaceHolders($out) {

        foreach(self::$parameterList as $item) {
            $out = str_replace('{' . $item .'}', $this->_format($item), $out);
        }

        return $out;
    }

    /**
     * @param $format
     * @param $bnDate
     * @return mixed
     */
    protected function replaceBnSuffix($format, $bnDate)
    {
        return str_replace('S', $this->getBnSuffix((int)$bnDate['day']), $format);
    }

    protected function replaceDateNumbers($format, $bnDate = array())
    {
        $format = str_replace('d', str_pad($bnDate['day'], 2, 0, STR_PAD_LEFT), $format);
        $format = str_replace('j', $bnDate['day'], $format);
        $format = str_replace('t', $this->getDayInMonth($bnDate['month']), $format);
        $format = str_replace('m', str_pad($bnDate['month'], 2, 0, STR_PAD_LEFT), $format);
        $format = str_replace('n', $bnDate['month'], $format);
        $format = str_replace('Y', $bnDate['year'], $format);
        $format = str_replace('y', substr($bnDate['year'], -2), $format);

        return $format;
    }

    /**
     * @return \DateTime
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

    protected function getEnSuffix($num) {

        $index = $this->getSuffixArrayIndexFromNumber($num);

        if($index > 3) {
            $index = 0;
        }

        return self::$enSuffix[$index];
    }

    /**
     * @param $template
     * @param $bnDate
     * @param $monthArray
     * @param $keyTemplate
     * @return mixed
     */
    protected function replaceMonthString($template, $bnDate, $monthArray, $keyTemplate)
    {
        foreach(array('F','M') as $key){
            $template = str_replace(sprintf($keyTemplate, $key), $monthArray[$key][$bnDate['month'] - 1], $template);
        }

        return $template;
    }

    /**
     * @param $num
     * @return int
     */
    protected function getSuffixArrayIndexFromNumber($num)
    {
        if (in_array($num, array(11, 12, 13))) {
            return 0;
        }

        return ($num % 10);
    }
}