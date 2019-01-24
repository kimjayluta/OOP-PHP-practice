<?php
require_once 'core/init.php';

$user = DB::getInstance();
$user->get('users',array('usn','=','billy'));

if ($user->count()){
    /*
     * To query first data
     * $var->first_result()->field
     * To query all data the example is below
     * foreach ($user->results() as $user){
            echo $user->usn,'<br>';
        }
     */
    echo $user->first_result()->usn;
} else {
    echo "Error sql";
}