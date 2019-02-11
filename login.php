<?php
require_once 'core/init.php';

if (Session::exists('home')){
    echo Session::flashMessage('home');
}