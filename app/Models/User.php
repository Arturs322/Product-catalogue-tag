<?php

namespace App\Models;

use Carbon\Carbon;

class User
{
    private string $id;
    private string $email;
    private string $name;
    private string $password;
    private string $createdAt;

    public function __construct(string $id, string $email, string $name, string $password, ?string $createdAt = null)
    {

        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->createdAt = $createdAt ?? Carbon::now();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}