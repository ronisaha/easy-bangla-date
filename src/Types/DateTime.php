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

class DateTime extends BaseDateTime
{

    protected function replaceDateNumbers($format)
    {
        $numbersItems = array('d', 'j', 'm', 'n', 't', 'Y', 'y');
        $out = $format;

        foreach ($numbersItems as $item) {
            $out = str_replace($item, $this->_format($item), $out);
        }

        return $out;
    }

    protected function replaceMonths($format)
    {
        return $this->getInBengali($format, array('F', 'M'));
    }

    public function format($format)
    {
        $out = $this->replaceTimes($format);
        $out = $this->replaceTimePrefix($out);
        $out = $this->replaceSuffix($out);
        $out = $this->replaceDateNumbers($out);
        $out = $this->replaceMonths($out);
        $out = $this->replaceDays($out);
        $out = $this->replaceMeridian($out);

        return $this->translateNumbers($out);
    }

    public function enFormat($format) {
        return $this->_format($format);
    }
}