<?php

namespace App\Core;

class Session {

    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function get($key) {
        self::start();
        return $_SESSION[$key];
    }

    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }

}