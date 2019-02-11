<?php
require_once 'core/init.php';

if (Session::exists('loginSuccess')){
    echo Session::flashMessage('loginSuccess');
}
