<?php

/*
 * This file is part of the EasyBanglaDate package.
 *
 * Copyright (c) 2015 Roni Saha
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyBanglaDate\Common;


abstract class BaseDateTime extends  \DateTime
{
    protected static $enDigit = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

    protected static $bnDigit = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');

    protected static $enArray = array(
        'l' => array('Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
        'D' => array('Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'),
        'F' => array('January','February','March','April','May','June','July','August','September','October','November','December'),
        'M' => array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec')
    );

    protected static $bnArray = array(
        'l' => array('শনিবার', 'রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহঃস্পতিবার', 'শুক্রবার'),
        'D' => array('শনি', 'রবি', 'সোম', 'মঙ্গল', 'বুধ', 'বৃহ', 'শুক্র'),
        'F' => array('জানুয়ারী','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর'),
        'M' => array('জানু','ফেব্রু','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টে','অক্টো','নভে','ডিসে')
    );

    protected static $enAmPm = array('am', 'pm');

    protected static $bnAmPM = array('পূর্বাহ্ন', 'অপরাহ্ন');
    protected static $bnSuffix = array('', 'লা', 'রা', 'রা', 'ঠা', 'ই', 'শে');
    protected static $bnPrefix = array('ভোর', 'সকাল', 'দুপুর', 'বিকাল', 'সন্ধ্যা', 'রাত');
    protected static $enTimeSlot = array('Dawn', 'Morning', 'Noon', 'Afternoon', 'Evening', 'Night');

    public function __construct($time = 'now', \DateTimeZone $timezone = null)
    {
        parent::__construct($time, $timezone);
    }

    protected function translateNumbers($number)
    {
        return str_replace(BaseDateTime::$enDigit, BaseDateTime::$bnDigit, $number);
    }

    protected function replaceSuffix($format)
    {
        return str_replace('S', $this->getBnSuffix($this->_format('j')), $format);
    }

    protected function _format($format) {
        return parent::format($format);
    }

    protected function getBnSuffix($date)
    {
        $index = (int)$date;

        if ($index < 19 && $index > 4) {
            $index = 5;
        } elseif($index > 18) {
            $index = 6;
        }

        return BaseDateTime::$bnSuffix[$index];
    }

    protected function replaceTimes($format)
    {
        $numbersItems = array('G', 'g', 'H', 'h', 'i', 's');
        $out = $format;

        foreach ($numbersItems as $item) {
            $out = str_replace($item, $this->_format($item), $out);
        }

        return $out;
    }

    protected function getAmPm()
    {
        return str_replace(BaseDateTime::$enAmPm, BaseDateTime::$bnAmPM, $this->_format('a'));
    }

    /**
     * @param $format
     * @param $items
     * @return mixed
     */
    protected function getInBengali($format, $items)
    {
        foreach ($items as $item) {
            $format = str_replace(
                $item,
                str_replace(BaseDateTime::$enArray[$item], BaseDateTime::$bnArray[$item], $this->_format($item)),
                $format
            );
        }

        return $format;
    }

    protected function replaceMeridian($str)
    {

        $mValue = $this->getAmPm();

        $str = str_replace('a', $mValue, $str);
        $str = str_replace('A', $mValue, $str);

        return $str;
    }

    protected function replaceDays($format)
    {
        return $this->getInBengali($format, array('D', 'l'));
    }

    protected function replaceTimePrefix($str)
    {
        return str_replace('b', $this->getTimePrefix(), $str);
    }

    protected function getTimePrefix()
    {
        return BaseDateTime::$bnPrefix[$this->getPrefixIndex()];
    }

    protected function getPrefixIndex()
    {
        $hour = (int)$this->_format('G');
        $items = count(self::$enTimeSlot) - 1;

        for ($i = 0; $i < $items; $i++) {
            if ($this->isInTimeSlot(self::$enTimeSlot[$i], $hour)) {
                return $i;
            }
        }

        return $i;
    }


    /**
     * @param $hour
     * @return bool
     */
    protected function isDawn($hour)
    {
        return $hour < 6 && $hour > 3;
    }

    /**
     * @param $hour
     * @return bool
     */
    protected function isMorning($hour)
    {
        return $hour < 12 && $hour > 5;
    }

    /**
     * @param $hour
     * @return bool
     */
    protected function isNoon($hour)
    {
        return $hour < 15 && $hour > 11;
    }

    /**
     * @param $hour
     * @return bool
     */
    protected function isAfternoon($hour)
    {
        return $hour < 18 && $hour > 14;
    }

    /**
     * @param $hour
     * @return bool
     */
    protected function isEvening($hour)
    {
        return $hour < 20 && $hour > 17;
    }

    /**
     * @param $slot
     * @param $hour
     * @return mixed
     */
    protected function isInTimeSlot($slot, $hour)
    {
        return call_user_func(array($this, "is{$slot}"), $hour);
    }
}