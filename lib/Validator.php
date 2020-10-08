<?php

// ================================================================================================
// Updated: 2020-04-10 (for assignment purpose, use the latest version)
// ================================================================================================

class Validator {

    function __construct() {
        $this->rules    = [];
        $this->messages = [];
        $this->inputs   = [];
        $this->errors   = [];

        // ========================================================================================
        // DEFAULT ERROR MESSAGES
        // ========================================================================================

        $this->defaults = [
            'required'         => 'Required',
            'maxlength'        => 'Length cannot more than {0}',
            'minlength'        => 'Length cannot less than {0}',
            'rangelength'      => 'Length must between {0} to {1}',
            'email'            => 'Invalid email',
            'url'              => 'Invalid URL',
            'pattern'          => 'Invalid format',
            'number'           => 'Must be a number',
            'digits'           => 'Only digits are allowed',
            'min'              => 'Value cannot more than {0}',
            'max'              => 'Value cannot less than {0}',
            'range'            => 'Value must between {0} to {1}',
            'unique'           => 'Value not unique', 
            'exist'            => 'Value not exist',
            'dbUnique'         => 'Value not unique', // server-only
            'dbExist'          => 'Value not exist',  // server-only
            'maxsize'          => 'File size cannot more than {0} bytes',
            'accept'           => 'Invalid file type',
            'extension'        => 'Invalid file extension',
            // *** NEW ***
            'equalTo'          => 'Value must equal to target',
            'notEqualTo'       => 'Value must not equal to target', 
            'lessThan'         => 'Value must less than target',
            'lessThanEqual'    => 'Value must less than or equal to target',
            'greaterThan'      => 'Value must greater than target',
            'greaterThanEqual' => 'Value must greater than or equal to target',

            // ... more
        ];
    }

    // validate input values based on rules
    function validate() {
        // clear errors list
        $this->errors = [];

        foreach ($this->rules as $key => $rule) {
            // get input value (from inputs array or global variable)
            $value = $this->inputs[$key] ?? $GLOBALS[str_replace('[]', '', $key)];
            
            // call validation methods for each field
            foreach ($rule as $method => $param) {
                // skip undefined method
                if (method_exists($this, $method) === false) continue;
                
                // skip empty value (except required)
                if ($method !== 'required' && ($value === '' || $value === [] || $value === null)) continue;

                // convert parameter to array if necessary (except unique and exist)
                if (!is_array($param) || in_array($method, ['unique', 'exist'])) {
                    $param = [$param];
                }

                // call validation method
                $valid = $this->$method($value, ...$param);

                // if invalid
                if ($valid === false) {
                    // get custom error message
                    $message = $this->messages[$key][$method] ?? '';

                    // if no custom error message, use default error message
                    if ($message === '') {
                        // for custom validation
                        if ($method === 'custom') {
                            $message = $param[1] ?? 'Error message not defined';
                        }
                        // for other validations
                        else {
                            $message = $this->defaults[$method] ?? 'Error message not defined';
                        }
                    }

                    // add error message to errors list
                    $this->errors[$key] = $this->format($message, $param);

                    // the field is invalid, skip to next field
                    break;
                }
            }
        }

        // true if all valid, otherwise false
        return count($this->errors) === 0;
    }

    // format error message with parameter
    function format($message, $param) {
        // replace {0} with parameter if any
        if (strpos($message, '{0}') !== false) {
            $message = str_replace('{0}', $param[0], $message);
        }
        
        // replace {1} with parameter if any
        if (strpos($message, '{1}') !== false) {
            $message = str_replace('{1}', $param[1], $message);
        }
        
        return $message;
    }

    // check if all fields valid, or if a specific field valid
    function valid($key = '') {
        if ($key === '') {
            return count($this->errors) === 0;
        }
        else {
            return isset($this->errors[$key]) ? false : true;
        }
    }

    // generate HTML for error message
    function error($key) {
        // obtain error message
        $error = $this->errors[$key] ?? '';

        // return HTML
        $h = "<span id='$key-error' class='error'>$error</span>";
        return $h;
    }

    // ============================================================================================
    // VALIDATION METHODS
    // ============================================================================================

