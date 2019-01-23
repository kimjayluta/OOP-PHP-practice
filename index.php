<?php
require_once 'core/init.php';

$user = DB::getInstance();
$user->get('users',array('usn','=','alex'));


if (!$user->count()){
    echo 'mayong nakukuang user';
} else {
    echo 'Success';
}