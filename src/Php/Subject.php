<?php

namespace MSOS\Php;

class Subject
{
    private $subject_id;
    public $name;

    public function __construct($subject_id, $name)
    {
        $this->subject_id = $subject_id;
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

}