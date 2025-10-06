<?php

namespace MSOS\Php\Queries;

use MSOS\Config\Database;
use PDO;

class LoginQuery
{
    private Database $db;

    function __construct()
    {
        $this->db = new Database();
    }

    public function isUserExist(string $username): bool
    {
        $statement = $this->db->prepare("SELECT user_id, username, is_admin FROM msos.users WHERE username=:username");
        $statement->execute(array(':username' => $username));
        $count = $statement->rowCount();
        if ($count != 0) {
            return true;
        }

        return false;
    }

    /**
     * @return array<int, array<string, string|int>>|null
     */
    public function loginUser(string $username, string $password): ?array
    {
        $statement = $this->db->prepare("SELECT user_id, username, password, is_admin FROM msos.users WHERE username=:username");
        $statement->execute(array(':username' => $username));
        $count = $statement->rowCount();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($count != 0) {
            if (password_verify($password, $result[0]['password'])) {
                return $result;
            }
        }

        return null;
    }
}
