<?php

namespace Msos\Php\queries;
use Database;

require_once(__DIR__ . '/database.Php');

class RegisterQuery
{
    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    public function registerUser($username, $password): array|null
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
