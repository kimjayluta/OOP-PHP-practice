<?php
require_once 'core/init.php';
// Checking if there's a submitted data
if (Input::exists()){
    // This if condition if for CSRF security
    if(Token::checkToken(Input::get('token'))){
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'usn' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'pwd' => array(
                'required' => true,
                'min' => 6
            ),
            'rep_pass' => array(
                'required' => true,
                'matches' => 'pwd'
            ),
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            )
        ));
        // Check if validation is passed
        if ($validation->passed()){
            $user = new User();
            try {
                $user -> create(array(
                    'usn' => Input::get('usn'),
                    'pwd' => Hash::make(Input::get('pwd')),
                    'name' => Input::get('name'),
                    'joined' => date('Y-m-d H:i:s'),
                    'grp' => 1
                ));
            } catch (Exception $e){
                die($e->getMessage());
            }

            Session::flashMessage('home',"You've been successfully registered, you can now login!");
            header('location: login.php');
        } else {
            foreach($validation->errors() as $error){
                echo $error . "<br>";
            }
        }
    }
}
?>
<form action="#" method="post">
    <div class="fields">
        <label for="usn">Username</label>
        <input type="text" id="usn" name="usn" value="<?php echo Input::get('usn')?>">
    </div>
    <div class="fields">
        <label for="pwd">Password</label>
        <input type="password" id="pwd" name="pwd">
    </div>
    <div class="fields">
        <label for="rep_pass">Repeat password</label>
        <input type="password" id="rep_pass" name="rep_pass">
    </div>
    <div class="fields">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo Input::get('name')?>">
    </div>
    <input type="hidden" name="token" id="token" value="<?php echo Token::generateToken()?>">
    <button type="submit">Submit</button>
</form>