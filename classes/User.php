<?php
class User {
    private $_db,
            $_data,
            $_sessionName;

    public function __construct($user = null){
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
    }
    // Registering new account
    public function create($fields = array()){
        if (!$this->_db->insert('users',$fields)){
            throw new Exception('There was a problem creating your account!');
        }
    }
    // Checking if username is exist in the database and inserting the data to a variable
    public function find($user = null){
        if ($user){
            $data = $this->_db->get('users', array('usn', '=', $user));

            if ($data->count()){
                $this->_data = $data->first_result();
                return true;
            }
        }
        return false;
    }
    // Logging in user
    public function login($username = null, $password = null){
        $user = $this->find($username);
        if ($user){
            if ($this->_data->pwd === Hash::make($password)){
                Session::put($this->_sessionName, $this->_data->id);
                return true;
            }
        }
        return false;
    }

}