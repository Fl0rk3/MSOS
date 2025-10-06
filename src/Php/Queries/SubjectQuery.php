<?php

namespace Msos\Php\queries;

use Database;
use Msos\Php\Subject;

require_once(__DIR__ . '/database.Php');
require_once(__DIR__ . '/../Subject.php');

class SubjectQuery
{
    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function getSubject($name)
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