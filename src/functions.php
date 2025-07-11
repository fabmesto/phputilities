<?php

namespace fab;

class functions
{
    public static function params_from_get($params)
    {
        $ret = array();
        foreach ($params as $key => $default_value) {
            $ret[$key] = $default_value;
            if (isset($_GET[$key])) {
                $ret[$key] = $_GET[$key];
            }
        }
        return $ret;
    }

    public static function params_from_post($params)
    {
        $ret = array();
        foreach ($params as $key => $default_value) {
            $ret[$key] = $default_value;
            if (isset($_POST[$key])) {
                $ret[$key] = $_POST[$key];
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
        if (is_array($array)) {
            foreach ($array as $row) {
                if (isset($row[$key])) {
                    $ret[] = $row[$key];
                } else {
                    //echo "ERROR arraymulti_to_keys: " . $key . '<br />';
                    //self::print_r($array) . '<br />';
                }
            }
        }
        return $ret;
    }

    /* --- comp old code */
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
    /* --- comp old code */

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
                    $ret = self::html_array($name, $value, $show_html);
                } else {
                    $ret = $value;
                }
                break;
        }
        return $ret;
    }

    public static function html_array($name, $value, $show_html = true)
    {
        $ret = "";
        if ($show_html) {
            $ret .= '<div class="fab-col-details" onclick="jQuery(this).toggleClass(\'fab-col-details\')">';
        }
        $sep_not_show_html = '';
        foreach ($value as $key => $row) {
            if (is_array($row)) {

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
            } else {
                if ($show_html) {
                    $ret .= '<div><b>' .  self::clean_col_name($key)  . '</b>: ' . self::clean_col_value($name, $row) . '</div>';
                } else {
                    $ret .= $sep_not_show_html . self::clean_col_value($name, $row);
                    $sep_not_show_html = ' | ';
                }
            }
        }
        if ($show_html) {
            $ret .= '</div>';
        }
        return $ret;
    }

    public static function date_to_invert($date, $only_date = false)
    {
        return \fab\date::date_to_invert($date, $only_date);
    }

    public static function date_to_sql($date, $only_date = false)
    {
        return \fab\date::date_to_sql($date, $only_date);
    }

    public static function date_to_ita($date, $only_date = false)
    {
        return \fab\date::date_to_ita($date, $only_date);
    }

    public static function nice_date($datetime, $full = false)
    {
        return \fab\date::nice_date($datetime, $full);
    }

    public static function csv_to_array($filename, $map = array(), $sep = ";", $index_header = 0)
    {
        $array = array();
        if (file_exists($filename)) {
            $row = 0;
            if (($handle = fopen($filename, "r")) !== false) {
                while (($data = fgetcsv($handle, 0, $sep)) !== false) {

                    $num = count($data);

                    for ($c = 0; $c < $num; $c++) {
                        if (isset($map[$c])) {
                            $map[$c] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $map[$c]);
                            $array[$row][$map[$c]] = $data[$c];
                        } else {
                            if ($row == $index_header) {
                                $data[$c] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data[$c]);
                                $map[$c] = str_replace(PHP_EOL, '', $data[$c]);
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

    public static function console_log($data)
    {
        echo '<script>';
        echo 'console.log(' . json_encode($data) . ')';
        echo '</script>';
    }

    public static function html_environment_variable()
    {
        $current_url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        $html = '';
        $html .= '<p>POST: ' . json_encode($_POST) . '</p>';
        $html .= '<p>GET: ' . json_encode($_GET) . '</p>';
        $html .= '<p>URL: ' . $current_url . '</p>';
        $html .= '<p>IP: ' . $_SERVER['REMOTE_ADDR'] . '</p>';
        return $html;
    }

    public static function wp_admin_email($subject, $message)
    {
        if (function_exists('wp_mail')) {
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $admin_email = get_option('admin_email');
            return wp_mail($admin_email, $subject, $message, $headers);
        }
    }

    public static function random_str(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}
