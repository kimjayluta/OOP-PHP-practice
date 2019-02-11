<?php
require_once 'core/init.php';

if (Session::exists('home')){
    echo Session::flashMessage('home');
}

if (Input::exists()){
    if (Token::check(Input::get('token'))){
        // Validating data
        $validate = new Validate();
        $validate = $validate->check($_POST, array(
            'usn' => array('required' => true),
            'pwd' => array('required' => true),
        ));

        // Log user in or output all the errors
        if ($validate->passed()){
            $user = new User();
            $login = $user->login(Input::get('usn'), Input::get('usn'));

            if ($login){
                Session::flashMessage('loginSuccess', 'Good day user!');
                Redirect::to('index.php');
            } else {
                echo "<p>There was an error logging in please try again later!</p>";
            }
        } else {
            foreach ($validate->errors() as $error){
                echo $error . '<br />';
            }
        }
    }
}
?>
<form action="#" method="post">
    <div class="fields">
        <label for="usn">Username</label>
        <input type="text" id="usn" name="usn" autocomplete="off">
    </div>
    <div class="fields">
        <label for="pwd">Password</label>
        <input type="password" id="pwd" name="pwd" autocomplete="off">
    </div>
    <input type="hidden" name="token" id="token" value="<?php echo Token::generate()?>">
    <button type="submit">LOGIN</button>
</form>