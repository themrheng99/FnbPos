<?php

// ================================================================================================
// Updated: 2020-04-12 (for assignment purpose, use the latest version)
// ================================================================================================

class Page {

    function __construct() {
        $this->title = 'Untitled';
        $this->pagename  = 'Restaurant';
        $this->head  = '';
        $this->foot  = '';
        $this->get   = $_SERVER['REQUEST_METHOD'] === 'GET';
        $this->post  = $_SERVER['REQUEST_METHOD'] === 'POST';
        // TODO: document root
        $this->root  = $_SERVER['DOCUMENT_ROOT'];
        // TODO: base url
        $this->base  = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}";
        // security (user & role)
        $this->user  = $_SESSION['user'] ?? null;
        $this->role  = $_SESSION['role'] ?? null;
        $this->table = $_SESSION['table'] ?? null;
        $this->order  = $_SESSION['order'] ?? null;
    }

    // obtain GET paramater
    function get($key, $default = '') {
        $value = $_GET[$key] ?? $default;
        return is_array($value) ? array_map('trim', $value) : trim($value);
    }

    // obtain POST paramater
    function post($key, $default = '') {
        $value = $_POST[$key] ?? $default;
        return is_array($value) ? array_map('trim', $value) : trim($value);
    }

    function post_notrim($key, $default = '') {
        return $value = $_POST[$key] ?? $default;
    }

    // obtain REQUEST (GET and POST) parameter
    function req($key, $default = '') {
        $value = $_REQUEST[$key] ?? $default;
        return is_array($value) ? array_map('trim', $value) : trim($value);
    }

    // obtain single uploaded file --> cast to object
    function file($key) {
        $arr = $_FILES[$key] ?? [];
        
        if ($arr && $arr['error'] === 0) {
            return (object)$arr;
        }

        return null;
    }

    // redirect to other page
    // Examples:
    // $p->redirect()    --> redirect to same URL (with parameters)
    // $p->redirect('?') --> redirect to same URL (without parameters)
    // $p->redirect('/') --> redirect to index.php
    // $p->redirect('/demo.php') --> redirect to demo.php
    function redirect($url = null) {
        ob_clean();
        $url = $url ?? $_SERVER['REQUEST_URI'];
        header("location: $url");
        exit();
    }

    // read or set temporaly session variable
    function temp($key, $value = null) {
        if ($value === null) {
            $value = $_SESSION["temp-$key"] ?? '';
            unset($_SESSION["temp-$key"]);
        }
        else {
            $_SESSION["temp-$key"] = $value;
        }
        return $value;
    }

    // login user
    function login($user, $role, $table = '', $order = '', $url = '/') {
        $_SESSION['user'] = $user;
        $_SESSION['role'] = $role;
        $_SESSION['table'] = $table;
        $_SESSION['order'] = $order;

        $this->redirect($url);  
    }

    // logout user
    function logout($url = '/') {
        unset($_SESSION['user']);
        unset($_SESSION['role']);
        unset($_SESSION['table']);
        unset($_SESSION['order']);

        $this->redirect($url);
    }

    // authorization
    function auth($roles = '') {
        if ($this->user) {
            if ($roles) {
                $arr = array_map('trim', explode(',', $roles));
                if (in_array($this->role, $arr)) {
                    return; // OK
                }
            }
            else {
                return; // OK
            }
        }

        // *** UPDATED ***
        $this->redirect('/home.php');
    }

    // TODO: initialize & return mail object
    function mail() {
        include_once 'PHPMailer.php';
        include_once 'SMTP.php';

        $m = new PHPMailer(true);
        $m->isSMTP();
        $m->SMTPAuth = true;
        $m->Host = 'smtp.gmail.com';
        $m->Port = 587;
        $m->Username = 'BAIT2173.email@gmail.com';
        $m->Password = 'BAIT2173.password';
        $m->CharSet = 'utf-8';
        $m->setFrom($m->Username, '🍓 Admin');

        return $m;
    }

    // TODO: return random string of specific length
    function random($length = 10) {
        $chr = '0123456789' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . 'abcdefghijklmnopqrstuvwxyz';
        $max = strlen($chr) - 1;
        $str = '';

        for ($i = 1; $i <= $length; $i++) {
            $str .= $chr[random_int(0, $max)];
        }

        return $str;
    }

    // TODO: return random unique token (SHA1 hashed)
    function token() {
        return sha1(uniqid() . random_int(PHP_INT_MIN, PHP_INT_MAX));  
    }

    // ... more

}