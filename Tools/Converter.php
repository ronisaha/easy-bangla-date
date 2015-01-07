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

class Converter
{
    /**
     * @param \DateTime $time
     * @param int $morning
     * @return array
     */
    public static function getBengaliDateMonthYear(\DateTime $time, $morning = 6) {

        $enMonth = $time->format('n');
        $day = (int)$time->format('j');
        $hour = (int)$time->format('G');

        list($bnDate, $bnMonth) = self::getBengaliDateAndMonth($enMonth, $day, $hour, $morning, $time->format('L'));

        return array(
            'date'   => $bnDate,
            'month'  => $bnMonth,
            'year'   => self::getBengaliYear($enMonth, $day, $hour, $morning, $time)
        );
    }


    /**
     * @param $month
     * @param $day
     * @param $hour
     * @param $morning
     * @param $isLeapYear
     * @return array
     */
    private static function getBengaliDateAndMonth($month, $day, $hour, $morning, $isLeapYear)
    {
        switch ($month) {
            case 1: return self::convertDatesOfJanuary($day, $hour, $morning);
            case 2: return self::convertDatesOfFebruary($day, $hour, $morning);
            case 3: return self::convertDatesOfMarch($day, $hour, $morning, $isLeapYear);
            case 4: return self::convertDatesOfApril($day, $hour, $morning);
            case 5: return self::convertDatesOfMay($day, $hour, $morning);
            case 6: return self::convertDatesOfJune($day, $hour, $morning);
            case 7: return self::convertDatesOfJuly($day, $hour, $morning);
            case 8: return self::convertDatesOfAugust($day, $hour, $morning);
            case 9: return self::convertDatesOfSeptember($day, $hour, $morning);
            case 10: return self::convertDatesOfOctober($day, $hour, $morning);
            case 11: return self::convertDatesOfNovember($day, $hour, $morning); break;
            default : return self::convertDatesOfDecember($day, $hour, $morning);
        }
    }


