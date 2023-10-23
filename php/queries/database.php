<?php

require_once(__DIR__ . '/config.php');

class Database extends PDO
{

    private $user = DBUSER;
    private $password = DBPWD;
    private $database = DBNAME;
    private $host = DBHOST;

    public function __construct()
    {

        parent::__construct("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password);

        try {
            $conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
