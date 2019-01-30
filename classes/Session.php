<?php
class Session {
    // Checking if Session is exists
    public static function exists($name){
        return (isset($_SESSION[$name])) ? true : false;
    }
    // To put session value
    public static function put($name, $value){
        return $_SESSION[$name] = $value;
    }
    // To Return Session value
    public static function get($name){
        return $_SESSION[$name];
    }
    // Delete/Unset Session
    public static function delete($name){
        if (self::exists($name)){
            unset($_SESSION[$name]);
        }
    }

}