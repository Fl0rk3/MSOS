<?php

require_once(__DIR__ . '/database.php');

class RegisterQuery
{
    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    public function registerUser($username, $password)
    {
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        $statement = $this->db->prepare("INSERT INTO msos.users (username, password, is_admin) VALUES (:username, :password, 0)");
        $statement->execute(array(':username' => $username, ':password' => $hash_password));
        $count = $statement->rowCount();
        if ($count > 0) {
            $id = $this->db->lastInsertId();
            return [$id, $username, 0];
        }

        return null;
    }
}
