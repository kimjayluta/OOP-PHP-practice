<?php
class User {
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn;
    public function __construct($user = null){
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if (!$user){
            if (Session::exists($this->_sessionName)){
                $user = Session::get($this->_sessionName);

                if ($this->find($user)){
                    $this->_isLoggedIn = true;
                } else {
                    // Process Logout
                }
            }
        } else {
            $this->find($user);
        }
    }
    // Registering new account
    public function create($fields = array()){
        if (!$this->_db->insert('users',$fields)){
            throw new Exception('There was a problem creating your account!');
        }
    }

    // Checking if user exists in the database
    public function find($user = null){
        if ($user){
            $field = (is_numeric($user)) ? 'id' : 'usn';
            $data = $this->_db->get('users', array($field, '=', $user));

            if ($data->count()){
                $this->_data = $data->first_result();
                return true;
            }
        }
        return false;
    }

    // Logging user in
    public function login($username = null, $password = null, $remember = false){
        if (!$username && !$password && $this->exists()){
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            $user = $this->find($username);
            if ($user){
                if ($this->data()->pwd === Hash::make($password)){
                    Session::put($this->_sessionName, $this->data()->id);

                    if ($remember){
                        $hash = Hash::unique();
                        $expiry = Config::get('remember/cookie_expiration');
                        // Checking if cookie is already in database
                        $hashCheck = $this->_db->get('user_session', array('user_id', '=', $this->data()->id));

                        if (!$hashCheck->count()){
                            $this->_db->insert('user_session',array(
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ));
                        } else {
                            $hash = $hashCheck->first_result()->hash;
                        }
                        Cookie::put($this->_cookieName, $hash, $expiry);
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function exists(){
        return (!empty($this->_data)) ? true : false;
    }

    // Log out user
    public function logout(){
        $this->_db->delete('user_session',array('user_id', '=', $this->data()->id));

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }
    // Use to return the data
    public function data(){
        return $this->_data;
    }
    // Return the login variable, it check if user is already logged in or not.
    public function isLoggedIn(){
        return $this->_isLoggedIn;
    }
}