<?php
class DB {
    private static $_instance = null;
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0;
    // Get the connection in database
    private function __construct() {
        try{
            $this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),
                Config::get('mysql/username'),Config::get('mysql/password'));
            echo 'Connected';
        }catch (PDOException $e){
            die($e->getMessage());
        }
    }

    // Checking the connection if already have
    public static function getInstance(){
        if (!isset(self::$_instance)){
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
}