    /**
     * @param $enMonth
     * @param $day
     * @param $hour
     * @param $morning
     * @param \DateTime $time
     * @return int
     */
    private static function getBengaliYear($enMonth, $day, $hour, $morning, \DateTime $time)
    {
        $engYear = (int)$time->format('Y');

        if ($enMonth < 4) {
            return $engYear - 594;
        } else {
            if ($enMonth == 4) {
                if (($day < 14) || ($day == 14 && $hour < $morning))
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
     * @param $morning
     * @return array
     */
    private static function convertDatesOfJanuary($day, $hour, $morning)
    {
        if ($day >= 1 && $day <= 13) {
            if ($hour < $morning) {
                $date = $day + 16;
            } else {
                $date = $day + 17;
            }
            $month = 9;
        } else {
            if ($day == 14 && $hour < $morning) {
                $date = $day + 16;
                $month = 9;
            } else {
                if ($hour < $morning) {
                    $date = $day - 14;
                } else {
                    $date = $day - 13;
                }
                $month = 10;
            }
        }

        return array($date, $month);
    }

    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @return array
     */
    private static function convertDatesOfFebruary($day, $hour, $morning)
    {
        if ($day >= 1 && $day <= 12) {
            if ($hour < $morning) {
                $date = $day + 17;
            } else {
                $date = $day + 18;
            }
            $month = 10;
        } else {
            if ($day == 13 && $hour < $morning) {
                $date = $day + 17;
                $month = 10;
            } else {
                if ($hour < $morning) {
                    $date = $day - 13;
                } else {
                    $date = $day - 12;
                }
                $month = 11;
            }
        }

        return array($date, $month);
    }

    /**
     * @param $day
     * @param $hour
     * @param $morning
     * @param $isLeapYear
     * @return array
     */
    private static function convertDatesOfMarch($day, $hour, $morning, $isLeapYear)
    {
        if ($day >= 1 && $day <= 14) {
            if ($hour < $morning) {
                if (!$isLeapYear) {
                    $date = $day + 15;
                } else {
                    $date = $day + 16;
                }
                $month = 11;
            } else {
                if (!$isLeapYear) {
                    $date = $day + 16;
                } else {
                    $date = $day + 17;
                }
                $month = 11;
            }
        } else {
            if ($day == 15 && $hour < $morning) {
                if (!$isLeapYear) {
                    $date = $day + 15;
                } else {
                    $date = $day + 16;
                }
                $month = 11;
            } else {
                if ($hour < $morning) {
                    $date = $day - 15;
                } else {
                    $date = $day - 14;
                }
                $month = 12;
            }
        }

        return array($date, $month);
    }

    private static function convertDatesOfApril($day, $hour, $morning)
    {
        if ($day >= 1 && $day <= 13) {
            if ($hour < $morning) {
                $date = $day + 16;
            } else {
                $date = $day + 17;
            }
            $month = 12;
        } else {
            if ($day == 14 && $hour < $morning) {
                $date = $day + 16;
                $month = 12;
            } else {
                if ($hour < $morning) {
                    $date = $day - 14;
                } else {
                    $date = $day - 13;
                }
                $month = 1;
            }
        }

        return array($date, $month);
    }

    private static function convertDatesOfMay($day, $hour, $morning)
    {
        if ($day >= 1 && $day <= 14) {
            if ($hour < $morning) {
                $date = $day + 16;
            } else {
                $date = $day + 17;
            }
            $month = 1;
        } else {
            if ($day == 15 && $hour < $morning) {
                $date = $day + 16;
                $month = 1;
            } else {
                if ($hour < $morning) {
                    $date = $day - 15;
                } else {
                    $date = $day - 14;
                }
                $month = 2;
            }
        }

        return array($date, $month);
    }

    private static function convertDatesOfJune($day, $hour, $morning)
    {
        if ($day >= 1 && $day <= 14) {
            if ($hour < $morning) {
                $date = $day + 16;
            } else {
                $date = $day + 17;
            }
            $month = 2;
        } else {
            if ($day == 15 && $hour < $morning) {
                $date = $day + 16;
                $month = 2;
            } else {
                if ($hour < $morning) {
                    $date = $day - 15;
                } else {
                    $date = $day - 14;
                }
                $month = 3;
            }
        }

        return array($date, $month);
    }

    private static function convertDatesOfJuly($day, $hour, $morning)
    {
        if ($day >= 1 && $day <= 15) {
            if ($hour < $morning) {
                $date = $day + 15;
            } else {
                $date = $day + 16;
            }
            $month = 3;
        } else {
            if ($day == 16 && $hour < $morning) {
                $date = $day + 15;
                $month = 3;
            } else {
                if ($hour < $morning) {
                    $date = $day - 16;
                } else {
                    $date = $day - 15;
                }
                $month = 4;
            }
        }

        return array($date, $month);
    }

    private static function convertDatesOfAugust($day, $hour, $morning)
    {
        if ($day >= 1 && $day <= 15) {
            if ($hour < $morning) {
                $date = $day + 15;
            } else {
                $date = $day + 16;
            }
            $month = 4;
        } else {
            if ($day == 16 && $hour < $morning) {
                $date = $day + 15;
                $month = 4;
            } else {
                if ($hour < $morning) {
                    $date = $day - 16;
                } else {
                    $date = $day - 15;
                }
                $month = 5;
            }
        }

        return array($date, $month);
    }

    private static function convertDatesOfSeptember($day, $hour, $morning)
    {
        if ($day >= 1 && $day <= 15) {
            if ($hour < $morning) {
                $date = $day + 15;
            } else {
                $date = $day + 16;
            }
            $month = 5;
        } else {
            if ($day == 16 && $hour < $morning) {
                $date = $day + 15;
                $month = 5;
            } else {
                if ($hour < $morning) {
                    $date = $day - 16;
                } else {
                    $date = $day - 15;
                }
                $month = 6;
            }
        }

        return array($date, $month);
    }

    private static function convertDatesOfOctober($day, $hour, $morning)
    {
        if ($day >= 1 && $day <= 15) {
            if ($hour < $morning) {
                $date = $day + 14;
            } else {
                $date = $day + 15;
            }
            $month = 6;
        } else {
            if ($day == 16 && $hour < $morning) {
                $date = $day + 14;
                $month = 6;
            } else {
                if ($hour < $morning) {
                    $date = $day - 16;
                } else {
                    $date = $day - 15;
                }
                $month = 7;
            }
        }

        return array($date, $month);
    }

    private static function convertDatesOfNovember($day, $hour, $morning)
    {
        if ($day >= 1 && $day <= 14) {
            if ($hour < $morning) {
                $date = $day + 15;
            } else {
                $date = $day + 16;
            }
            $month = 7;
        } else {
            if ($day == 15 && $hour < $morning) {
                $date = $day + 15;
                $month = 7;
            } else {
                if ($hour < $morning) {
                    $date = $day - 15;
                } else {
                    $date = $day - 14;
                }
                $month = 8;
            }
        }

        return array($date, $month);
    }

    private static function convertDatesOfDecember($day, $hour, $morning)
    {
        if ($day >= 1 && $day <= 14) {
            if ($hour < $morning) {
                $date = $day + 15;
            } else {
                $date = $day + 16;
            }
            $month = 8;
        } else {
            if ($day == 15 && $hour < $morning) {
                $date = $day + 15;
                $month = 8;
            } else {
                if ($hour < $morning) {
                    $date = $day - 15;
                } else {
                    $date = $day - 14;
                }
                $month = 9;
            }
        }

        return array($date, $month);
    }
}