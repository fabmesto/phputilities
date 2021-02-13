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

    public static function options_deleted($sel = '', $all = false, $text_all = '-TUTTI-')
    {
        return self::options_bool($sel, $all, $text_all);
    }

    public static function options_bool($sel = '', $all = false, $text_all = '-TUTTI-')
    {
        $array = array('0' => 'NO', '1' => 'SI');
        return self::options_array($sel, $array, $all, $text_all);
    }

    public static function options_sesso($sel, $all = false, $text_all = '-TUTTI-')
    {
        $array = array('m' => 'M', 'f' => 'F');
        return self::options_array($sel, $array, $all, $text_all);
    }

    public static function options_sesso_upper($sel, $all = false, $text_all = '-TUTTI-')
    {
        $array = array('M' => 'M', 'F' => 'F');
        return self::options_array($sel, $array, $all, $text_all);
    }

    public static function options_array($sel = '', $array = array(), $all = false, $text_all = '-TUTTI-')
    {
        $html = '';
        if ($all) {
            $html .= '<option value="">' . $text_all . '</option>';
        }

        foreach ($array as $value => $text) {
            if (is_array($text)) {
                $html .= '<optgroup label="' . $value . '">';
                $html .= self::options_array($sel, $text);
                $html .= '</optgroup>';
            } else {

                if (is_array($sel)) {
                    $html .= '<option value="' . $value . '" ' . (in_array($value, $sel) ? 'selected' : '') . '>' . $text . '</option>';
                } else {
                    $html .= '<option value="' . $value . '" ' . (strval($sel) === strval($value) ? 'selected' : '') . '>' . $text . '</option>';
                }
            }
        }
        return $html;
    }

    public static function arraymulti_to_keys_values($array, $key = 'id', $value = 'nome')
    {
        $ret = array();
        foreach ($array as $row) {
            $ret[$row[$key]] = $row[$value];
        }
        return $ret;
    }

    public static function arraymulti_to_keys_values_group($array, $group_name, $group_text = 'nome', $key = 'id', $value = 'nome')
    {
        $ret = array();
        foreach ($array as $row) {
            if (isset($row[$group_name])) {
                $ret[$row[$group_text]] = self::arraymulti_to_keys_values($row[$group_name], $key, $value);
            }
        }
        return $ret;
    }

    public static function arraymulti_to_keys($array, $key = 'id')
    {
        $ret = array();
        foreach ($array as $row) {
            if (isset($row[$key])) {
                $ret[] = $row[$key];
            } else {
                echo "ERROR arraymulti_to_keys: " . $key . '<br />';
                self::print_r($array) . '<br />';
            }
        }
        return $ret;
    }

    public static function html_input_edit($label, $input, $value, $classes = "", $attributes = "")
    {
        return \fab\bootstrap::html_input_edit($label, $input, $value, $classes, $attributes);
    }

    public static function html_number_edit($label, $input, $value, $classes = "", $attributes = "")
    {
        return \fab\bootstrap::html_number_edit($label, $input, $value, $classes, $attributes);
    }

    public static function html_textarea_edit($label, $input, $value, $classes = "", $attributes = "")
    {
        return \fab\bootstrap::html_textarea_edit($label, $input, $value, $classes, $attributes);
    }

    public static function html_checkboxes_edit($label, $input, $values = array(), $sels = array(), $classes = "")
    {
        return \fab\bootstrap::html_checkboxes_edit($label, $input, $values, $sels, $classes);
    }

    public static function html_radio_edit($label, $input, $values, $sel, $classes = "")
    {
        return \fab\bootstrap::html_radio_edit($label, $input, $values, $sel, $classes);
    }

    public static function html_radio_inline_edit($label, $input, $values, $sel, $classes = "")
    {
        return \fab\bootstrap::html_radio_inline_edit($label, $input, $values, $sel, $classes);
    }

    public static function html_radio_edit4($label, $input, $values, $sel, $classes = "",  $id_prefix = "radio_")
    {
        return \fab\bootstrap::html_radio_edit4($label, $input, $values, $sel, $classes, $id_prefix);
    }

    public static function html_select_edit($label, $input, $options, $classes = "", $attributes = "")
    {
        return \fab\bootstrap::html_select_edit($label, $input, $options, $classes, $attributes);
    }

    public static function html_input_search($label, $input, $value, $classes = "", $attributes = "")
    {
        return \fab\bootstrap::html_input_search($label, $input, $value, $classes, $attributes);
    }

    public static function html_select_search($label, $input, $options, $classes = "", $attributes = "")
    {
        return \fab\bootstrap::html_select_search($label, $input, $options, $classes, $attributes);
    }

    public static function get_to_input_hidden($exclude_keys = array())
    {
        $html = '';
        if (isset($_GET)) {
            foreach ($_GET as $key => $value) {
                if (!in_array($key, $exclude_keys)) {
                    $html .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
                }
            }
        }
        return $html;
    }

    public static function get_in_query_string($exclude_keys = array())
    {
        $query = '';
        if (isset($_GET)) {
            foreach ($_GET as $key => $value) {
                if (!in_array($key, $exclude_keys)) {
                    $query .= $key . '=' . $value . '&';
                }
            }
        }
        return $query;
    }

    public static function get_in_array($exclude_keys = array())
    {
        $array = array();
        if (isset($_GET)) {
            foreach ($_GET as $key => $value) {
                if (!in_array($key, $exclude_keys)) {
                    $array[$key] = $value;
                }
            }
        }
        return $array;
    }



    public static function clean_col_name($name)
    {
        $name = str_replace('.', ' ', $name);
        $name = str_replace('_', ' ', $name);
        $name = ucwords($name);
        return $name;
    }

    public static function clean_col_value($name, $value, $show_html = true)
    {
        $type = "STRING";
        if (strpos($name, 'date_') === 0) {
            $type = "DATE";
        }

        switch ($name) {
            case "deleted":
                $type = "DELETED";
                break;

            case "date_update":
            case "date_insert":
                $type = "DATETIME";
                break;
            case "img":
                $type = "IMG";
                break;
        }

        switch ($type) {
            case "DELETED":
                if ($value == 0) {
                    $ret = "No";
                }

                if ($value == 1) {
                    $ret = "Si";
                }

                break;
            case "DATETIME":
                if ($value == '0000-00-00 00:00:00') {
                    $ret = "Nessuno";
                } else {
                    $ret = date('d-m-Y H:i', strtotime($value));
                }
                break;
            case "DATE":
                if ($value == '0000-00-00') {
                    $ret = "Nessuno";
                } else {
                    $ret = date('d-m-Y', strtotime($value));
                }
                break;
            case "IMG":
                if ($value == '') {
                    $ret = "Nessuna";
                } else {
                    $ret = '<img src="' . $value . '">';
                }
                break;
            default:
                if (is_array($value)) {
                    $ret = "";
                    foreach ($value as $row) {
                        if (is_array($row)) {
                            if ($show_html) {
                                $ret .= '<div class="fab-col-details">';
                            }

                            $sep = '';
                            foreach ($row as $k => $v) {
                                if (!is_array($v)) {
                                    if ($show_html) {
                                        $ret .= $sep . '<b>' . self::clean_col_name($k) . '</b>: ' . self::clean_col_value($k, $v);
                                    } else {
                                        $ret .= $sep . '' . self::clean_col_name($k) . ': ' . self::clean_col_value($k, $v);
                                    }
                                    $sep = ' , ';
                                }
                            }
                            if ($show_html) {
                                $ret .= '</div>';
                            }
                        } else {
                            if ($show_html) {
                                $ret .= '<div>' . self::clean_col_value($name, $row) . '</div>';
                            } else {
                                $ret .= self::clean_col_value($name, $row);
                            }
                        }
                    }
                } else {
                    $ret = $value;
                }
                break;
        }
        return $ret;
    }

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


    public static function memory_get_usage($label = '')
    {
        echo "<div>" . $label . ' - ' . self::human_filesize(memory_get_usage(true)) . "</div>\n";
    }



    public static function csv_to_array($filename, $map = array(), $sep = ";")
    {
        $array = array();
        if (file_exists($filename)) {
            $row = 0;
            if (($handle = fopen($filename, "r")) !== false) {
                while (($data = fgetcsv($handle, 0, $sep)) !== false) {
                    $num = count($data);

                    for ($c = 0; $c < $num; $c++) {
                        if (isset($map[$c])) {
                            $array[$row][$map[$c]] = $data[$c];
                        } else {
                            if ($row == 0) {
                                $map[$c] = $data[$c];
                            }
                            //$array[$row][$c] = $data[$c];
                        }
                    }
                    $row++;
                }
            }
            return $array;
        } else {
            return false;
        }
    }

    public static function split_comune_provincia($comune_provincia)
    {
        $pos_prov = strpos($comune_provincia, '(');
        if (!($pos_prov === false)) {
            $split = preg_split('/\(/', $comune_provincia);
            $comune = trim($split[0]);
            $provincia = trim(str_replace(')', '', $split[1]));
        } else {
            $comune = $comune_provincia;
            $provincia = '';
        }
        return array('comune' => $comune, 'provincia' => $provincia);
    }

    public static function value_by_key($array, $key)
    {
        foreach ($array as $k => $value) {
            if ($k == $key) {
                return $value;
            }

            if (is_array($value)) {
                $ret = self::value_by_key($value, $key);
                if ($ret != '') {
                    return $ret;
                }
            }
        }
        return '';
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


    public static function echo_error($error)
    {
        $html = '<div class="error">';
        $html .= $error;
        $html .= "</div>";
        echo $html;
    }

    public static function url_abs($url, $site = '')
    {
        if ($site == '') {
            if (function_exists('get_site_url')) {
                // wordpress
                $site = \get_site_url() . '/';
            }
        }

        if (strpos($url, 'http') === 0) {
            return $url;
        } else {
            return $site . $url;
        }
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


    public static function curl($url, $post = false, $options_set = false)
    {
        $someUA = array(
            "Mozilla/5.0 (Windows; U; Windows NT 6.0; fr; rv:1.9.1b1) Gecko/20081007 Firefox/3.1b1",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.0",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/0.4.154.18 Safari/525.19",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13",
            "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
            "Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.40607)",
            "Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.1; .NET CLR 1.1.4322)",
            "Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.1; .NET CLR 1.0.3705; Media Center PC 3.1; Alexa Toolbar; .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
            "Mozilla/45.0 (compatible; MSIE 6.0; Windows NT 5.1)",
            "Mozilla/4.08 (compatible; MSIE 6.0; Windows NT 5.1)",
            "Mozilla/4.01 (compatible; MSIE 6.0; Windows NT 5.1)",
        );

        srand((float) microtime() * 1000000);
        $ua = $someUA[rand(0, count($someUA) - 1)];
        if ($options_set) {
            $options = $options_set;
        } else {
            $options = array(
                CURLOPT_RETURNTRANSFER => true, // return web page
                CURLOPT_HEADER => false, // don't return headers
                CURLOPT_FOLLOWLOCATION => true, // follow redirects
                CURLOPT_ENCODING => "", // handle all encodings
                CURLOPT_USERAGENT => $ua, // who am i
                CURLOPT_AUTOREFERER => true, // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => 15, // timeout on connect
                CURLOPT_TIMEOUT => 15, // timeout on response
                CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_VERBOSE => true,

            );
        }
        if ($post) {
            $options[CURLOPT_POST] = 1;
            if (is_array($post)) {
                $post = http_build_query($post);
            }
            $options[CURLOPT_POSTFIELDS] = $post;
        }
        //echo strfab::printR($options);

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
        /*
            $header['errno']   = $err;
            $header['errmsg']  = $errmsg;
             */
        //change errmsg here to errno
        if ($errmsg) {
            echo "CURL:" . $errmsg . " - " . $url . "<BR>";
            return false;
        } else {
            return $content;
        }
    }

    public static function human_filesize($bytes, $decimals = 2)
    {
        $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    public static function filedir_to_url($filedir)
    {
        if (function_exists('wp_upload_dir')) {
            // wordpress
            $upload_dir = \wp_upload_dir();
            $base_upload_dir = $upload_dir['basedir'];
            $base_upload_url = $upload_dir['baseurl'];
            return str_replace($base_upload_dir, $base_upload_url, $filedir);
        }
        return $filedir;
    }

    public static function extension_file($file_path)
    {
        $path_parts = pathinfo($file_path);
        return $path_parts['extension'];
    }

    public static function is_image($file_path)
    {
        $extension = self::extension_file($file_path);
        $extensions_image = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
        return in_array(strtolower($extension), $extensions_image);
    }
}
