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


class MagicNumbers
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

    public static function getMagicArrayForMonth($month)
    {
        return self::$magicArray[self::$indexByMonth[$month]];
    }
}