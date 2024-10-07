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
        $userLinks = $this->getUserLinks($user_id);

        foreach ($userLinks as $link) {
            if ($name == $link['name'] || $url  == $link['url']) {
                return false;
            }
        }

        $statement = $this->db->prepare("INSERT INTO msos.links (user_id, name, url) VALUES (:user_id, :name, :url)");
        $statement->execute(array(':user_id' => $user_id, ':name' => $name, ':url' => $url));

        return true;
    }

    function removeLink($user_id, $name): bool
    {
        $userLinks = $this->getUserLinks($user_id);

        foreach ($userLinks as $link) {
            if ($name == $link['name']) {
                $statement = $this->db->prepare("DELETE FROM msos.links WHERE user_id = :user_id AND name = :name");
                $statement->execute(array(':user_id' => $user_id, ':name' => $name));

                return true;
            }
        }

        return false;
    }

    function getUserLinks($user_id): array
    {
        $statement = $this->db->prepare("SELECT link_id, name, url FROM msos.links WHERE user_id = :user_id");
        $statement->execute(array(':user_id' => $user_id));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
