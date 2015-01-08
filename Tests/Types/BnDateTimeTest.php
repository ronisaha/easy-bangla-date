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
        $object = new BnDateTime("2015-01-01 05:00:00", new \DateTimeZone('Asia/Dhaka'));
        $this->assertEquals("১৭", $object->format('d'));
        $object->setMorning(4);
        $this->assertEquals("১৮", $object->format('d'));
    }

}
