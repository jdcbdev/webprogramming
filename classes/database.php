<?php

class Database{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'sample_db';

    protected $connection;

    function connect(){
        if($this->connection === null){
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
        }

        return $this->connection;
    }
}

// Uncomment the lines below to test the connection by creating an instance of the Database class and calling the connect() method.
// $obj = new Database();
// $obj->connect();
