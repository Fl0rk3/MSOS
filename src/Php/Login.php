<?php

namespace MSOS\Php;

use MSOS\Php\OptionsBox\Links;
use MSOS\Php\Queries\OptionsBox\LinkQuery;

class Login
{
    private int $user_id;
    private string $username;
    private int $is_admin;
    private Links $Links;
    /**
     * @var array<int, array<string, mixed>>
     */
    private array $user_links;

    function __construct(int $user_id, string $username, int $is_admin)
    {
        $this->user_id = $user_id;
        $this->username = $username;
        $this->is_admin = $is_admin;
        $this->Links = new Links();
        $this->user_links = (new LinkQuery())->getUserLinks($this->user_id);
    }

    public function getUserID(): int
    {
        return $this->user_id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getIsAdmin(): int
    {
        return $this->is_admin;
    }

    public function reloadLinks(): void
    {
        $this->user_links = (new LinkQuery())->getUserLinks($this->user_id);
    }

    public function getLinks(): Links
    {
        return $this->Links;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getUserLinks(): array
    {
        return $this->user_links;
    }
}
