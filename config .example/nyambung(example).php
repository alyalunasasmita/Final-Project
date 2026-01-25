<?php
//connection to database replace with your own database config
namespace App\Database;
class Database {
    private $hostname = ''; 
    private $username = ''; 
    private $password = ''; 
    private $db_name = '';
    public $db ;  

    public function __construct () {
        $this -> connect() ;
    }
    public function connect (){
        $this -> db = new \mysqli ($this -> hostname, $this -> username, $this -> password,
        $this -> db_name);
        if ($this -> db ->connect_error){
            die('database tidak terkoneksi'. $this -> db ->connect_error);
        }
        return $this -> db ; 
    }
}