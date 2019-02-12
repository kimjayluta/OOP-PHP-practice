<?php
require_once 'core/init.php';

if (Session::exists('loginSuccess')){
    echo Session::flashMessage('loginSuccess');
}
$user = new User();
if (!$user->isLoggedIn()){
    Redirect::to('login.php');
}
?>
<p>Hello <?php echo $user->data()->usn?></p>
<ul>
    <li><a href="logout.php">Logout</a></li>
</ul>