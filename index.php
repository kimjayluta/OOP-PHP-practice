<?php
require_once 'core/init.php';

$user = DB::getInstance();

/*
* To query first data
* $var->first_result()->field
* To query all data the example is below
* foreach ($user->results() as $user){
       echo $user->usn,'<br>';
   }
*/

$user->update('users',3, array(
    'usn' => 'KimJayLuta',
    'pwd' => 'password'
));