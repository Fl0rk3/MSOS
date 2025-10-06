<?php

declare(strict_types=1);

namespace MSOS\backend\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'subjects')]
class Subject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $subject_id = null;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $user_id = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getSubjectId(): ?int
    {
        return $this->subject_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

}