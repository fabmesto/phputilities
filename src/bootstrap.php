<?php

namespace fab;

class bootstrap
{
    public static function html_input_edit($label, $input, $value, $classes = "", $attributes = "")
    {
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label>' . $label . '</label>';
        $html .= '<input type="text" class="form-control ' . $classes . '" name="' . $input . '" value="' . $value . '" placeholder="' . self::sanitize_html_placeholder(strip_tags($label)) . '" ' . $attributes . '>';
        $html .= '</div>';
        return $html;
    }

    public static function html_number_edit($label, $input, $value, $classes = "", $attributes = "")
    {
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label>' . $label . '</label>';
        $html .= '<input type="number" class="form-control ' . $classes . '" name="' . $input . '" value="' . $value . '" placeholder="' . self::sanitize_html_placeholder(strip_tags($label)) . '" ' . $attributes . '>';
        $html .= '</div>';
        return $html;
    }

    public static function html_textarea_edit($label, $input, $value, $classes = "", $attributes = "")
    {
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label>' . $label . '</label>';
        $html .= '<textarea class="form-control ' . $classes . '" name="' . $input . '" placeholder="' . self::sanitize_html_placeholder(strip_tags($label)) . '" ' . $attributes . '>' . $value . '</textarea>';
        $html .= '</div>';
        return $html;
    }

    public static function html_checkboxes_edit($label, $input, $values = array(), $sels = array(), $classes = "")
    {
        $html = '';
        if (is_array($values) && count($values) > 0) {
            $html .= '<div class="form-group">';
            foreach ($values as $value => $text) {
                $html .= '
                    <input type="hidden" name="' . $input . '[' . $value . ']" value="0" />
                    <div class="checkbox ' . $classes . '">
                        <label>
                            <input type="checkbox" name="' . $input . '[' . $value . ']" value="' . $value . '" ' . (in_array($value, $sels) ? 'checked' : '') . '> ' . $text . '
                        </label>
                    </div>';
            }
            $html .= '</div>';
        }
        return $html;
    }

    public static function html_radio_edit($label, $input, $values, $sel, $classes = "")
    {
        return self::_html_radio_edit($label, $input, $values, $sel, 'radio ' . $classes);
    }

    public static function html_radio_inline_edit($label, $input, $values, $sel, $classes = "")
    {
        return self::_html_radio_edit($label, $input, $values, $sel, 'radio-inline ' . $classes);
    }

    private static function _html_radio_edit($label, $input, $values, $sel, $classes = "")
    {
        $html = '';
        if (is_array($values) && count($values) > 0) {
            $html .= '<div class="form-check form-group">';
            $html .= '<label>' . $label . '</label>';
            foreach ($values as $value => $text) {
                $html .= '<div class="' . $classes . '">';
                $html .= '<label class="form-check-label"><input class="form-check-input" type="radio" name="' . $input . '" value="' . $value . '" ' . (strval($sel) === strval($value) ? 'checked' : '') . '>' . $text . '</label>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        return $html;
    }

    public static function html_radio_edit4($label, $input, $values, $sel, $classes = "",  $id_prefix = "radio_")
    {
        $html = '';
        if (is_array($values) && count($values) > 0) {
            $html .= '<div class="form-group-radio">';
            if ($label != '') $html .= '<label>' . $label . '</label>';
            foreach ($values as $value => $text) {
                $html .= '<div class="form-check ' . $classes . '">';
                $html .= '<input class="form-check-input" id="' . $id_prefix . $value . '" type="radio" name="' . $input . '" value="' . $value . '" ' . (strval($sel) === strval($value) ? 'checked' : '') . '>';
                $html .= '<label class="form-check-label" for="' . $id_prefix . $value . '">' . $text . '</label>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        return $html;
    }

    public static function html_select_edit($label, $input, $options, $classes = "", $attributes = "")
    {
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label>' . $label . '</label>';
        $html .= '<select class="form-control ' . $classes . '" name="' . $input . '" ' . $attributes . '>';
        $html .= $options;
        $html .= '</select>';
        $html .= '</div>';
        return $html;
    }

    public static function html_input_search($label, $input, $value, $classes = "", $attributes = "")
    {
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="sr-only">' . $label . '</label>';
        $html .= '<input type="text" class="form-control ' . $classes . '" name="' . $input . '" value="' . $value . '" placeholder="' . self::sanitize_html_placeholder(strip_tags($label)) . '" ' . $attributes . '>';
        $html .= '</div>';
        return $html;
    }

    public static function html_select_search($label, $input, $options, $classes = "", $attributes = "")
    {
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="sr-only">' . $label . '</label>';
        $html .= '<select class="form-control ' . $classes . '" name="' . $input . '" ' . $attributes . '>';
        $html .= $options;
        $html .= '</select>';
        $html .= '</div>';
        return $html;
    }

    private static function sanitize_html_placeholder($placeholder)
    {
        // Strip out any %-encoded octets.
        $sanitized = preg_replace('|%[a-fA-F0-9][a-fA-F0-9]|', '', $placeholder);

        // Limit to A-Z, a-z, 0-9, '_', '-'.
        $sanitized = preg_replace('/[^\s+A-Za-z0-9_-]/', '', $sanitized);

        return $sanitized;
    }
}
