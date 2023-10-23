<?php

require_once(dirname(__DIR__, 1) . '\database.php');

class LinkQuery
{
    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function addLink($user_id, $name, $url): bool
    {
        $statement = $this->db->prepare("INSERT INTO msos.links (user_id, name, url) VALUES (:user_id, :name, :url)");
        $statement->execute(array(':user_id' => $user_id, ':name' => $name, ':url' => $url));

        return true;
    }

    function getUserLinks($user_id): array
    {
        $statement = $this->db->prepare("SELECT name, url FROM msos.links WHERE user_id = :user_id");
        $statement->execute(array(':user_id' => $user_id));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
