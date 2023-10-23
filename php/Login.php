<?php

include_once(__DIR__ . "/queries/optionsBox/LinkQuery.php");
include_once(__DIR__ . "/optionsBox/Links.php");

class Login
{
    private $user_id;
    private $username;
    private $is_admin;
    private $Links;
    private $user_links;

    function __construct($user_id, $username, $is_admin)
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

    public function reloadLinks(): void
    {
        $this->user_links = (new LinkQuery())->getUserLinks($this->user_id);
    }

    public function getLinks(): Links
    {
        return $this->Links;
    }

    public function getUserLinks(): array
    {
        return $this->user_links;
    }
}
