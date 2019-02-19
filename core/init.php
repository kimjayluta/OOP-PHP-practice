<?php
session_start();

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'oopproject'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiration' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token',
    )
);

// To call the class
spl_autoload_register(function ($class){
    require_once 'classes/' . $class .'.php';
});

require_once 'functions/sanitize.php';

// For remember me function
if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('user_session',array('hash', '=', $hash));

    if ($hashCheck->count()){
        $user = new User($hashCheck->first_result()->user_id);
        $user->login();

    }
}