<?php
class Cookie {
    // Function to check if cookie exists
    public static function exists($name){
        return (isset($_COOKIE[$name])) ? true : false;
    }
    // Function to get the cookie name
    public static function get($name){
        return $_COOKIE[$name];
    }
    // Function to set cookie
    public static function put($name, $value, $expiry){
        if (setcookie($name, $value, time() + $expiry)){
            return true;
        }
        return false;
    }
    // Function to delete cookie
    public static function delete($name){
        self::put($name, '', time() - 1);
    }
}