    // server-only --------------------------------------------------------------------------------
    function custom($value, $fn) {
        return $fn($value);
    }

    function dbUnique($value, $table, $field) {
        global $db;
        $stm = $db->prepare("SELECT COUNT(*) FROM `$table` WHERE `$field` = ?");
        $stm->execute([$value]);
        $count = $stm->fetchColumn(); // single value
        return $count === 0;     
    }

    function dbExist($value, $table, $field) {
        global $db;
        $stm = $db->prepare("SELECT COUNT(*) FROM `$table` WHERE `$field` = ?");
        $stm->execute([$value]);
        $count = $stm->fetchColumn(); // single value
        return $count > 0; 
    }

    // server and client --------------------------------------------------------------------------
    function required($value) {
        if (is_array($value)) {
            return count($value) !== 0;
        }
        else if (is_string($value)) {
            return $value !== '';
        }
        return $value !== null;
    }

    function minlength($value, $min) {
        if (is_array($value)) {
            return count($value) >= $min;
        }
        return strlen($value) >= $min;
    }

    function maxlength($value, $max) {
        if (is_array($value)) {
            return count($value) <= $max;
        }
        return strlen($value) <= $max;
    }

    function rangelength($value, $min, $max) {
        if (is_array($value)) {
            return count($value) >= $min && count($value) <= $max;
        }
        return strlen($value) >= $min && strlen($value) <= $max;
    }

    function email($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    function url($value) {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    function pattern($value, $regex) {
        return preg_match("/$regex/", $value) === 1;
    }

    function number($value) {
        return is_numeric($value);
    }
    
    function digits($value) {
        return preg_match('/^\d+$/', $value) === 1;
    }

    function min($value, $min) {
        return $value >= $min;
    }

    function max($value, $max) {
        return $value <= $max;
    }

    function range($value, $min, $max) {
        return $value >= $min && $value <= $max;
    }
        
    function unique($value, $array) {
        return in_array($value, $array) === false;
    }

    function exist($value, $array) {
        return in_array($value, $array);
    }

    function maxsize($file, $maxsize) {
        return $file->size <= $maxsize;
    }

    function accept($file, $accept) {
        $arr = explode(',', $accept);         // string to array
        $arr = array_map('trim', $arr);       // trim individual string
        $str = implode(',', $arr);            // array to string
        $str = preg_quote($str, '/');         // escape special regex chars
        $str = str_replace('\*', '.+', $str); // replace '\*' with '.+'
        $str = str_replace(',', '|', $str);   // replace ',' with '|'

        return preg_match("/^($str)$/i", $file->type) === 1;
    }

    function extension($file, $extension) {
        $arr = explode(',', $extension);    // string to array
        $arr = array_map('trim', $arr);     // trim individual string
        $str = implode(',', $arr);          // array to string
        $str = str_replace(',', '|', $str); // replace ',' with '|'

        return preg_match("/\.($str)$/i", $file->name) === 1;
    }

    // *** NEW ***
    function equalTo($value, $key) {
        $target = $this->inputs[$key] ?? $GLOBALS[$key];
        return strcmp($value, $target) === 0;
    }

    // *** NEW ***
    function notEqualTo($value, $key) {
        $target = $this->inputs[$key] ?? $GLOBALS[$key];
        return strcmp($value, $target) !== 0;
    }

    // *** NEW ***
    function lessThan($value, $key) {
        $target = $this->inputs[$key] ?? $GLOBALS[$key];
        return strcmp($value, $target) < 0;
    }

    // *** NEW ***
    function lessThanEqual($value, $key) {
        $target = $this->inputs[$key] ?? $GLOBALS[$key];
        return strcmp($value, $target) <= 0;
    }

    // *** NEW ***
    function greaterThan($value, $key) {
        $target = $this->inputs[$key] ?? $GLOBALS[$key];
        return strcmp($value, $target) > 0;
    }

    // *** NEW ***
    function greaterThanEqual($value, $key) {
        $target = $this->inputs[$key] ?? $GLOBALS[$key];
        return strcmp($value, $target) >= 0;
    }

    // ... more

}