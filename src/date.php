<?php

declare(strict_types=1);

namespace fab;

class date
{

    public static function date_to_invert($source_date, $only_date = false)
    {
        if (!empty($source_date)) {
            if (is_array($source_date)) {
                foreach ($source_date as $Key => $Value) {
                    if (!empty($Value)) {
                        if ((strpos($Key, "data_")) === 0 || (strpos($Key, "date_")) === 0) {
                            $date[$Key] = self::date_to_invert($Value, $only_date);
                        }
                    }
                }
            } else {
                if (!self::is_zero_date($source_date)) {
                    $source_hi = ' H:i';
                    $source_his = ' H:i:s';
                    $hi = ($only_date == false ? ' H:i' : '');
                    $his = ($only_date == false ? ' H:i:s' : '');
                    $seps = array('-', '.', '/');
                    foreach ($seps as $sep) {
                        if ($date = \DateTime::createFromFormat('Y' . $sep . 'm' . $sep . 'd' . $source_hi, $source_date)) {
                            return $date->format('d-m-Y' . $hi);
                        }
                        if ($date = \DateTime::createFromFormat('Y' . $sep . 'm' . $sep . 'd' . $source_his, $source_date)) {
                            return $date->format('d-m-Y' . $his);
                        }
                        if ($date = \DateTime::createFromFormat('Y' . $sep . 'm' . $sep . 'd', $source_date)) {
                            return $date->format('d-m-Y');
                        }
                        if ($date = \DateTime::createFromFormat('d' . $sep . 'm' . $sep . 'Y' . $source_hi, $source_date)) {
                            return $date->format('Y-m-d' . $hi);
                        }
                        if ($date = \DateTime::createFromFormat('d' . $sep . 'm' . $sep . 'Y' . $source_his, $source_date)) {
                            return $date->format('Y-m-d' . $his);
                        }
                        if ($date = \DateTime::createFromFormat('d' . $sep . 'm' . $sep . 'Y', $source_date)) {
                            return $date->format('Y-m-d');
                        }
                    }
                } else {
                    // BUG
                    // se la data è zero
                    // non funziona DateTime::createFromFormat
                    return self::invert_zero_date($source_date);
                }
            }
        }
        return $source_date;
    }

    public static function date_to_sql($date, $only_date = false)
    {
        if (!empty($date)) {
            if (is_array($date)) {
                foreach ($date as $Key => $Value) {
                    if (!empty($Value)) {
                        if (is_string($Key) && ((strpos($Key, "data_")) === 0 || (strpos($Key, "date_")) === 0)) {
                            $date[$Key] = self::date_to_sql($Value, $only_date);
                        }
                    }
                }
            } else {
                $date = self::date_format_to($date, 'Y-m-d', $only_date);
            }
        }
        return $date;
    }

    public static function date_to_ita($date, $only_date = false)
    {
        if (!empty($date)) {
            if (is_array($date)) {
                foreach ($date as $Key => $Value) {
                    if (!empty($Value)) {
                        if (is_array($Value)) {
                            $date[$Key] = self::date_to_ita($Value, $only_date);
                        } else {
                            if (is_string($Key) && ((strpos($Key, "data_")) === 0 || (strpos($Key, "date_")) === 0)) {
                                $date[$Key] = self::date_to_ita($Value, $only_date);
                            }
                        }
                    }
                }
            } else {
                $date = self::date_format_to($date, 'd-m-Y', $only_date);
            }
        }
        return $date;
    }

