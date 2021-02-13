<?php

namespace fab;

class functions
{

    public static function params_from_get($params)
    {
        //self::print_r($params);
        //self::print_r($_GET);
        $ret = array();
        foreach ($params as $key => $default_value) {
            $ret[$key] = $default_value;
            if (isset($_GET[$key])) {
                $ret[$key] = $_GET[$key];
            }
        }
        return $ret;
    }

    public static function print_r($array)
    {
        $text = '<pre>';
        $text .= print_r($array, true);
        $text .= '</pre>';
        return $text;
    }


    /* DATES */
    public static function date_to_invert($date, $only_date = false)
    {
        if (!empty($date)) {
            if (is_array($date)) {
                foreach ($date as $Key => $Value) {
                    if (!empty($Value)) {
                        if ((strpos($Key, "data_")) === 0 || (strpos($Key, "date_")) === 0) {
                            $date[$Key] = self::date_to_invert($Value, $only_date);
                        }
                    }
                }
                return $date;
            } else {
                $all = preg_split("/ /", $date);
                $dates = preg_split('/[-\.\/ ]/', $all[0]);
                if (count($dates) > 1) {
                    if (count($all) == 2 && $only_date == false) {
                        // d-m-Y
                        return $dates[2] . "-" . $dates[1] . "-" . $dates[0] . " " . $all[1];
                    } else {
                        // d-m-Y
                        return $dates[2] . "-" . $dates[1] . "-" . $dates[0];
                    }
                }
            }
        }
        return $date;
    }

    public static function date_to_sql($date, $only_date = false)
    {
        if (!empty($date)) {
            if (is_array($date)) {
                foreach ($date as $Key => $Value) {
                    if (!empty($Value)) {
                        if ((strpos($Key, "data_")) === 0 || (strpos($Key, "date_")) === 0) {
                            $date[$Key] = self::date_to_sql($Value, $only_date);
                        }
                    }
                }
                return $date;
            } else {
                $all = preg_split("/ /", $date);
                $dates = preg_split('/[-\.\/ ]/', $all[0]);
                if (count($dates) > 1) {
                    if (count($all) == 2 && $only_date == false) {
                        if (strlen($dates[0]) == 4) {
                            // Y-m-d
                            return $dates[0] . "-" . $dates[1] . "-" . $dates[2] . " " . $all[1];
                        } else if (strlen($dates[2]) == 4) {
                            // d-m-Y
                            return $dates[2] . "-" . $dates[1] . "-" . $dates[0] . " " . $all[1];
                        }
                    } else {
                        if (strlen($dates[0]) == 4) {
                            // Y-m-d
                            return $dates[0] . "-" . $dates[1] . "-" . $dates[2];
                        } else if (strlen($dates[2]) == 4) {
                            // d-m-Y
                            return $dates[2] . "-" . $dates[1] . "-" . $dates[0];
                        }
                    }
                }
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
                            if ((strpos($Key, "data_")) === 0 || (strpos($Key, "date_")) === 0) {
                                $date[$Key] = self::date_to_ita($Value, $only_date);
                            }
                        }
                    }
                }
                return $date;
            } else {
                $all = preg_split("/ /", $date);
                $dates = preg_split('/[-\.\/ ]/', $all[0]);
                if (count($dates) > 1) {
                    if (count($all) == 2 && $only_date == false) {
                        if (strlen($dates[0]) == 4) {
                            // Y-m-d
                            return $dates[2] . "-" . $dates[1] . "-" . $dates[0] . " " . $all[1];
                        } else if (strlen($dates[2]) == 4) {
                            // d-m-Y
                            return $dates[0] . "-" . $dates[1] . "-" . $dates[2] . " " . $all[1];
                        }
                    } else {
                        if (strlen($dates[0]) == 4) {
                            // Y-m-d
                            return $dates[2] . "-" . $dates[1] . "-" . $dates[0];
                        } else if (strlen($dates[2]) == 4) {
                            // d-m-Y
                            return $dates[0] . "-" . $dates[1] . "-" . $dates[2];
                        }
                    }
                }
            }
        }
        return $date;
    }

    public static function nice_date($datetime, $full = false)
    {
        date_default_timezone_set("Europe/Rome");

        $time_ago = strtotime($datetime);
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
}
