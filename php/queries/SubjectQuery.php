<?php

require_once('database.php');

class SubjectQuery
{
    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function getSubjectsList()
    {
        $statement = $this->db->prepare("SELECT * FROM msos.subjects");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_COLUMN);

        return $result;
    }

    function addSubject($name)
    {
        $statement = $this->db->prepare("SELECT * FROM msos.subjects WHERE name=:name");
        $statement->execute(array(':name' => $name));
        $result = count($statement->fetchALL(PDO::FETCH_ASSOC));

        if ($result == 0) {
            $statement = $this->db->prepare("INSERT INTO msos.subjects VALUES (null, :name)");
            $statement->execute(array(':name' => $name));
            $_SESSION['added'] = "Dodano!";
        } else {
            $_SESSION['added'] = "Data zajęta!";
        }
    }
}