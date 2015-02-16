<?php

/*
 * This file is part of the EasyBanglaDate package.
 *
 * Copyright (c) 2015 Roni Saha
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace EasyBanglaDateTests\Types;

use EasyBanglaDateTests\Utils\CsvFileIterator;
use EasyBanglaDate\Types\DateTime;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{

    public function flagDataProvider()
    {
        return new CsvFileIterator(__DIR__ . '/../Resources/en_flag_data.csv');
    }

    /**
     * @dataProvider flagDataProvider
     * @param $time
     * @param $flag
     * @param $expected
     */
    public function testFormat($time, $flag, $expected)
    {
        $object = new DateTime($time, new \DateTimeZone('Asia/Dhaka'));
        $this->assertEquals($expected, $object->format($flag));
    }

    public function testEnglishDateTimeObject()
    {
        $object = new DateTime("2015-01-01 08:00:00", new \DateTimeZone('Asia/Dhaka'));

        $this->assertEquals("01-01-2015 08:00:00", $object->enFormat('d-m-Y H:i:s'));
    }

}
