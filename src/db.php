<?php

class db {
    private $dbhost = "localhost";
    private $dbuser = "root";
    private $dbpass = "";
    private $dbname = "evolunteer";
    private $conn;
    public $error;
    public function connect() {
        $this->conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

}
?>