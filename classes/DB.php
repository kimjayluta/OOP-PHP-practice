<?php
class DB {
    private static $_instance = null;
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0;
    // Connection sa database
    private function __construct() {
        try{
            $this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),
                Config::get('mysql/username'),Config::get('mysql/password'));
        }catch (PDOException $e){
            die($e->getMessage());
        }
    }

    // getInstance means get the connection
    public static function getInstance(){
        if (!isset(self::$_instance)){
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    // This function do is preparing the query and executing it
    private function query($sql, $params = array()){
        $this->_error = false;
        // Prepare the sql
        if ($this->_query = $this->_pdo->prepare($sql)){
            $x = 1;
            // Checking kung may laman ang params variable
            if (count($params)){
                foreach ($params as $param){
                    // Set the position and the value
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            // Execute query
            if ($this->_query->execute()){
                 // Get results
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                // Count the result
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        // Return all
        return $this;
    }

    private function action($action,  $table, $condition = array()){
        if ($condition  !== null && count($condition) == 3){
            $operators = array('=', '>', '<', '>=', '<=');

            $field = $condition[0];
            $operator = $condition[1];
            $value = $condition[2];

            // Check if operator is in the operators array
            if (in_array($operator, $operators)){
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, array($value))->error()){
                    return $this;
                }
            }

        } else {
            $sql = "{$action} FROM {$table}";
            if (!$this->query($sql)->error()){
                return $this;
            }

        }

        return false;
    }

    public function insert($table, $fields = array()){
        $keys = array_keys($fields);
        $values = '';
        $x = 1;

        foreach ($fields as $field){
            $values .= '?';
            if($x < count($fields)){
                $values .= ',';
            }
            $x++;
        }
        $sql = "INSERT INTO {$table} (`". implode('`,`',$keys) ."`) VALUES ({$values})";
        if (!$this->query($sql,$fields)->error()){
            return true;
        }
        return false;
    }

    public function update($table, $id, $fields){
        $set = '';
        $x = 1;

        foreach ($fields as $name => $value){
            $set .= "{$name} = ?";
            if ($x < count($fields)){
                $set .= ', ';
            }
            $x++;
        }
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
        if (!$this->query($sql,$fields)->error()){
            return true;
        }
        return false;
    }
    // Get data function from the database
    public function get($table, $condition = array()){
        return $this->action('SELECT *', $table, $condition);
    }
    // Delete data from the database
    public function delete($table, $condition = array()){
        return $this->action('DELETE',$table, $condition);
    }
    // Return error if there's an error
    public function error(){
        return $this->_error;
    }
    // Return all the result from the query
    public function results(){
        return $this->_results;
    }
    // Return the count of the query
    public function count(){
        return $this->_count;
    }
    // Return only the first result
    public function first_result(){
        return $this->results()[0];
    }
}