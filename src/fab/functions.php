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

    public static function test()
    {
        return 'test ok';
    }
}
