<?php

namespace MSOS\Php;

class Subject
{
    private int $subject_id;
    public string $name;

    public function __construct(int $subject_id, string $name)
    {
        $this->subject_id = $subject_id;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

}