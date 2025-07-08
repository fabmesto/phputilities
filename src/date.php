<?php

declare(strict_types=1);

namespace fab;

class date
{

    private const TIME_LABELS = [
        'y' => ['singolare' => 'anno',     'plurale' => 'anni'],
        'm' => ['singolare' => 'mese',     'plurale' => 'mesi'],
        'w' => ['singolare' => 'settimana', 'plurale' => 'settimane'],
        'd' => ['singolare' => 'giorno',   'plurale' => 'giorni'],
        'h' => ['singolare' => 'ora',      'plurale' => 'ore'],
        'i' => ['singolare' => 'minuto',   'plurale' => 'minuti'],
        's' => ['singolare' => 'secondo',  'plurale' => 'secondi'],
    ];

    public static function date_to_invert($source_date, $only_date = false)
    {
        if (!empty($source_date)) {
            if (is_array($source_date)) {
                $date = [];
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
        if (is_null($source_date)) {
            return (strpos($format, 'Y') === 0) ? '0000-00-00' : '00-00-0000';
        }

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

    public static function nice_date($datetime, bool $full = false): string
    {
        if (self::is_zero_date($datetime)) {
            return '-';
        }

        try {
            $timezone = new \DateTimeZone('Europe/Rome');
            $time_now = new \DateTime('now', $timezone);
            $time_ago = new \DateTime($datetime, $timezone);
        } catch (\Exception $e) {
            return $datetime; // fallback in caso di errore
        }

        $diff = $time_now->diff($time_ago);
        $is_past = $time_now > $time_ago;

        // Calcolo settimane (non gestite nativamente in DateInterval)
        $weeks = floor($diff->d / 7);
        $diff->d -= $weeks * 7;

        $string = [];

        $labels = self::TIME_LABELS;

        if ($weeks > 0) {
            $string['w'] = $weeks . ' ' . ($weeks > 1 ? $labels['w']['plurale'] : $labels['w']['singolare']);
        }

        foreach (['y', 'm', 'd', 'h', 'i', 's'] as $key) {
            $value = $diff->$key;
            if ($value > 0) {
                $string[$key] = $value . ' ' . ($value > 1 ? $labels[$key]['plurale'] : $labels[$key]['singolare']);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }

        if (empty($string)) {
            return 'proprio adesso';
        }

        return $is_past
            ? implode(', ', $string) . ' fa'
            : 'tra ' . implode(', ', $string);
    }


    public static function calendar_array($year)
    {
        $start = new \DateTime("$year-01-01");
        $calendar = [];
        $days = $start->format('L') ? 366 : 365;

        for ($i = 0; $i < $days; $i++) {
            $current = (clone $start)->modify("+$i days");
            $month = $current->format('m');
            $day = $current->format('d');
            $calendar[$month][$day] = $current->format('Y-m-d');
        }

        return $calendar;
    }


    public static function is_zero_date($datetime)
    {
        if (is_null($datetime)) {
            return true; // Trattiamo NULL come data "zero"
        }

        if (is_string($datetime)) {
            $datetime = trim($datetime);
            if ($datetime !== '') {
                $zero_dates = array(
                    '0000-00-00',
                    '00-00-0000',
                    '0000-00-00 00:00:00',
                    '00-00-0000 00:00:00'
                );
                return in_array($datetime, $zero_dates, true);
            }
        }

        return false;
    }

    public static function invert_zero_date($source_date)
    {
        // Se è NULL lo trattiamo come "zero date"
        if (is_null($source_date)) {
            return '0000-00-00';
        }

        if (is_string($source_date)) {
            $normalized = trim($source_date);
            $zero_db_values = ['0000-00-00', '0000-00-00 00:00:00'];
            
            if (in_array($normalized, $zero_db_values, true)) {
                return '00-00-0000';
            }

            $zero_display_values = ['00-00-0000', '00-00-0000 00:00:00'];

            if (in_array($normalized, $zero_display_values, true)) {
                return '0000-00-00';
            }
        }

        return $source_date;
    }

    public static function is_valid_date($date, $formats = ['Y-m-d', 'd-m-Y', 'Y-m-d H:i:s', 'd-m-Y H:i:s'])
    {
        if (self::is_zero_date($date)) return false;

        foreach ($formats as $format) {
            $dt = \DateTime::createFromFormat($format, $date);
            if ($dt && $dt->format($format) === $date) {
                return true;
            }
        }

        return false;
    }
}
