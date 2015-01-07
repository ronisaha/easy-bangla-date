<?php

/*
 * This file is part of the EasyBanglaDate package.
 *
 * Copyright (c) 2015 Roni Saha
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace EasyBanglaDate;


class BnDateTime  extends BaseDateTime
{

    protected static $bnMonths = array('বৈশাখ','জ্যৈষ্ঠ','আষাঢ়','শ্রাবণ','ভাদ্র','আশ্বিন','কার্তিক','অগ্রহায়ণ','পৌষ','মাঘ','ফাল্গুন','চৈত্র');

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
     * @return array
     */
    private function getBengaliDateMonthYear() {
        $monthNumber = $this->_format('n');
        $day = (int)$this->_format('j');
        $hour = (int)$this->_format('G');

        list($date, $month) = $this->getBengaliDateAndMonth($day, $hour);

        $year = $this->getBengaliYear($monthNumber, $day, $hour);

        // Returning results as array
        return array(
            'date'   => $date,
            'month'  => $month,
            'year'   => $year
        );
    }

    /**
     * @TODO: Implement get day in month
     */
    private function getDayInMonth($month)
    {
        return "";
    }

    /**
     * @param $day
     * @param $hour
     * @return array
     */
    private function getBengaliDateAndMonth($day, $hour)
    {
        switch ((int)$this->_format('n')) {
            case 1: return $this->convertDatesOfJanuary($day, $hour);
            case 2: return $this->convertDatesOfFebruary($day, $hour);
            case 3: return $this->convertDatesOfMarch($day, $hour);
            case 4: return $this->convertDatesOfApril($day, $hour);
            case 5: return $this->convertDatesOfMay($day, $hour);
            case 6: return $this->convertDatesOfJune($day, $hour);
            case 7: return $this->convertDatesOfJuly($day, $hour);
            case 8: return $this->convertDatesOfAugust($day, $hour);
            case 9: return $this->convertDatesOfSeptember($day, $hour);
            case 10: return $this->convertDatesOfOctober($day, $hour);
            case 11: return $this->convertDatesOfNovember($day, $hour);
            case 12: return $this->convertDatesOfDecember($day, $hour);
        }
    }

    /**
     * @param $monthNumber
     * @param $day
     * @param $hour
     * @return string
     */
    private function getBengaliYear($monthNumber, $day, $hour)
    {
        $engYear = $this->_format("Y");

        if ($monthNumber < 4) {
            return $engYear - 594;
        } else {
            if ($monthNumber == 4) {
                if (($day < 14) || ($day == 14 && $hour < $this->morning))
                    return $engYear - 594;
                else
                    return $engYear - 593;
            }

            return $engYear - 593;
        }
    }

    /**
     * @param $day
     * @param $hour
     * @return array
     */
    private function convertDatesOfJanuary($day, $hour)
    {
        if ($day >= 1 && $day <= 13) {
            if ($hour < $this->morning) {
                $date = $day + 16;
            } else {
                $date = $day + 17;
            }
            $month = 8;
        } else {
            if ($day == 14 && $hour < $this->morning) {
                $date = $day + 16;
                $month = 8;
            } else {
                if ($hour < $this->morning) {
                    $date = $day - 14;
                } else {
                    $date = $day - 13;
                }
                $month = 9;
            }
        }

        return array($date, $month);
    }

    private function convertDatesOfFebruary($day, $hour)
    {
        if ($day >= 1 && $day <= 12) {
            if ($hour < $this->morning) {
                $date = $day + 17;
            } else {
                $date = $day + 18;
            }
            $month = 9;
        } else {
            if ($day == 13 && $hour < $this->morning) {
                $date = $day + 17;
                $month = 9;
            } else {
                if ($hour < $this->morning) {
                    $date = $day - 13;
                } else {
                    $date = $day - 12;
                }
                $month = 10;
            }
        }

        return array($date, $month);
    }

    private function convertDatesOfMarch($day, $hour)
    {
        $isLeapYear = $this->_format('L');

        if ($day >= 1 && $day <= 14) {
            if ($hour < $this->morning) {
                if (!$isLeapYear) {
                    $date = $day + 15;
                } else {
                    $date = $day + 16;
                }
                $month = 10;
            } else {
                if (!$isLeapYear) {
                    $date = $day + 16;
                } else {
                    $date = $day + 17;
                }
                $month = 10;
            }
        } else {
            if ($day == 15 && $hour < $this->morning) {
                if (!$isLeapYear) {
                    $date = $day + 15;
                } else {
                    $date = $day + 16;
                }
                $month = 10;
            } else {
                if ($hour < $this->morning) {
                    $date = $day - 15;
                } else {
                    $date = $day - 14;
                }
                $month = 11;
            }
        }

        return array($date, $month);
    }

    private function convertDatesOfApril($day, $hour)
    {
        if ($day >= 1 && $day <= 13) {
            if ($hour < $this->morning) {
                $date = $day + 16;
            } else {
                $date = $day + 17;
            }
            $month = 11;
        } else {
            if ($day == 14 && $hour < $this->morning) {
                $date = $day + 16;
                $month = 11;
            } else {
                if ($hour < $this->morning) {
                    $date = $day - 14;
                } else {
                    $date = $day - 13;
                }
                $month = 0;
            }
        }

        return array($date, $month);
    }

    private function convertDatesOfMay($day, $hour)
    {
        if ($day >= 1 && $day <= 14) {
            if ($hour < $this->morning) {
                $date = $day + 16;
            } else {
                $date = $day + 17;
            }
            $month = 0;
        } else {
            if ($day == 15 && $hour < $this->morning) {
                $date = $day + 16;
                $month = 0;
            } else {
                if ($hour < $this->morning) {
                    $date = $day - 15;
                } else {
                    $date = $day - 14;
                }
                $month = 1;
            }
        }

        return array($date, $month);
    }

    private function convertDatesOfJune($day, $hour)
    {
        if ($day >= 1 && $day <= 14) {
            if ($hour < $this->morning) {
                $date = $day + 16;
            } else {
                $date = $day + 17;
            }
            $month = 1;
        } else {
            if ($day == 15 && $hour < $this->morning) {
                $date = $day + 16;
                $month = 1;
            } else {
                if ($hour < $this->morning) {
                    $date = $day - 15;
                } else {
                    $date = $day - 14;
                }
                $month = 2;
            }
        }

        return array($date, $month);
    }

    private function convertDatesOfJuly($day, $hour)
    {
        if ($day >= 1 && $day <= 15) {
            if ($hour < $this->morning) {
                $date = $day + 15;
            } else {
                $date = $day + 16;
            }
            $month = 2;
        } else {
            if ($day == 16 && $hour < $this->morning) {
                $date = $day + 15;
                $month = 2;
            } else {
                if ($hour < $this->morning) {
                    $date = $day - 16;
                } else {
                    $date = $day - 15;
                }
                $month = 3;
            }
        }

        return array($date, $month);
    }

    private function convertDatesOfAugust($day, $hour)
    {
        if ($day >= 1 && $day <= 15) {
            if ($hour < $this->morning) {
                $date = $day + 15;
            } else {
                $date = $day + 16;
            }
            $month = 3;
        } else {
            if ($day == 16 && $hour < $this->morning) {
                $date = $day + 15;
                $month = 3;
            } else {
                if ($hour < $this->morning) {
                    $date = $day - 16;
                } else {
                    $date = $day - 15;
                }
                $month = 4;
            }
        }

        return array($date, $month);
    }

    private function convertDatesOfSeptember($day, $hour)
    {
        if ($day >= 1 && $day <= 15) {
            if ($hour < $this->morning) {
                $date = $day + 15;
            } else {
                $date = $day + 16;
            }
            $month = 4;
        } else {
            if ($day == 16 && $hour < $this->morning) {
                $date = $day + 15;
                $month = 4;
            } else {
                if ($hour < $this->morning) {
                    $date = $day - 16;
                } else {
                    $date = $day - 15;
                }
                $month = 5;
            }
        }

        return array($date, $month);
    }

    private function convertDatesOfOctober($day, $hour)
    {
        if ($day >= 1 && $day <= 15) {
            if ($hour < $this->morning) {
                $date = $day + 14;
            } else {
                $date = $day + 15;
            }
            $month = 5;
        } else {
            if ($day == 16 && $hour < $this->morning) {
                $date = $day + 14;
                $month = 5;
            } else {
                if ($hour < $this->morning) {
                    $date = $day - 16;
                } else {
                    $date = $day - 15;
                }
                $month = $this->morning;
            }
        }

        return array($date, $month);
    }

    private function convertDatesOfNovember($day, $hour)
    {
        if ($day >= 1 && $day <= 14) {
            if ($hour < $this->morning) {
                $date = $day + 15;
            } else {
                $date = $day + 16;
            }
            $month = $this->morning;
        } else {
            if ($day == 15 && $hour < $this->morning) {
                $date = $day + 15;
                $month = $this->morning;
            } else {
                if ($hour < $this->morning) {
                    $date = $day - 15;
                } else {
                    $date = $day - 14;
                }
                $month = 7;
            }
        }

        return array($date, $month);
    }

    private function convertDatesOfDecember($day, $hour)
    {
        if ($day >= 1 && $day <= 14) {
            if ($hour < $this->morning) {
                $date = $day + 15;
            } else {
                $date = $day + 16;
            }
            $month = 7;
        } else {
            if ($day == 15 && $hour < $this->morning) {
                $date = $day + 15;
                $month = 7;
            } else {
                if ($hour < $this->morning) {
                    $date = $day - 15;
                } else {
                    $date = $day - 14;
                }
                $month = 8;
            }
        }

        return array($date, $month);
    }

    /**
     * @param int $morning
     */
    public function setMorning($morning)
    {
        $this->morning = $morning;
    }
}