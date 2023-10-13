<?php

require_once(__DIR__ . '/database.php');

class LoginQuery
{
    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    public function loginUser($username, $password)
    {
        $statement = $this->db->prepare("SELECT user_id, username, is_admin FROM msos.users WHERE username=:username AND password=:password");
        $statement->execute(array(':username' => $username, ':password' => $password));
        $count = $statement->rowCount();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($count != 0) {
            return $result;
        } else {
            return null;
        }
    }
}
