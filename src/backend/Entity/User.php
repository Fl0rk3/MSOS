<?php

declare(strict_types=1);

namespace MSOS\backend\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users', schema: 'msos')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $user_id = null;

    #[ORM\Column(type: Types::STRING, length: 40, unique: true, nullable: false)]
    private string $username;

    #[ORM\Column(type: Types::STRING, length: 256, nullable: false)]
    private string $password;

    #[ORM\Column(type: Types::INTEGER, nullable: false, options: ['default' => FALSE], columnDefinition: 'tinyint(1) NOT NULL')]
    private int $is_admin;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getIsAdmin(): int
    {
        return $this->is_admin;
    }

    public function setIsAdmin(int $is_admin): void
    {
        $this->is_admin = $is_admin;
    }

    /**
     * @return array{user_id:int, username:string, is_admin:int}
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'username' => $this->username,
            'is_admin' => $this->is_admin
        ];
    }
}