    public static function date_format_to($source_date, $format, $only_date = false)
    {
        if (is_string($source_date)) {
            if (!self::is_zero_date($source_date)) {
                $source_hi =  ' H:i';
                $source_his =  ' H:i:s';
                $hi = ($only_date == false ? ' H:i' : '');
                $his = ($only_date == false ? ' H:i:s' : '');
                $seps = array('-', '.', '/');
                foreach ($seps as $sep) {
                    if ($date = \DateTime::createFromFormat('Y' . $sep . 'm' . $sep . 'd' . $source_hi, $source_date)) {
                        return $date->format($format . $hi);
                    }
                    if ($date = \DateTime::createFromFormat('Y' . $sep . 'm' . $sep . 'd' . $source_his, $source_date)) {
                        return $date->format($format . $his);
                    }
                    if ($date = \DateTime::createFromFormat('Y' . $sep . 'm' . $sep . 'd', $source_date)) {
                        return $date->format($format);
                    }
                    if ($date = \DateTime::createFromFormat('d' . $sep . 'm' . $sep . 'Y' . $source_hi, $source_date)) {
                        return $date->format($format  . $hi);
                    }
                    if ($date = \DateTime::createFromFormat('d' . $sep . 'm' . $sep . 'Y' . $source_his, $source_date)) {
                        return $date->format($format  . $his);
                    }
                    if ($date = \DateTime::createFromFormat('d' . $sep . 'm' . $sep . 'Y', $source_date)) {
                        return $date->format($format);
                    }
                }
            } else {
                if (strpos($format, 'Y') === 0) {
                    return '0000-00-00';
                } else {
                    return '00-00-0000';
                }
            }
        }
        return $source_date;
    }

    public static function date_format_from_to($date, $from_format, $to_format)
    {
        if ($date = \DateTime::createFromFormat($from_format, $date)) {
            return $date->format($to_format);
        }
        return $date;
    }

    public static function nice_date($datetime, $full = false)
    {
        date_default_timezone_set("Europe/Rome");

        $time_ago = strtotime($datetime);
        if ($time_ago) {
            $time_now = time();
            $now = new \DateTime('@' . $time_now);
            $ago = new \DateTime('@' . $time_ago);
            $diff = $now->diff($ago);

            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;

            $string = array(
                'y' => array('singolare' => 'anno', 'plurale' => 'anni'),
                'm' => array('singolare' => 'mese', 'plurale' => 'mesi'),
                'w' => array('singolare' => 'settimana', 'plurale' => 'settimane'),
                'd' => array('singolare' => 'giorno', 'plurale' => 'giorni'),
                'h' => array('singolare' => 'ora', 'plurale' => 'ore'),
                'i' => array('singolare' => 'minuto', 'plurale' => 'minuti'),
                's' => array('singolare' => 'secondo', 'plurale' => 'secondi'),
            );

            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    if ($diff->$k > 1) {
                        //plurale
                        $v = $diff->$k . ' ' . $v['plurale'];
                    } else {
                        //singolare
                        $v = $diff->$k . ' ' . $v['singolare'];
                    }
                } else {
                    unset($string[$k]);
                }
            }

            //print_r($string);
            if (!$full) {
                $string = array_slice($string, 0, 1);
            }

            if ($time_now > $time_ago) {
                $ret = $string ? implode(', ', $string) . ' fa' : 'proprio adesso';
            } else {
                $ret = $string ? 'tra ' . implode(', ', $string) . '' : 'proprio adesso';
            }
            return $ret;
        }
        return $datetime;
    }

    public static function calendar_array($year)
    {
        $start = strtotime("01/01/" . $year);
        $i_max = (date("L", $start) ? 366 : 365) - 1;

        for ($i = 0; $i <= $i_max; $i++) {
            $calendar[strftime("%m", $loop = strtotime("+$i day", $start))][strftime("%d", $loop)] = strftime("%Y-%m-%d", $loop);
        }
        return $calendar;
    }

    public static function is_zero_date($datetime)
    {
        if (is_string($datetime)) {
            if (trim($datetime) !== '') {
                $zero_dates = array('0000-00-00', '00-00-0000', '0000-00-00 00:00:00', '00-00-0000 00:00:00');
                return (\in_array($datetime, $zero_dates));
            }
        } else {
            return false;
        }
        return true;
    }

    public static function invert_zero_date($source_date)
    {
        // BUG
        // se la data è zero
        // non funziona DateTime::createFromFormat
        if (is_string($source_date)) {
            if (strpos($source_date, '0000') === 0) {
                return '00-00-0000';
            } else {
                return '0000-00-00';
            }
        } else {
            return $source_date;
        }
    }
}
