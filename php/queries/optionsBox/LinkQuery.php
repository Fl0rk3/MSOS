<?php

require_once(dirname(__DIR__, 1) . '\database.php');

class LinkQuery
{
    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function addLink($user_id, $name, $url)
    {
        $statement = $this->db->prepare("INSERT INTO msos.links (user_id, name, url) VALUES (:user_id, :name, :url)");
        $statement->execute(array(':user_id' => $user_id, ':name' => $name, ':url' => $url));

        return true;
    }
}
