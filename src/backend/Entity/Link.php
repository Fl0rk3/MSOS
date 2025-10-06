<?php

declare(strict_types=1);

namespace MSOS\backend\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'links')]
class Link
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $link_id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(
        name: 'user_id',
        referencedColumnName: 'user_id',
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private User $user;

    #[ORM\Column(type: Types::STRING, length: 30)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 80)]
    private string $url;

    public function __construct(User $user, string $name, string $url)
    {
        $this->user = $user;
        $this->name = $name;
        $this->url = $url;
    }

    public function getLinkId(): int
    {
        return $this->link_id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
}