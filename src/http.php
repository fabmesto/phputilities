<?php

namespace fab;

class http
{
    public static function request($url, $post = false, $options_set = false)
    {
        if (!function_exists('curl_init')) {
            return false;
        }

        if ($options_set) {
            $options = $options_set;
        } else {
            $options = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_USERAGENT => self::random_user_agent(),
                CURLOPT_AUTOREFERER => true,
                CURLOPT_CONNECTTIMEOUT => 15,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            );
        }

        if ($post !== false) {
            $options[CURLOPT_POST] = 1;
            if (is_array($post)) {
                $post = http_build_query($post);
            }
            $options[CURLOPT_POSTFIELDS] = $post;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $ch = null;

        if ($err || $content === false) {
            return false;
        }

        return $content;
    }

    private static function random_user_agent()
    {
        $agents = array(
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 14_4) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Safari/605.1.15",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36",
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/124.0",
        );

        return $agents[array_rand($agents)];
    }
}
