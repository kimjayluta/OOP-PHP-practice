<?php
require_once 'core/init.php';

if (Session::exists('home')){
    echo Session::flashMessage('home');
}

if (Input::exists()){
    if (Token::check(Input::get('token'))){
        // Validating the inputs
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
           'usn' => array('required' => true),
           'pwd' => array('required' => true)
        ));
        // Checking here if the inputs is valid
        if ($validation->passed()){
            $user = new User();
            // Checking if remember is checked or not
            $remember = (Input::get('remember') === 'on') ? true : false;
            // Sa login() function tig pa process ang login
            $login = $user->login(Input::get('usn'), Input::get('pwd'), $remember);

            if ($login){
                Redirect::to('index.php');
            } else {
                echo '<p>Login Failed! Please try again.</p>';
            }
        } else {
            // Output all the errors if there's an error
            foreach ($validation->errors() as $error){
                echo $error . '<br/>';
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
    <div class="fields">
        <label for="remember">
            <input type="checkbox" name="remember" id="remember"> Remember me
        </label>
    </div>
    <input type="hidden" name="token" id="token" value="<?php echo Token::generate()?>">
    <button type="submit">LOGIN</button>
</form>