<?php

// ================================================================================================
// Updated: 2020-04-08 (for assignment purpose, use the latest version)
// ================================================================================================

class Html {

    function __construct() {
        
    }

    // generate <label>
    function label($key, $text = null, $attr = '') {
        if ($text === null) {
            $text = str_replace('_', ' ', $key);
            $text = str_replace('[]', '', $text);
            $text = ucwords($text);
        }

        $h = "<label for='$key' $attr>$text</label>";
        return $h;
    }

    // generate <input type='text'>
    function text($key, $value = null, $maxlength = '', $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<input type='text' name='$key' id='$key' value='$value' maxlength='$maxlength' $attr>";
        return $h;
    }

    // generate <input type='password'>
    function password($key, $value = null, $maxlength = '', $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<input type='password' name='$key' id='$key' value='$value' maxlength='$maxlength' $attr>";
        return $h;
    }

    // generate <input type='number'>
    function number($key, $value = null, $min = '', $max = '', $step = '', $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<input type='number' name='$key' id='$key' value='$value' min='$min' max='$max' step='$step' $attr>";
        return $h;
    }

    // generate <input type='range'>
    function range($key, $value = null, $min = '', $max = '', $step = '', $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<input type='range' name='$key' id='$key' value='$value' min='$min' max='$max' step='$step' $attr>";
        return $h;
    }

    // generate <input type='color'>
    function color($key, $value = null, $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<input type='color' name='$key' id='$key' value='$value' $attr>";
        return $h;
    }

    // generate <input type='file'>
    function file($key, $accept = '', $attr = '') {
        $h = "<input type='file' name='$key' id='$key' accept='$accept' $attr>";
        return $h;
    }

    // generate <input type='hidden'>
    function hidden($key, $value = null, $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<input type='hidden' name='$key' id='$key' value='$value' $attr>";
        return $h;
    }

    // generate <textarea>
    function textarea($key, $value = null, $maxlength = '', $rows = '', $cols = '', $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<textarea name='$key' id='$key' maxlength='$maxlength' rows='$rows' cols='$cols' $attr>$value</textarea>";
        return $h;
    }

    // generate <select>
    // *** SINGLE SELECTION ***
    function select($key, $value = null, $items = [], $default = true, $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<select name='$key' id='$key' $attr>";

        if ($default) {
            $h .= "<option value=''>- Select One -</option>";
        }
        
        foreach ($items as $id => $text) {
            $status = $id == $value ? 'selected' : '';
            $h .= "<option value='$id' $status>$text</option>";
        }

        $h .= "</select>";
        return $h;
    }

    // generate <select multiple>
    // *** MULTIPLE SELECTION ***
    function selects($key, $value = null, $items = [], $size = null, $attr = '') {
        $value = $value ?? $GLOBALS[str_replace('[]', '', $key)];

        $size = $size ?? count($items);

        $h = "<select name='$key' id='$key' multiple size='$size' $attr>";

        foreach ($items as $id => $text) {
            $status = in_array($id, $value) ? 'selected' : '';
            $h .= "<option value='$id' $status>$text</option>";
        }

        $h .= "</select>";
        return $h;
    }

    // generate SINGLE <input type='radio'>
    function radio($key, $value = null, $id = '', $text = '', $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $status = $id == $value ? 'checked' : '';
        $h = "<label><input type='radio' name='$key' id='$key-$id' value='$id' $status $attr>$text</label>";
        return $h;
    }
    
    // generate HTML --> <input type='radio'>
    function radios($key, $value = null, $items = [], $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<div>";

        foreach ($items as $id => $text) {
            $status = $id == $value ? 'checked' : '';
            $h .= "
                <input type='radio' name='$key' id='$key-$id' value='$id' $status $attr>
                <label for='$key-$id'>$text</label>
            ";
        }

        $h .= "</div>";
        return $h;
    }

    // generate SINGLE <input type='checkbox'>
    function checkbox($key, $value = null, $text = '', $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $status = $value == 1 ? 'checked' : '';
        $h = "<label><input type='checkbox' name='$key' id='$key' value='1' $status $attr>$text</label>";
        return $h;
    }

    // generate MULTIPLE <input type='checkbox'>
    // *** MULTIPLE SELECTION ***
    function checkboxes($key, $value = null, $items = [], $break = true, $attr = '') {
        $value = $value ?? $GLOBALS[str_replace('[]', '', $key)];

        $h = "<div>";

        foreach ($items as $id => $text) {
            $status = in_array($id, $value) ? 'checked' : '';
            $h .= "<label><input type='checkbox' name='$key' id='$key-$id' value='$id' $status $attr>$text</label>";

            if ($break) {
                $h .= "<br>";
            }
        }

        $h .= "</div>";
        return $h;
    }

    // generate <input type='date'>
    function date($key, $value = null, $min = '', $max = '', $step = '', $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<input type='date' name='$key' id='$key' value='$value' min='$min' max='$max' step='$step' $attr>";
        return $h;
    }

    // generate <input type='time'>
    function time($key, $value = null, $min = '', $max = '', $step = '', $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<input type='time' name='$key' id='$key' value='$value' min='$min' max='$max' step='$step' $attr>";
        return $h;
    }

    // generate <input type='datetime-local'>
    function datetime($key, $value = null, $min = '', $max = '', $step = '', $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<input type='datetime-local' name='$key' id='$key' value='$value' min='$min' max='$max' step='$step' $attr>";
        return $h;
    }

    // generate <input type='month'>
    function month($key, $value = null, $min = '', $max = '', $step = '', $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<input type='month' name='$key' id='$key' value='$value' min='$min' max='$max' step='$step' $attr>";
        return $h;
    }

    // generate <input type='week'>
    function week($key, $value = null, $min = '', $max = '', $step = '', $attr = '') {
        $value = $value ?? $GLOBALS[$key];

        $h = "<input type='week' name='$key' id='$key' value='$value' min='$min' max='$max' step='$step' $attr>";
        return $h;
    }

    // IGNORED:
    // <input type='search'>
    // <input type='email'>
    // <input type='tel'>
    // <input type='url'>
    // <input type='image'>

}