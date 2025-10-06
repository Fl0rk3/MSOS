<?php

namespace MSOS\Php\Queries;

use MSOS\Config\Database;
use MSOS\Php\Subject;
use PDO;

class SubjectQuery
{
    private Database $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function getSubject(string $name): Subject
    {
        $statement = $this->db->prepare("SELECT * FROM msos.subjects WHERE name=:name");
        $statement->execute(array(':name' => $name));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $subject_id = $result[0]['subject_id'];
        $name = $result[0]['name'];

        return new Subject($subject_id, $name);
    }

    function getSubjectsList()
    {
        $statement = $this->db->prepare("SELECT * FROM msos.subjects");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

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
            $_SESSION['subject_add'] = "Dodano przedmiot.";
        } else {
            $_SESSION['subject_add'] = "Przedmiot już istnieje.";
        }
    }
}