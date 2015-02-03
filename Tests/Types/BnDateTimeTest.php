<?php

/*
 * This file is part of the EasyBanglaDate package.
 *
 * Copyright (c) 2015 Roni Saha
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace EasyBanglaDate\Tests\Types;

use EasyBanglaDate\Tests\Utils\CsvFileIterator;
use EasyBanglaDate\Types\BnDateTime;

class BnDateTimeTest extends \PHPUnit_Framework_TestCase
{
    public function flagDataProvider()
    {
        return new CsvFileIterator(__DIR__ . '/../Resources/bn_flag_data.csv');
    }

    public function dataProviderForFlag_t()
    {
        return new CsvFileIterator(__DIR__ . '/../Resources/bn_flag_t_data.csv');
    }

    /**
     * @dataProvider flagDataProvider
     * @param $time
     * @param $flag
     * @param $expected
     */
    public function testFormat($time, $flag, $expected)
    {
        $object = new BnDateTime($time, new \DateTimeZone('Asia/Dhaka'));
        $this->assertEquals($expected, $object->format($flag));
    }

    /**
     * @dataProvider dataProviderForFlag_t
     * @param $time
     * @param $expected
     */
    public function testDayInMonth($time, $expected)
    {
        $object = new BnDateTime($time, new \DateTimeZone('Asia/Dhaka'));
        $this->assertEquals($expected, $object->format('t'));
    }

    public function testCustomMorningTest()
    {
        $object = $this->createObject("2015-01-01 05:00:00");
        $this->assertEquals("১৭", $object->format('d'));
        $object->setMorning(4);
        $this->assertEquals("১৮", $object->format('d'));
    }

    public function testBanglaDateSetting()
    {
        $object = $this->createObjectAndSetBanglaDate("2015-01-01 08:00:00", 1405,9,21);
        $this->assertEquals("২১-০৯-১৪০৫ ০৮:০০:০০", $object->format('d-m-Y H:i:s'));
    }

    public function testDateTimeObject()
    {
        $object = $this->createObjectAndSetBanglaDate("2015-01-01 08:00:00", 1421,1,1);
        $this->assertEquals("১৪-০৪-২০১৪ ০৮:০০:০০", $object->getDateTime()->format('d-m-Y H:i:s'));
    }

    protected function createObjectAndSetBanglaDate($time, $year, $month, $day)
    {
        return $this->createObject($time)->setDate($year, $month, $day);
    }

    /**
     * @param $time
     * @return BnDateTime
     */
    protected function createObject($time)
    {
        return new BnDateTime($time, new \DateTimeZone('Asia/Dhaka'));
    }
}
