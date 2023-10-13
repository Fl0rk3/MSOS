<?php

class Login
{
    private $user_id;
    private $username;
    private $is_admin;

    function __construct($user_id, $username, $is_admin)
    {
        $this->user_id = $user_id;
        $this->username = $username;
        $this->is_admin = $is_admin;
    }

    public function getUsername()
    {
        return $this->username;
    }
}
